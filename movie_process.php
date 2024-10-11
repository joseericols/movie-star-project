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

//Resgata o tipo do formulario

$type = filter_input(INPUT_POST, "type");
$userData = $userDao->verifyToken();

if ($type == "create") {

    //Receber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");


    $movie = new Movie();

    //validação minima de dados
    if (!empty($title) && !empty($description) && !empty($category)) {

        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;
        $movie->users_id = $userData->id;

        //Upload de imagem do filme.
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES["image"];
            $imageTypes = ["image/jpge", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            //checando tipo da imagem
            if (in_array($image["types"], $imageTypes)) {

                //checa se imagem é jpg.
                if (in_array($image["type"], $jpgArray)) {
                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                } else {
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                }
                //Gerando o nome da imamge
                $imageName = $movie->imageGenerateName();

                imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                $movie->image = $imageName;
            } else {
                $message->setMessage("Tipo invalido de imagem, insira png ou jpg!", "error", "back");
            }
        }

        $movieDao->create($movie);
    } else {
        $message->setMessage("Você precisa adicionar pelo menos: titulo, categoria e duração.", "error", "back");
    }
} elseif ($type === "delete") {
    //Recebe os dados do form
    $id = filter_input(INPUT_POST, "id");
    $movie = $movieDao->findById($id);

    if ($movie) {
        //Verifica se o filme é do usuário.
        if ($movie->users_id == $userData->id) {
            $movieDao->destroy($movie->id);
        } else {
            $message->setMessage("Informações inválidas!", "error", "index.php");
        }
    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }
} else if ($type === "update") {
    //Receber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    //Verificar se encontrou o filme.
    if ($movieData) {
        //Verifica se o filme é do usuário.
        if ($movieData->users_id == $userData->id) {
            //validação minima de dados.
            if (!empty($title) && !empty($description) && !empty($category)) {

                //Edição de filme.
                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;

                //Uploads de imagem do filme.
                if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                    $image = $_FILES["image"];
                    $imageTypes = ["image/jpge", "image/jpg", "image/png"];
                    $jpgArray = ["image/jpeg", "image/jpg"];

                    //checando tipo da imagem
                    if (in_array($image["types"], $imageTypes)) {

                        //checa se imagem é jpg.
                        if (in_array($image["type"], $jpgArray)) {
                            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                        } else {
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
                        }

                        $movie = new Movie();
                        //Gerando o nome da imamge
                        $imageName = $movie->imageGenerateName();

                        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                        $movieData->image = $imageName;
                    } else {
                        $message->setMessage("Tipo invalido de imagem, insira png ou jpg!", "error", "back");
                    }
                }
                $movieDao->update($movieData);
            } else {
                $message->setMessage("Você precisa adcionar pelo menos: titulo, descrição e categoria", "error", "back");
            }
        } else {
            $message->setMessage("Informações inválidas!", "error", "index.php");
        }
    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }
} else {
    $message->setMessage("Informações inválidas!", "error", "index.php");
}
