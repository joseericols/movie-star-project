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

    //upload da imagem
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        //Cheagem de tipo de imagem.
        if (in_array($image["type"], $imageTypes)) {
            //Checar se é jpg
            if (in_array($image, $jpgArray)) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);

                //imagem é um png.
            } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            $imageName = $user->imageGenerateName();

            imagejpeg($imageFile, "./img/users/" . $imageName, 100);

            $userData->image = $imageName;
        } else {
            $message->setMessage("Tipo invalido de imagem. Insira png ou jpg!", "error", "back");
        }
    }

    $userDao->update($userData);

    //atualizar senha do usuario
} else if ($type === "changepassword") {
} else {
    $messsage->setMessage("Informações invalidas!", "error", "index.php");
}
