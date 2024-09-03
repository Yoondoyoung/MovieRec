<?php
include 'movie.php';

if (isset($_GET['movieId'])) {
    $movieId = $_GET['movieId'];
    $movieDetails = getMovieDetail($movieId);
    echo json_encode($movieDetails);
}
?>
