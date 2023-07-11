<?php
include('libs/utils.php');

class Controller
{

    //--------------------INICIO ----------------


    public function iniciar()
    //este template muestra las categorias disponibles para entrar
    {
        $array_random = array();
        for ($i = 0; $i < 3; $i++) {
            $res = file_get_contents("https://api.jikan.moe/v4/random/anime");
            $json = json_decode($res, true);
            $anime_random = $json["data"];
            $array_random[$i] = $anime_random;
        }
        require __DIR__ . '/templates/inicio.php';
    }


    // ------------------------- LOGIN -----------------------------------------------

    public function logear()
    {
        $errors = array();
        $m = Model::GetInstance();
        if (isset($_POST['logy'])) {

            $user  = recoge("username");
            $passw = recoge("password");

            if (validarDatos($user, $passw)) {
                if ($m->validaLogear($user, $passw)) {
                    session_start();
                    $_SESSION['user_logeado'] = $user;
                    $_SESSION['momento'] = time();
                    $_SESSION['acceso'] = $m->getPermiso($user); // 1
                    $_SESSION['email'] = $m->getEmail($user);
                    $_SESSION['id'] = $m->getId($_SESSION['email']);
                    $_SESSION['avatar'] = $m->getFoto($_SESSION['id']);

                    header('Location:index.php?ctl=confirmacion&id=' . $_GET['id']);
                } else {
                    $errors['user'] = "Usuario incorrecto";
                    error_log($errors['user'] . microtime() . PHP_EOL, 3, "log_errores.txt");
                }
            } else {
                $errors['input'] = 'Datos no validos';
            }
        }
        require __DIR__ . '/templates/login.php';
    }



    //----------------------------------  REGISTRO  ------------------------------------------

    public function registrarse()
    {
        $errors = array();
        $m = Model::GetInstance();
        if (isset($_POST['registrar'])) {

            //comprobar q las 2 contraseñas coinciden
            if ($_POST['password'] == $_POST['confirm_password']) {
                $email = recoge("email");
                $nick  = recoge('username');
                $fullname = recoge('first_name') . '  ' . recoge('last_name');
                $fecha_nac = recoge("birthday");
                $passwHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

                //comprobar que se ha introducido avatar
                if ($_FILES['avatar']['size'] != 0) {
                    $fileName = 'avatar.png'; //validar la sintaxis de los inputs!>   
                    if (validarDatos($email, $nick)) {
                        if (checkemail($email)) {
                            if ($m->validaRegister($email, $nick)) {

                                //creamos la carpeta de imagenes del usuario
                                $avatar_path = 'images/user_images/' . $nick . '/';
                                if (!is_dir($avatar_path)) {
                                    mkdir($avatar_path, 0777, true);
                                    $avatar_path = 'images/user_images/' . $nick . '/' . $fileName;
                                    //copiar imagen a images/folder
                                    copy($_FILES['avatar']['tmp_name'], $avatar_path);
                                }
                                //cuando todas las validaciones son correctas-- inserta usuario
                                $m->insertarUsuario($nick, $email, $fecha_nac, $passwHash, ucwords($fullname),  $avatar_path);
                                header('Location: index.php?ctl=login');
                            } else {
                                $errors['user'] = 'Escoja otro email o nombre de usuario.';
                                error_log($errors['user'] . microtime() . PHP_EOL, 3, "log_errores.txt");
                            }
                        } else {
                            $errors['input'] = 'Email no valido';
                        }
                    } else {
                        $errors['input'] = 'Datos no validos';
                    }
                } else {
                    $errors['foto'] = 'Seleccione foto de avatar!';
                }
            } else {
                $errors['passw'] =  'Las contraseñas no coinciden';
            }
        }

        require __DIR__ . '/templates/register.php';
    }

    //----------------------------  CONFIRMACION PAGINA ---------------------------------


    public function confirmacion()
    {
        $m = Model::GetInstance();
        // tras el login te lleva al profile o a donde estabas antes dependiendo 
        //si el user viene del logeo por darle a añadir ala lista
        if (isset($_SESSION['user_anime'])) {
            $result = $_SESSION['user_anime'];
            unset($_SESSION['user_anime']);
            header('Location:index.php?ctl=anime&var1=' . $result);
        } else {

            if ($m->isreset_passw($_SESSION['user_logeado']) > 0) {
                header('Location:index.php?ctl=resetPassw');
            } else {
                header('Location:index.php?ctl=profile&id=' . $_SESSION['id'] . '');
            }
        }
    }



