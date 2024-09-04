<?php
include 'apiKey.php';
$apiKey = getenv('API_KEY');
$token = getenv('TOKEN');

function getMovieList($searchQuery = null, $pageNumber = 1)
{
    global $apiKey;
    global $token;
    $curl = curl_init();

    if ($searchQuery) {
        $url = "https://api.themoviedb.org/3/search/movie?query=" . urlencode($searchQuery) . "&language=en-US&page=$pageNumber&include_adult=false";
    } else {
        $url = "https://api.themoviedb.org/3/movie/popular?language=en-US&page=$pageNumber";
    }

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $responseData = json_decode($response, true);
        return $responseData;
    }
}

function isPosterAvailable($posterPath)
{
    if ($posterPath == "") {
        $noImage = "resources/images/noimage.jpeg";
        return $noImage;
    } else {
        return "https://image.tmdb.org/t/p/original/$posterPath";
    }
}

function getMovieDetail($movieId)
{
    global $key;
    global $token;
    $curl = curl_init();

    $url = "https://api.themoviedb.org/3/movie/$movieId?language=en-US";

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $responseData = json_decode($response, true);
        return $responseData;
    }
}

function getProvider($movieId)
{
    global $key;
    global $token;
    $curl = curl_init();

    $url = "https://api.themoviedb.org/3/movie/$movieId/watch/providers";

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $responseData = json_decode($response, true);
        return $responseData;
    }
}
