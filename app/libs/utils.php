
<?php

//Aqui pondremos las funciones de validación de los campos

function validarDatos($e, $p)
{
    return (is_string($e) &
        is_string($p)); //is_numberic()
}


function recoge($var)
{
    if (isset($_REQUEST[$var]))
        $tmp=strip_tags(sinEspacios($_REQUEST[$var]));
        else
            $tmp= "";
            
            return $tmp;
}
function checkemail($str) {
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) 
    ? FALSE : TRUE;
}
function borrar_directorio($dirname) {
	//si es un directorio lo abro
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
        //si no es un directorio devuelvo false para avisar de que ha habido un error
	
        //recorro el contenido del directorio fichero a fichero
	 while($file = readdir($dir_handle)) {
	       if ($file != "." && $file != "..") {
                   //si no es un directorio elemino el fichero con unlink()
	            if (!is_dir($dirname."/".$file))
	                 unlink($dirname."/".$file);
	            else //si es un directorio hago la llamada recursiva con el nombre del directorio
	                 borrar_directorio($dirname.'/'.$file);
	       }
	 }
	 closedir($dir_handle);
	//elimino el directorio que ya he vaciado
	 rmdir($dirname);
	 return true;
}

function sinEspacios($frase) {
    $texto = trim(preg_replace('/ +/', ' ', $frase));
    return $texto;
}


function sendmail($email,$body){ 
    
   
    $name = "MyAnim3";  // Name of your website or yours
    $too = $email;  // mail of reciever
    $subject = "Forgot Password?";
    $from = "sandritalove9595@gmail.com";  // you mail
    $password = "iterscnazkqnfvuq";  // your mail password

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";
    $mail = new  PHPMailer\PHPMailer\PHPMailer;

    // To Here

    //SMTP Settings
    $mail->isSMTP();
    // $mail->SMTPDebug = 3;  Keep It commented this is used for debugging                          
    $mail->Host = "smtp.gmail.com"; // smtp address of your email
    $mail->SMTPAuth = true;
    $mail->Username = $from;
    $mail->Password = $password;
    $mail->Port = 587;  // port
    $mail->SMTPSecure = "tls";  // tls or ssl
    $mail->smtpConnect([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        ]
    ]);

    //Email Settings
    $mail->isHTML(true);
    $mail->setFrom($from, $name);
    $mail->addAddress($too); // enter email address whom you want to send
    $mail->Subject = $subject;
    $mail->Body = $body;
    if ($mail->send()) {
        echo "Email is sent!"; 
    } else {
        echo "Something is wrong: <br><br>" . $mail->ErrorInfo;
    }
}













function cArchivo($name, $path, $validExtensions, &$errors, $size = 200000)
{
    if($_FILES[$name]['error'] != 0){
        switch($_FILES[$name]['error']){
            case 1:
            $errors[] = "UPLOAD_ERR_INI_SIZE";
            $errors[] = "Fichero demasiado grande";
            break;
        case 2:
            $errors[] = "UPLOAD_ERR_FORM_SIZE";
            $errors[] = 'El fichero es demasiado grande';
            break;
        case 3:
            $errors[] = "UPLOAD_ERR_PARTIAL";
            $errors[] = 'El fichero no se ha podido subir entero';
            break;
        case 4:
            $errors[] = "UPLOAD_ERR_NO_FILE";
            $errors[] = 'No se ha podido subir el fichero';
            break;
        case 6:
            $errors[] = "UPLOAD_ERR_NO_TMP_DIR";
            $errors[] = "Falta carpeta temporal";
            break;
        case 7:
            $errors[] = "UPLOAD_ERR_CANT_WRITE";
            $errors[] = "No se ha podido escribir en el disco";
            break;

        default:
            $errors[] = 'Error indeterminado.';
    }
    return false;
} else{
    
    $fileName = $_FILES[$name]['name'];
	echo $fileName;
    
    $pathTemp = $_FILES[$name]['tmp_name'];
    $ext = $_FILES['image']['type'];
    
    if(! in_array($ext, $validExtensions)){
        $errors[] = "La extensión no es válida o no se ha subido ningún archivo";
        return false;
    }

    if((int)$_FILES[$name]['size'] > $size){
        $errors[] = "El tamaño fichero es demasiado grande";
        return false;
    }

    if( is_file($path . $name)) {
        $fileName = time() . $fileName;
    }

    if(move_uploaded_file($pathTemp, $path .time() .$fileName)){
       
        return $fileName;
    }else{
        $errors[] = "Error: No se pudo mover el fichero a su destino";
        return false;
    }

}
}

?>