    public function cerrarSesion()
    {
        if (isset($_SESSION['user_logeado'])) {
            session_destroy();
        }
        header('Location:index.php?ctl=inicio');
    }





    //-------------------------   REQUEST AMIGOS ------------------------------------------
    public function funcionesRequest()
    {
        $m = Model::GetInstance();

        //COGEMOS LA OPCION DEL REQUEST
        $user_id = $_GET['id'];
        $my_id = $_SESSION['id'];

        // IF GET SEND REQUEST ACTION
        if ($_GET['action'] == 'send_req') {
            // CHECK IS REQUEST ALREADY SENT OR NOT
            // is_request_already_sent() FUNCTION RETURN TRUE OR FLASE
            if ($m->is_request_already_sent($my_id, $user_id)) {
                header("Location: index.php?ctl=profile");
            }
            // CHECK IF THIS ID IS ALREADY IN MY FRIENDS LIST.
            // THIS FUNCTION ALSO RETURN TRUE OR FLASE 
            elseif ($m->is_already_friends($my_id, $user_id)) {
                header("Location: index.php?ctl=profile");
            }
            // OTHERWISE MAKE FRIEND REQUEST
            else {
                $m->make_pending_friends($my_id, $user_id);
            }
        }
        // IF GET CANCEL REQUEST OR IGNORE REQUEST ACTION
        else if ($_GET['action'] == 'cancel_req' || $_GET['action'] == 'ignore_req') {
            $m->cancel_or_ignore_friend_request($my_id, $user_id);
        }
        // IF GET ACCEPT REQUEST ACTION
        elseif ($_GET['action'] == 'accept_req') {

            if ($m->is_already_friends($my_id, $user_id)) {
            } else {
                $m->make_friends($my_id, $user_id);
            }
        }
        // IF GET UNFRIEND REQUEST ACTION
        elseif ($_GET['action'] == 'unfriend_req') {

            $m->delete_friends($my_id, $user_id);
        } else {
            header("Location:index.php?ctl=profile&id='.$user_id.'");
        }
    }



    //----------DIBUJO  USUARIO ------------------------------

    public function recogeImagen()
    {
        $mensaje = '';
        $m = Model::GetInstance();
        $id_dibujo = recoge('id');
        $datos = $m->leerDibujo($id_dibujo);

        if (isset($_POST['añadir'])) {
            if ($_FILES['image']['size'] != 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if ($ext == 'gif' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                    //recogemos la imagen
                    $imagen_temporal = $_FILES['image']['tmp_name'];
                    // Leemos el contenido del archivo temporal en binario.
                    $data = file_get_contents($imagen_temporal);
                    $m->InsertaDibujo(addslashes($data), $_SESSION['id']);
                } else {
                    $mensaje = 'el formato no es compatible';
                }
            } else {
                $mensaje = 'No se ha seleccionado.';
            }
        }
        if (isset($_POST['borrar'])) {
            $m->borrarDibujo($id_dibujo);
        }
        require __DIR__ . '/templates/dibujos.php';
    }


    public function dibujo()
    {
        $m = Model::GetInstance();
        $receiver = recoge('receiver');
        $leido = recoge('leido');
        $id_dibujo = recoge('id_dibujo');
        if ($leido != null) {
            $m->updateNotificacion($id_dibujo);
        }

        $dibujo = $m->getDibujo($id_dibujo);

        if (isset($_POST['publicar'])) {
            $comentario = recoge('comentario');
            // inserta comentario
            $m->insertaComentario($comentario, $_SESSION['id'], $id_dibujo);
            //inserta notificacion
            $m->insertaNotificacion($_SESSION['id'], $receiver, $id_dibujo);
        }
        $matriz_comentarios = $m->getComentarios($id_dibujo);

        require __DIR__ . '/templates/dibujo.php';
    }


    //------------------------------ PERFIL --------------------------------

