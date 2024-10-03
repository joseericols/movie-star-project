<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);
//Resgata o tipo do forms.
$type = filter_input(INPUT_POST, "type");

//Verificação do tipo do forms.
if ($type == "register") {

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //Verificação de dados minimos.
    if ($name && $lastname && $email && $password) {
        //verificar se as senhas batem
        if ($password == $confirmpassword) {

            //Verificar se o e-mail já está cadastrado no sistema.
            if ($userDao->findByEmail($email) === false) {
                $user = new User();

                //criação de token e senha.
                $userToken = $user->generateToken();
                $finalPassword = $user->generatePassword($password);

                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalPassword;
                $user->token = $userToken;

                $auth = True;

                $userDao->create($user, $auth);
            }
        } else {
            //Enviar mensagem de erro se as senhas não forem iguais.
            $message->setMessage("As senhas não são iguais.", "error", "back");
        }
    } else {
        //Enviar uma mensagem de erro, de dados faltando.
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
} else if ($type == "login") {
}
