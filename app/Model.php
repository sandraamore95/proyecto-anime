<?php

class Model extends PDO
{

    protected $conexion;
    private static $instance = null;

    public function __construct()
    {
        //recupero la instancia para utilizar los metodos
        try {
            parent::__construct(
                'mysql:host=' . Config::$mvc_bd_hostname . ';dbname=' . Config::$mvc_bd_nombre . '',
                Config::$mvc_bd_usuario,
                Config::$mvc_bd_clave
            );
            parent::exec("set names utf8");
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "<p>Error: No puede conectarse con la base de datos.</p>\n";
            echo "<p>Error: " . $e->getMessage();
        }
    }

    public static function GetInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }



    public function validaLogear($user, $passw)
    {
        try {
            $estaDentro = false;
            $consulta = "select * from usuario where username like :username";
            $result = $this->prepare($consulta);
            $result->bindParam(':username', $user);
            $result->execute();

            $rows = $result->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                if (password_verify($passw, $row['passw'])) {
                    echo 'Success.';
                    $estaDentro = true;
                }
            }
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage();
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
        }
        return $estaDentro;
    }



    public function getEmail($user)
    {
        $email = "";
        $consulta = "select email from usuario where username like :username ";
        $result = $this->prepare($consulta);
        $result->bindParam(':username', $user);

        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_NUM);

        foreach ($rows as $row) {

            $email = $row[0];
        }
        return $email;
    }

    public function getFoto($id)
    {
        $foto = " ";
        $consulta = "select foto from usuario where id like :id ";
        $result = $this->prepare($consulta);
        $result->bindParam(':id', $id);

        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_NUM);

        foreach ($rows as $row) {

            $foto = $row[0];
        }
        return $foto;
    }