    public function editProfile()
    {
        $m = Model::GetInstance();
        $id = $_SESSION['id'];
        $matriz_usuarios = $m->getUsuarioDatos($id);

        if (isset($_POST['update'])) {

            //tenemos que ver si hay input file o no
            $email_input = recoge("email");
            if ($_FILES['fileImage']['size'] != 0) {

                $avatar_path = 'images/user_images/' .  $_SESSION['user_logeado'] . '/';
                if (!is_dir($avatar_path)) {
                    mkdir($avatar_path, 0777, true);
                }
                $avatar_path = 'images/user_images/' .  $_SESSION['user_logeado'] . '/avatar.png';
                copy($_FILES['fileImage']['tmp_name'], $avatar_path);

                //la nueva session de avatar con diferente foto
                $_SESSION['avatar'] = 'images/user_images/' . $_SESSION['user_logeado'] . '/avatar.png';
            }

            $name = recoge("name");
            $user = recoge("user");
            $foto_nueva = $_SESSION['avatar'];
            $about = recoge('about');
            $user = $_SESSION['user_logeado'];
            $id = $_SESSION['id'];

            if (strpos(json_encode($m->getallemails()), $email_input) !== false && $email_input != $m->getEmail($_SESSION['user_logeado'])) {
                echo "el email ya existe !";
            } else {
                $m->UpdateUser($id, $user, $email_input,  $name,  $foto_nueva, $about);
                header('Location: index.php?ctl=editprofile');
            }
        }
        require __DIR__ . '/templates/editprofile.php';
    }

    public function profile()

    {
        $m = Model::GetInstance();
        $id_seleccionado = $_GET['id'];

        //el boton del request ...
        // CHECK FRIENDS -> id_user ,id_friend 
        $is_already_friends = $m->is_already_friends($_SESSION['id'],  $id_seleccionado);

        //  IF I AM THE REQUEST SENDER -->cancel request
        $check_req_sender = $m->am_i_the_req_sender($_SESSION['id'], $id_seleccionado);
        // IF I AM THE REQUEST RECEIVER --> accetp /ignore
        $check_req_receiver = $m->am_i_the_req_receiver($_SESSION['id'], $id_seleccionado);

        $datos_usuario = $m->getUsuarioDatos($id_seleccionado);

        // DIBUJOS
        $datos = $m->leerDibujo($id_seleccionado);
        $STRING = json_encode($datos);
        $tamaño = sizeof($datos);

        // FAVORITOS ANIME
        $favoritos = $m->getFavorites($id_seleccionado);

        //FRIENDS
        $get_all_friends = $m->getFriends($id_seleccionado, true);

        // buscador
        $message = '';
        if (isset($_POST['update'])) {

            //recoger el id de dicho user 
            $email = $m->getEmail(recoge('username'));
            if (strlen($email) !== 0) {
                $id_amigo = $m->getId($email);
                header('Location: index.php?ctl=profile&id=' . $id_amigo);
            } else {
                $message = "El usuario introducido no existe";
            }
        }
        require __DIR__ . '/templates/profile.php';
    }


    public function EliminarCuenta()
    {

        $m = Model::GetInstance();
        #eliminar carpetas de user_image
        $file = 'images/user_images/' . $_SESSION['user_logeado'];
        borrar_directorio($file);
        if (isset($_SESSION['user_logeado'])) {
            session_destroy();
        }
        $m->deleteUser($_SESSION['id']);
    }

    public function resetPassw()
    {
        $errors = array();
        if (isset($_POST['change'])) {

            $passw_old = recoge('old');
            $passw1 = recoge('passw1');
            $passw2 = recoge('passw2');
            if ($passw1 == $passw2) {
                //mirar si existe la contraseña real en la base de datos
                $m = Model::GetInstance();
                $user = $_SESSION['user_logeado'];
                $email = $m->getEmail($user);
                $id = $m->getId($email);
                $matriz_usuarios = $m->getUsuarioDatos($id);

                foreach ($matriz_usuarios as $value) {

                    if (password_verify($passw_old, $value['passw'])) {
                        $errors['passw'] = 'Contraseña Cambiada!';
                        // hacer hash a lanueva contaseña
                        $passwHash = password_hash($passw2, PASSWORD_DEFAULT);
                        $m->resetPassw($id, $passwHash);
                    } else {
                        $errors['passw'] = 'No existe esa contraseña';
                    }
                }
            } else {
                $errors['passw'] = 'Las contraseñas no coinciden';
            }
        }
        require __DIR__ . '/templates/resetpassw.php';
    }


    // ---------------------------- ANIME ------------------------------------

    
    
    public function listaAnime()
    {
        //recoge la choice elegida desde el menu de inicio
        $choice = $_GET['var']; //Movie , TV, Ova, Special
        $res = file_get_contents("https://api.jikan.moe/v4/top/anime");
        $json = json_decode($res, true);
        $animes_array = $json["data"];

        $animes = array();
        foreach ($animes_array as  $anime) {
            if ($anime['type'] == $choice) {
                array_push($animes, $anime);
            }
        }

        require __DIR__ . '/templates/categorias.php';
    }



