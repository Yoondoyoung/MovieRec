<?php
include 'movie.php';

if (isset($_GET['movieId'])) {
    $movieId = $_GET['movieId'];
    $movieProvider = getProvider($movieId);
    echo json_encode($movieProvider);
}
?>
