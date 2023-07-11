<?php include "parte_superior.php"; ?>

<style>
  

.dibujo-list {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    overflow: auto hidden;
}

.dibujo-list > div {
    width:320px;
    height:380px;
    flex-shrink: 0;
    flex-direction: column;
}

.dibujo-list > div > a {
  display: block;
    width:320px;
    height:320px;
}

.dibujo-list > div > a > img {
    width: 100%;
    height: 100%;
}
</style>

<div class="container">
  <form method="post" action="" enctype="multipart/form-data">

    <div class="form-group">

      <?php // si NO eres tu el de la sesion no puede salirte subir dibujo

      if ($id_dibujo == $_SESSION["id"]) {
        echo '
                <div class="row d-flex justify-content-center">
                <label style="font-size:30px;" class="col-sm-10 text-center control-label">¡Sube una imagen!</label>
                </div>
                <div class="row d-flex justify-content-center">
                <div class="col-sm-4">
                  <input type="file" class="form-control" id="image" name="image" multiple>
                </div>
                <div class="col-sm-2 ">
                  <button name="añadir" class="btn btn-outline-info btn-rounded" >Subir Dibujo</button> </div>
                  </div>
                <p style="color:red">' . $mensaje . '</p>
                ';
      } ?>
    </div>
  </form>
  
    <div class="dibujo-list">
      <?php foreach ($datos as $value) {
        echo '
        <div>  <a href="index.php?ctl=dibujo&id_dibujo=' .
          $value["id"] .
          "&receiver=" .
          $id_dibujo .
          '"> 
          <img  class="rounded shadow-sm"  src="data:image/png;base64,' .
          base64_encode($value["imagenes"]) .
          '"></a>';

        // si NO eres tu el de la sesion tampoco te tiene q salir borrar dibujo

        if ($id_dibujo == $_SESSION["id"]) {
          echo '
          <form  action="" method="post" data-abide class="d-flex justify-content-center">
          <input id="id" name="id" type="hidden" value="' .
            $value["id"] .
            '">
          <input type="submit" name="borrar" class="btn btn-outline-info btn-rounded" style="margin: 20px;" value="Borrar Dibujo">
 
          </form>';
        }
        echo "</div>";
      } ?>

  </div>
</div>

<?php include "parte_inferior.php"; ?>