    public function recogeAnime()
    {

        $mal_id = $_GET['var1'];
        $imagen = $_GET['var2'];
        //si no esta logeado el usuario no puede darle a favoritos... le redirecciona a login
        if (!isset($_SESSION['user_logeado'])) {

            session_start();
            // tengo q pasarle el mal_id
            $_SESSION['user_anime'] = $mal_id;
            header('Location: index.php?ctl=login');
        } else {

            // si el usuario esta logeado entonces : 
            //INSERTAMOS EN LA BASE DE DATOS

            $m = Model::GetInstance();
            $m->InsertaFavorito($_SESSION['id'], $imagen, $mal_id);
        }
    }

    public function character()
    {
        $character_id = $_GET['var1'];
        $mal_id = $_GET['var1'];
        $res = file_get_contents("https://api.jikan.moe/v4/characters/$character_id/full");
        $json = json_decode($res, true);
        $character = $json["data"];
        $nick = $character['name'];
        $imagen = $character['images']['webp']['image_url'];
        $about = $character['about'];
        $person_voice = $character['voices'];



        require __DIR__ . '/templates/character.php';
    }



    public function eliminarAnime()
    {
        $resultado1 = $_GET['var1'];
        $m = Model::GetInstance();
        $m->EliminarFavorito($_SESSION['id'], $resultado1);
    }

    //-----------------------------------------------------------------------------

    public function MiLista()
    {
        $m = Model::GetInstance();
        $id_favoritos = recoge('id');
        $favoritos = $m->getFavorites($id_favoritos);

        $favoritos_array = array();
        for ($i = 0; $i < count($favoritos); $i++) {
            array_push($favoritos_array, $favoritos[$i]["mal_id"]);
        }

        require __DIR__ . '/templates/favoritos.php';
    }


    //----------------------- PASSWORD -------------------------- 
    public function ForgottenPassword()
    {

        $errors = array();
        if (isset($_POST['submit_email']) && $_POST['email']) {
            $m = Model::GetInstance();
            if (!checkemail($_POST['email'])) {
                $errors['email'] = "Email no valido";
            } else {
                if ($m->ForgottenPassword($_POST['email'])) {
                    $nick = $m->getUser($_POST['email']);
                    $new_pass = $m->UpdatePassw($_POST['email']);
                    $body = "
                    <html>
                    <head>
                    <title>HTML</title>
                    </head>
                    <body>
                    <h1>Hi : $nick  , </h1>
                    <p>We received a request to reset your password</p>
                    <p>This is your code for change your password : </p>
                    <h3> $new_pass  </h3>
                    </body>
                    </html>";
                    sendmail($_POST['email'], $body);
                } else {
                    $errors['email'] = 'No existe el email';
                }
            }
        }
        require __DIR__ . '/templates/forgotten.php';
    }






    public function anime()
    {
        $m = Model::GetInstance();
        //recogemos la id del anime
        $mal_id = $_GET['var1'];
        $res = file_get_contents("https://api.jikan.moe/v4/anime/$mal_id");
        $json = json_decode($res, true)["data"];
        $title = $json["title"];
        $type = $json['type'];
        $episodes = $json["episodes"];
        $score = $json["title"];
        $synopsis = $json['synopsis'];
        $duration = $json['duration'];
        $studios = $json['studios'][0]['name'];
        $emitido = $json['aired']['from'];
        $status = $json['status'];
        $generos = '';
        $genero = $json['genres'];
        $res2 = file_get_contents("https://api.jikan.moe/v4/anime/$mal_id/characters");
        $data_characters = json_decode($res2, true)["data"];

        for ($i = 0; $i < count($genero); $i++) {
            $generos = $generos . $genero[$i]['name'] . ' ';
        }

        $imagen = $json['images']['webp']['image_url'];
        // YOUTUBE
        $trailer = $json['trailer']['embed_url'];



        //recommendations
        $res3 = file_get_contents("https://api.jikan.moe/v4/anime/$mal_id/recommendations");
        $data = json_decode($res3, true)["data"];

        $array_recomendations = array();
        for ($i = 0; $i < count($data); $i++) {
            $recomendation = $data[$i]['entry'];
            array_push($array_recomendations, $recomendation);
        }

        require __DIR__ . '/templates/anime.php';
    }
}
