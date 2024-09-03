<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Recommendation</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="./resources/css/ribbon.css">
    <link rel="stylesheet" href="./resources/css/modalScroll.css">
    <script src="./resources/javascripts/modal.js"></script>
</head>

<body class="bg-gray-900 text-white font-sans">
    <header class="p-5 bg-gray-800 text-center">
        <h1 class="text-4xl font-bold mb-4">Movie Recommendation</h1>
        <form class="flex justify-center" method="get" action="">
            <input type="text" name="search" id="search" placeholder="Search for a movie" class="p-3 w-full md:w-1/2 lg:w-1/3 rounded-lg text-gray-900" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="ml-2 p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Search</button>
        </form>
    </header>

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6">Popular Movies</h2>
        <main class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php
            include 'movie.php';
            global $rankNum;
            global $pageNumber;
            global $movies;
            $searchQuery = isset($_GET['search']) ? $_GET['search'] : null;
            $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $rankNum = $pageNumber * 20 - 19;
            $movies = getMovieList($searchQuery, $pageNumber);

            foreach ($movies['results'] as $movie) {
                echo "
                <div class='bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition duration-300 hover:scale-105'>
                    <div id='ribbon' class='ribbon ribbon-top-right'><span> # " . $rankNum . "</span></div>
                    <img loading='lazy' class='w-full h-auto' src='" . isPosterAvailable($movie['poster_path']) . "' alt='" . $movie['title'] . " Poster'>
                    <div class='p-4'>
                        <h3 class='text-xl font-bold mb-2'>" . $movie['title'] . "</h3>
                        <p class='text-gray-400 text-sm mb-4'>" . substr($movie['overview'], 0, 100) . "...</p>
                        <button class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded' onclick=\"fetchMovieDetails(" . $movie['id'] . ")\">
                            Find out more 
                            <span class='material-symbols-outlined align-middle'>
                                arrow_right_alt
                            </span>
                        </button>
                    </div>
                </div>";
                $rankNum++;
            }
            ?>
        </main>
    </div>

    <nav aria-label="Page navigation">
        <ul class="flex items-center justify-center -space-x-px h-10 text-base">
            <?php
            $prevPage = $pageNumber > 1 ? $pageNumber - 1 : 1;
            $nextPage = $pageNumber + 1;
            $totalPages = 32;

            // Previous Page Link
            echo "<li><a href='?search=$searchQuery&page=$prevPage' class='flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'><span class=\"sr-only\">Previous</span><svg class=\"w-3 h-3 rtl:rotate-180\" aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 6 10\"><path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M5 1 1 5l4 4\" /></svg></a></li>";

            // Dynamic Page Number Links
            $startPage = max(1, $pageNumber - 2);
            $endPage = min($totalPages, $pageNumber + 2);

            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $pageNumber) {
                    echo "<li><a href='?search=$searchQuery&page=$i' class='z-10 flex items-center justify-center px-4 h-10 leading-tight bg-blue-500 border border-blue-300 hover:bg-blue-100 hover:text-blue-700'>$i</a></li>";
                } else {
                    echo "<li><a href='?search=$searchQuery&page=$i' class='flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'>$i</a></li>";
                }
            }

            // Next Page Link
            echo "<li><a href='?search=$searchQuery&page=$nextPage' class='flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700'><span class=\"sr-only\">Next</span><svg class=\"w-3 h-3 rtl:rotate-180\" aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 6 10\"><path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"m1 9 4-4-4-4\" /></svg></a></li>";
            ?>
        </ul>
    </nav>

</body>

<!-- Modal -->
<div id="movieModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden z-50 pu-50">
    <div class="relative bg-gray-800 rounded-lg overflow-hidden shadow-xl max-w-6xl w-full p-4">
        <div class="flex justify-between items-center">
            <h2 id="modalTitle" class="text-3xl font-extrabold pl-3 text-white mb-4"></h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white">&times;</button>
        </div>

        <!-- Scrollable content container -->
        <div id="modalContent" class="overflow-y-auto max-h-80">
            <div id="modalGrid" class="grid md:grid-cols-2">
                <div id="modalTrailer" class="mb-4 p-3 rounded-lg overflow-hidden shadow-lg"></div>
                <p id="modalOverview" class="text-lg p-3 text-gray-300 mb-4"></p>

                <div class="grid col-span-2 grid-cols-3 gap-4 p-3">
                    <div>
                        <p class="text-sm font-medium text-gray-400"><strong>Genres:</strong> <span id="modalGenres"></span></p>
                        <p class="text-sm font-medium text-gray-400"><strong>Release Date:</strong> <span id="modalReleaseDate"></span></p>
                        <p class="text-sm font-medium text-gray-400"><strong>Popularity:</strong> <span id="modalPopularity"></span></p>
                        <p class="text-sm font-medium text-gray-400"><strong>Runtime:</strong> <span id="modalRuntime"></span></p>
                    </div>
                    <!-- Buy Providers Section -->
                    <div class="grid grid-cols-1 col-span-1 gap-4 p-3">
                        <p class="text-sm font-medium text-gray-400">
                        <div>Buy:</div> <span id="modalBuyProviders"></span></p>
                    </div>

                    <!-- Rent Providers Section -->
                    <div class="grid grid-cols-1 gap-4 p-3">
                        <p class="text-sm font-medium text-gray-400">
                        <div>Rent:</div> <span id="modalRentProviders"></span></p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


</html>