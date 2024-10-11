<?php
require_once("templates/header.php");

//Verificar se o usuário esta autenticado.
require_once("models/Movie.php");
require_once("dao/MovieDAO.php");

//Pegando o id do filme.
$id = filter_input(INPUT_GET, "id");

$movie;

$movieDao = new MovieDAO($conn, $BASE_URL);

if (empty($id)) {

    $message->setMessage("Filme não foi encontrado", "error", "index.php");
} else {
    $movie = $movieDao->findById($id);

    //Verificação pra ver se o filme existe.
    if (!$movie) {
        $message->setMessage("Filme não foi encontrado", "error", "index.php");
    } else {
    }
}

//Checar se o filme é do usuário.
$userOwnsMovie = false;

if (!empty($userData)) {
    if ($userData->id === $movie->users_id) {
        $userOwnsMovie = true;
    }
}

//Resgatar as revies dos filmes.
