async function fetchMovieDetails(movieId) {
    try {
      const response = await fetch(`movie_detail.php?movieId=${movieId}`);
      const provideResponse = await fetch(`movie_provider.php?movieId=${movieId}`);
  
      const movie = await response.json();
      const providerData = await provideResponse.json();
  
      // Set modal content
      document.getElementById("modalTitle").textContent = movie.title;
      document.getElementById("modalOverview").textContent = movie.overview;
      document.getElementById("modalReleaseDate").textContent = movie.release_date;
      document.getElementById("modalPopularity").textContent = movie.popularity;
      document.getElementById("modalRuntime").textContent = movie.runtime ? movie.runtime + " minutes" : "N/A";
  
      // Clear previous provider data
      document.getElementById("modalBuyProviders").innerHTML = "";
      document.getElementById("modalRentProviders").innerHTML = "";
      document.getElementById("modalGenres").textContent = "";
  
      // Populate genres
      for (let i = 0; i < movie.genres.length; i++) {
        if (i === movie.genres.length - 1)
          document.getElementById("modalGenres").textContent += movie.genres[i].name;
        else {
          document.getElementById("modalGenres").textContent += movie.genres[i].name + ", ";
        }
      }
  
      // Populate provider data for Buy
      if (providerData.results && providerData.results.US && providerData.results.US.buy) {
        providerData.results.US.buy.forEach(provider => {
          const providerLogo = `<img src="https://image.tmdb.org/t/p/w45${provider.logo_path}" alt="${provider.provider_name}" class="rounded-full inline-block mr-2 p-1">`;
          document.getElementById("modalBuyProviders").innerHTML += `${providerLogo}${provider.provider_name}<br>`;
        });
      }
  
      // Populate provider data for Rent
      if (providerData.results && providerData.results.US && providerData.results.US.rent) {
        providerData.results.US.rent.forEach(provider => {
          const providerLogo = `<img src="https://image.tmdb.org/t/p/w45${provider.logo_path}" alt="${provider.provider_name}" class="rounded-full inline-block mr-2 p-1">`;
          document.getElementById("modalRentProviders").innerHTML += `${providerLogo}${provider.provider_name}<br>`;
        });
      }
  
      // Fetch trailer from TMDB API
      const trailerHtml = await fetchTrailer(movie.id);
      document.getElementById("modalTrailer").innerHTML = trailerHtml;
  
      document.getElementById("movieModal").classList.remove("hidden");
    } catch (error) {
      console.error("Error fetching movie details:", error);
    }

    // Add event listeners for Esc key press and click outside the modal
    document.addEventListener("keydown", handleKeyPress);
    document.addEventListener("click", handleClickOutside);
  }

// Function to handle closing the modal on Esc key press
function handleKeyPress(event) {
    if (event.key === "Escape") {
      closeModal();
    }
  }
  
  // Function to handle closing the modal when clicking outside the modal content
  function handleClickOutside(event) {
    const modal = document.getElementById("movieModal");
    if (event.target === modal) {
      closeModal();
    }
  }
function closeModal() {
  document.getElementById("movieModal").classList.add("hidden");
  document.getElementById("modalTrailer").innerHTML = "";
}

async function fetchTrailer(movieId) {
  const apiKey = "f0c00ade59eb5d068135420171d66658";
  const response = await fetch(
    `https://api.themoviedb.org/3/movie/${movieId}/videos?api_key=${apiKey}&language=en-US`
  );
  const data = await response.json();

  if (data.results.length > 0) {
    const youtubeTrailer = data.results.find(
      (video) => video.site === "YouTube" && video.type === "Trailer"
    );
    if (youtubeTrailer) {
      return `<iframe id="modalVideo" width="100%" height="315" src="https://www.youtube.com/embed/${youtubeTrailer.key}" frameborder="0" allowfullscreen></iframe>`;
    }
  }
  return '<p class="text-gray-700 dark:text-gray-300">Trailer not available</p>';
}