public function getPermiso($user)
    {
        $permiso = " ";
        $consulta = "select permiso from usuario where username like :username ";
        $result = $this->prepare($consulta);
        $result->bindParam(':username', $user);

        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_NUM);

        foreach ($rows as $row) {

            $permiso = $row[0];
        }
        return $permiso;
    }



    public function validaRegister($email, $user)
    {
        $correcto = false;

        try {
            $consulta = "select * from usuario where email=:email or username=:username";
            $result = $this->prepare($consulta);
            $result->bindParam(':email', $email);
            $result->bindParam(':username', $user);
            $result->execute();

            if ($result->rowCount() == 0) {
                $correcto = true;
            }
        } catch (PDOException $e) {

            echo "<p>Error: " . $e->getMessage();
        }
        return $correcto;
    }

    public function insertarUsuario($username, $email, $fecha_nac, $passw, $nombre, $foto)
    {
        $consulta = "INSERT INTO usuario (username, email,fecha_nac,passw,nombre,foto,about,permiso,reset_passw) 
        VALUES (?,?,?,?,?,?,'Bienvenidos a mi perfil !' , 1 ,0)";
        $result = $this->prepare($consulta);
        $result->bindParam(1, $username);
        $result->bindParam(2, $email);
        $result->bindParam(3, $fecha_nac);
        $result->bindParam(4, $passw);
        $result->bindParam(5, $nombre);
        $result->bindParam(6, $foto);
      
      

        $result->execute();
        if ($result->rowCount() > 0) {
            echo '<p class="error">La persona ha sido registrada en la base de datos</p>';
        }
        return $result;
    }

    public function getId($email)
    {

        $consulta = "select id from usuario where email like :email ";
        $result = $this->prepare($consulta);
        $result->bindParam(':email', $email);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_NUM);
        foreach ($rows as $row) {
            $id = $row[0];
        }
        return $id;
    }

    public function isreset_passw($user){
        
        $consulta = "select reset_passw from usuario where username=:username";
        $result = $this->prepare($consulta);
        $result->bindParam(':username', $user);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_NUM);
        foreach ($rows as $row) {
            $reset_passw = $row[0];
        }
        return $reset_passw;
    }


    public function getUser($email)
    {

        $consulta = "select username from usuario where email like :email ";
        $result = $this->prepare($consulta);
        $result->bindParam(':email', $email);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_NUM);
        foreach ($rows as $row) {
            $user = $row[0];
        }
        return $user;
    }
    public function getallemails(){
       
        $consulta = "select email from usuario";
        $result = $this->prepare($consulta);
        
        $result->execute();


        //recorremos los registros para almacenarlos en el array
        while ($registro = $result->fetch(PDO::FETCH_ASSOC)) {
            //echo $registro['username'] . "  ";
            $usuarios_email[] = $registro;
            
        }
        return $usuarios_email;
    }
    



    public function getUsuarioDatos($id)
    {
        //creamos array para almacenar los usuarios

        $consulta = "select * from usuario where id like :id ";
        $result = $this->prepare($consulta);
        $result->bindParam(':id', $id);
        $result->execute();


        //recorremos los registros para almacenarlos en el array
        while ($registro = $result->fetch(PDO::FETCH_ASSOC)) {
            //echo $registro['username'] . "  ";
            $usuarios[] = $registro;
        }
        return $usuarios;
    }


    //UPDATE USUARIO
    public function UpdateUser($id, $username, $email, $nombre, $foto, $about)
    {
        $consulta = " UPDATE usuario SET username='$username' , email='$email' ,nombre='$nombre',foto='$foto',about='$about' where id ='$id'";
        $result = $this->prepare($consulta);
        $result->execute();
        return $result;
    }

    // REQUEST NOTIFICATIONS
    public function request_notification($my_id, $send_data)
    {
        try {
            $sql = "SELECT sender, username, foto FROM `friend_request` JOIN usuario ON friend_request.sender = usuario.id WHERE receiver = ?";

            $stmt = $this->prepare($sql);
            $stmt->execute([$my_id]);
            if ($send_data) {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            } else {
                return $stmt->rowCount();
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    public function ForgottenPassword($email)
    {

        $consulta = "select email from usuario where email='$email'";
        $result = $this->prepare($consulta);
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        }
    }

    public function UpdatePassw($email)
    {
        //generar contraseña aleatoria
        //Carácteres para la contraseña
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $password = "";
        //Reconstruimos la contraseña segun la longitud que se quiera
        for ($i = 0; $i < 5; $i++) {
            //obtenemos un caracter aleatorio escogido de la cadena de caracteres
            $password .= substr($str, rand(0, 62), 1);
        }
        //Mostramos la contraseña generada
        $passwHash = password_hash($password, PASSWORD_DEFAULT);
        $consulta = "UPDATE usuario SET passw='$passwHash' , reset_passw=1   where email ='$email'  ";
        $result = $this->prepare($consulta);
        $result->execute();
        return $password;
    }
    public function resetPassw($id, $passw)
    {

        $consulta = " UPDATE usuario SET passw=? , reset_passw=0 where id ='$id'";
        $result = $this->prepare($consulta);
        $result->bindParam(1, $passw);
        $result->execute();
        return $result;
    }

    public function InsertaDibujo($imagen, $id_usuario)
    {
        $consulta = "INSERT into images_tabla (imagenes, creado,id_usuario) 
        VALUES ('$imagen', now(), '$id_usuario' )  ";
        $result = $this->prepare($consulta);
        $result->execute();
        header('Location: index.php?ctl=misdibujos&id=' . $_SESSION['id'] . '');
        return $result;
    }

    public function InsertaComentario($comentario, $id_usuario, $id_dibujo)
    {

        $consulta = "INSERT into comentarios  (`id`, `texto`, `id_usuario`, `id_dibujo`) VALUES (NULL, '$comentario', '$id_usuario', '$id_dibujo') ";

        $result = $this->prepare($consulta);

        $result->execute();
        header('Location: index.php?ctl=dibujo&id_dibujo=' . $id_dibujo . '');
        return $result;
    }

    public function InsertaNotificacion($sender, $receiver, $id_dibujo) //quien inserta el comentario
    {

        $consulta = "INSERT into notificaciones (`id`, `sender`, `receiver`,`id_dibujo`,`leido`) VALUES (NULL, '$sender','$receiver',$id_dibujo ,0) ";

        $result = $this->prepare($consulta);

        $result->execute();
        //header('Location: index.php?ctl=dibujo&id_dibujo='.$id_dibujo.'');
        return $result;
    }
    public function getnotificaciones($receiver)
    {
        try {

            $consulta = "select * from notificaciones where receiver=$receiver and leido=0";
            $result = $this->query($consulta);
            return $result->fetchAll();
        } catch (PDOException $e) {

            echo "<p>Error: " . $e->getMessage();
        }
    }
    public function updateNotificacion($id_dibujo)
    {
        $consulta = " UPDATE notificaciones SET leido=1 where id_dibujo ='$id_dibujo'";
        $result = $this->prepare($consulta);
        $result->execute();
        return $result;
    }


    public function InsertaFavorito($id_usuario, $imagen, $mal_id)
    {

        $consulta = "INSERT into favoritos (id_usuario,mal_id,imagen) VALUES ('$id_usuario','$mal_id', '$imagen' )  ";
        $result = $this->prepare($consulta);
        $result->execute();
        header('Location: index.php?ctl=anime&var1=' . $mal_id . '');
        return $result;
    }

    public function EliminarFavorito($id_usuario, $mal_id)
    {

        $consulta = "DELETE FROM `favoritos` WHERE mal_id=:mal_id and id_usuario=:id_usuario";

        $result = $this->prepare($consulta);
        $result->bindValue(':mal_id', $mal_id, PDO::PARAM_INT);
        $result->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);

        $result->execute();
        header('Location: index.php?ctl=anime&var1=' . $mal_id . '');
    }


    public function leerDibujo($id_usuario)
    {

        $consulta = "SELECT * FROM images_tabla WHERE id_usuario='$id_usuario';";

        $result = $this->prepare($consulta);

        $result->execute();

        $dibujo = [];
        //recorremos los registros para almacenarlos en el array
        while ($registro = $result->fetch(PDO::FETCH_ASSOC)) {
            //echo $registro['username'] . "  ";

            array_push($dibujo, $registro);
        }

        return $dibujo;
    }

    public function borrarDibujo($id_dibujo)
    {
        $consulta = "DELETE FROM `images_tabla` WHERE id=:id_dibujo";

        $result = $this->prepare($consulta);
        $result->bindValue(':id_dibujo', $id_dibujo, PDO::PARAM_INT);

        $result->execute();

        header('Location: index.php?ctl=misdibujos&id=' . $_SESSION['id'] . '');
    }


    public function getDibujo($id_dibujo)
    {

        //creamos array para almacenar los usuarios

        $consulta = "select imagenes from images_tabla where id like :id ";

        $result = $this->prepare($consulta);
        $result->bindParam(':id', $id_dibujo);

        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_NUM);

        foreach ($rows as $row) {
            // l a imagen 
            $foto = $row[0];
        }
        return $foto;
    }


    public function getComentarios($id_dibujo)
    {
        //creamos array para almacenar los usuarios

        $consulta = "select * from comentarios where id_dibujo like :id_dibujo ORDER BY id DESC";
        $result = $this->prepare($consulta);
        $result->bindParam(':id_dibujo', $id_dibujo);
        $result->execute();
        $comentarios = [];

        //recorremos los registros para almacenarlos en el array
        while ($registro = $result->fetch(PDO::FETCH_ASSOC)) {
            //echo $registro['username'] . "  ";
            $comentarios[] = $registro;
        }
        return $comentarios;
    }
    //-----FRIIIIIENDSSSSSSSSSSS----

    public function getFriends($my_id, $send_data)
    {
        try {
            $sql = "SELECT * FROM `friends` WHERE user_one = :my_id OR user_two = :my_id";
            $stmt = $this->prepare($sql);
            $stmt->bindValue(':my_id', $my_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($send_data) {

                $return_data = [];
                $all_users = $stmt->fetchAll(PDO::FETCH_OBJ);

                foreach ($all_users as $row) {
                    if ($row->user_one == $my_id) {
                        $get_user = "SELECT id, username, foto FROM `usuario` WHERE id = ?";
                        $get_user_stmt = $this->prepare($get_user);
                        $get_user_stmt->execute([$row->user_two]);
                        array_push($return_data, $get_user_stmt->fetch(PDO::FETCH_OBJ));
                    } else {
                        $get_user = "SELECT id, username, foto FROM `usuario` WHERE id = ?";
                        $get_user_stmt = $this->prepare($get_user);
                        $get_user_stmt->execute([$row->user_one]);
                        array_push($return_data, $get_user_stmt->fetch(PDO::FETCH_OBJ));
                    }
                }

                return $return_data;
            } else {
                return $stmt->rowCount();
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // MAKE FRIENDS
    public function make_friends($my_id, $user_id)
    {

        try {

            $delete_pending_friends = "DELETE FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";
            $delete_stmt = $this->prepare($delete_pending_friends);
            $delete_stmt->bindValue(':my_id', $my_id, PDO::PARAM_INT);
            $delete_stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_stmt->execute();
            if ($delete_stmt->execute()) {

                $sql = "INSERT INTO `friends`(user_one, user_two) VALUES(?, ?)";
                $stmt = $this->prepare($sql);
                $stmt->execute([$my_id, $user_id]);
                header('Location:index.php?ctl=profile&id=' . $user_id . '');
                exit;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    public function getFavorites($id_usuario)
    {
        $consulta = "select * from favoritos where id_usuario='$id_usuario' ";
        $result = $this->prepare($consulta);
        $result->execute();
        $favoritos = [];
        //recorremos los registros para almacenarlos en el array
        while ($registro = $result->fetch(PDO::FETCH_ASSOC)) {
            //echo $registro['username'] . "  ";
            array_push($favoritos, $registro);
        }
        return $favoritos;
    }

    // CHECK IF ALREADY FRIENDS
    public function is_already_friends($my_id, $user_id)
    {
        try {
            $sql = "SELECT * FROM `friends` WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";

            $stmt = $this->prepare($sql);
            $stmt->bindValue(':my_id', $my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //  IF I AM THE REQUEST SENDER
    public function am_i_the_req_sender($my_id, $user_id)
    {
        try {
            $sql = "SELECT * FROM `friend_request` WHERE sender = ? AND receiver = ?";
            $stmt = $this->prepare($sql);
            $stmt->execute([$my_id, $user_id]);

            if ($stmt->rowCount() === 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //  IF I AM THE RECEIVER 
    public function am_i_the_req_receiver($my_id, $user_id)
    {

        try {
            $sql = "SELECT * FROM `friend_request` WHERE sender = ? AND receiver = ?";
            $stmt = $this->prepare($sql);
            $stmt->execute([$user_id, $my_id]);

            if ($stmt->rowCount() === 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // CHECK IF REQUEST HAS ALREADY BEEN SENT
    public function is_request_already_sent($my_id, $user_id)
    {

        try {
            $sql = "SELECT * FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $stmt = $this->prepare($sql);
            $stmt->bindValue(':my_id', $my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // MAKE PENDING FRIENDS (SEND FRIEND REQUEST)
    public function make_pending_friends($my_id, $user_id)
    {

        try {
            $sql = "INSERT INTO `friend_request`(sender, receiver) VALUES(?,?)";
            $stmt = $this->prepare($sql);
            $stmt->execute([$my_id, $user_id]);
            header('Location:index.php?ctl=profile&id=' . $user_id . '');
            exit;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // CANCEL FRIEND REQUEST
    public function cancel_or_ignore_friend_request($my_id, $user_id)
    {

        try {
            $sql = "DELETE FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $stmt = $this->prepare($sql);
            $stmt->bindValue(':my_id', $my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            header('Location:index.php?ctl=profile&id=' . $user_id . '');
            exit;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    // DELETE FRIENDS 
    public function delete_friends($my_id, $user_id)
    {
        try {
            $delete_friends = "DELETE FROM `friends` WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";
            $delete_stmt = $this->prepare($delete_friends);
            $delete_stmt->bindValue(':my_id', $my_id, PDO::PARAM_INT);
            $delete_stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_stmt->execute();

            header('Location:index.php?ctl=profile&id=' . $user_id . '');
            exit;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }



    public function deleteUser($id_usuario)
    {
        $consulta = "DELETE FROM `favoritos` WHERE id_usuario='$id_usuario';
        DELETE FROM images_tabla WHERE id_usuario='$id_usuario';
        DELETE FROM friend_request WHERE sender='$id_usuario';
        DELETE FROM friends WHERE user_two='$id_usuario';
        DELETE FROM usuario WHERE id='$id_usuario';
        ";;
        $result = $this->prepare($consulta);
        $result->execute();
        header('Location: index.php?ctl=login');
    }
}
