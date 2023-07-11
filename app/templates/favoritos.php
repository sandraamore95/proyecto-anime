<?php include "parte_superior.php"; ?>

<link rel="stylesheet" href="css/favoritos.css">
<!--INICIO del cont principal-->
<div class="container">


  <h1 class="textoVerde text-center">Animes Favoritos</h1>
  <div class="row mt-5 ">


    <?php if ($favoritos != null) {
        foreach ($favoritos as $value) {
            $mal_id = $value['mal_id'];
            echo '
            <div class="col-lg-4 text-center card-box mt-4">
            <a href="index.php?ctl=anime&var1='.$mal_id.'"> 
            <img src="' .$value['imagen'] .'" alt="Avatar"> 
            </a></div>
            ';   
        }
    } else {
        echo '<h2>Vaya! No se han encontrado favoritos.  </h2>';

        if ($id_favoritos == $_SESSION['id']) {
            echo '<h2><a href="index.php?ctl=inicio">Empieza a Descubrir </a></h2>';
        }
    } ?>
    </table>
  </div>
</div>

<!--FIN del cont principal-->
<?php include "parte_inferior.php"; ?>
