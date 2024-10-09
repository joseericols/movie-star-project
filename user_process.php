<?php
require_once("db.php");
require_once("globals.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");

//Atualizar usuario
if ($type === "update") {
    //Resgata dados do usuario
    $userData = $userDao->verifyToken();

    //Receber dados do post
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "last");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    //Criar novo objeto de usuario
    $user = new User();

    //Preencher os dados do usuario

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    $userDao->update($userData);

    //atualizar senha do usuario
} else if ($type === "changepassword") {
} else {
    $messsage->setMessage("Informações invalidas!", "error", "index.php");
}
