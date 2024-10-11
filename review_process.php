<?php
require_once("db.php");
require_once("globals.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

//Recebendo o tipo do form.
$type = filter_input(INPUT_POST, "type");
//Resgatando dados do usuário.
$userData = $userDao->verifyToken();

if ($type == "create") {
}
