<?php
// web/index.php

// carga del modelo y los controladores


require_once __DIR__ . '/../app/Config.php';
require_once __DIR__ . '/../app/Model.php';
require_once __DIR__ . '/../app/Controller.php';


session_start();
//inicializamos nivel 0 para usuarios no logueados, si no se ha logeado nadie no hay getpermiso() , luego la variable de sesion no se inicializa .
if (!isset($_SESSION['user_logeado'])) {
	$_SESSION['acceso']=0;	
}
// si estas logeado...


// if(isset($_SESSION['user_logeado'])){
// 	$_SESSION['acceso']=1;	

// 	if (!isset($_SESSION['momento'])) {
// 		$_SESSION['momento']=time();
// 	}									// el 1 minuto de inactividad cerrar sesion.
// 	else if (time() - $_SESSION['momento'] > 60) {
// 		session_destroy();
// 		/* Aquí redireccionas a la url especifica */
// 		header("Location: index.php?ctl=login");
// 		die();  
// 	}else{
// 		$_SESSION['momento']=time();
// 	}
	
// }



// enrutamiento
//añadimos el elemento acceso para controlar el nivel de usuario que tiene que tener para acceder
//nivel 0 para acceso a todos los usuarios incluso no logueados
$map = array(
	'login' => array('controller' =>'Controller', 'action' =>'logear', 'acceso' => 0),
	'animeView' => array('controller' =>'Controller', 'action' =>'listaAnime', 'acceso' => 0),
	'inicio' => array('controller' =>'Controller', 'action' =>'iniciar', 'acceso' => 0),
	'favoritos' => array('controller' =>'Controller', 'action' =>'MiLista', 'acceso' => 1),
	'mostrar' => array('controller' =>'Controller', 'action' =>'mostrarDibujos', 'acceso' => 0),
	'recogeAnime' => array('controller' =>'Controller', 'action' =>'recogeAnime', 'acceso' => 0),
	'eliminarAnime' => array('controller' =>'Controller', 'action' =>'eliminarAnime', 'acceso' => 0),
	'dardeBaja' => array('controller' =>'Controller', 'action' =>'EliminarCuenta', 'acceso' => 0),
	'forgottenPassw' => array('controller' =>'Controller', 'action' =>'ForgottenPassword', 'acceso' => 0),
	'resetPassw' => array('controller' =>'Controller', 'action' =>'resetPassw', 'acceso' => 0),
	'dibujar' => array('controller' =>'Controller', 'action' =>'dibujar', 'acceso' => 0),
	'anime' => array('controller' =>'Controller', 'action' =>'anime', 'acceso' => 0),
	'functions' => array('controller' =>'Controller', 'action' =>'funcionesRequest', 'acceso' => 0),
	'misdibujos' => array('controller' =>'Controller', 'action' =>'recogeImagen', 'acceso' => 1),
	'dibujo' => array('controller' =>'Controller', 'action' =>'dibujo', 'acceso' => 0),
	'profile' => array('controller' =>'Controller', 'action' =>'profile', 'acceso' => 0),
	'notifications' => array('controller' =>'Controller', 'action' =>'notificaciones', 'acceso' => 0),
	'editprofile' => array('controller' =>'Controller', 'action' =>'editProfile', 'acceso' => 1),
    'insertar' => array('controller' =>'Controller', 'action' =>'', 'acceso' => 2),
	'register' => array('controller' =>'Controller', 'action' =>'registrarse', 'acceso' => 0),
	'error' => array('controller' =>'Controller', 'action' =>'error', 'acceso' => 0),
	'log_off' => array('controller' =>'Controller', 'action' =>'cerrarSesion', 'acceso' => 1),
	'confirmacion' => array('controller' =>'Controller', 'action' =>'confirmacion', 'acceso' => 1),
	'character' => array('controller' =>'Controller', 'action' =>'character', 'acceso' => 0),
);

// Parseo de la ruta

if (isset($_GET['ctl'])) {
	if (isset($map[$_GET['ctl']])) {
		$ruta = $_GET['ctl'];
	} else {

			// si el ctl NO EXISTE-> página de error común que se mostrará con o sin menú dependiendo si el usuario ya se ha logueado o no.
			$m = "No existe la ruta ctl.";
			header("Location: index.php?ctl=error&mensaje=$m");

			//escribimos en log errores no valido
			error_log("Url no válida" . microtime() . PHP_EOL, 3, "log_errores_no_valido.txt");
			/*header('Status: 404 Not Found');
			echo '<html><body><h1>Error 404: No existe la ruta <i>' .
			$_GET['ctl'] .'</p></body></html>'; */
			exit;
			}
	} else {
		//Si no se ha seleccionado nada mostraremos pantalla de inicio
		// si el usuario esta logeado --> bienvenido
		// si el usuario no esta logeado --> login
		if(isset($_SESSION['user_logeado'])){
			$ruta = 'timeline';	
		}else{
			// por defecto la pagina principal
			$ruta = 'inicio';
		}
		
}
$controlador = $map[$ruta];
                    //instancia controlador, nombre controlador
if (method_exists($controlador['controller'], $controlador['action'])) {
		// la ruta si existe.
		//antes de llevar a la ruta tenemos que ver que un usuario de nivel 1 no pueda acceder a rutas de nivel 2.
		
		//echo "el nivel de acceso es  " . $controlador['acceso']. " "; //2 (insertar)
		//echo " el permiso  del usuario es " . $_SESSION['acceso'] . " "; //1 (normal)
		
		if($controlador['acceso']>$_SESSION['acceso']){
			$m = "No tienes permisos para ejecutar esta accion.";
			//escribimos en log  errores_acceso -> momento, usuario, pagina queria acceder.
			$usuario=$_SESSION['user_logeado'];
			$pagina_acceder=$ruta.".php";
			error_log("Usuario : $usuario No pudo acceder a la página $pagina_acceder" . microtime() . PHP_EOL, 3, "log_errores_no_valido.txt");
            

			header("Location: index.php?ctl=error&mensaje=$m");
		}else{
			// si tiene permisos si carga la template.
		call_user_func(array(new $controlador['controller'], $controlador['action']));
		}
	
   
} else {
		header('Status: 404 Not Found');
		echo '<html><body><h1>Error 404: El controlador <i>' .
		$controlador['controller'] .'->' .	$controlador['action'] .'</i> no existe</h1></body></html>';
}
?>
 
 
