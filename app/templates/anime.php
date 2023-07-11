<?php include "parte_superior.php"; ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/anime.css">

<div class="container mt-4">
  <h1><?php echo $title; ?></h1>
  <div class="rec-container">
    <div>
      <img src=<?php echo $imagen; ?> alt="">
      <?php
      $boolean = null;

      if (isset($_SESSION['user_logeado'])) {
        $favoritos = $m->getFavorites($_SESSION['id']);
        if ($favoritos != null) {
          foreach ($favoritos as $value) {
            if ($value["mal_id"] == $mal_id) {
              $boolean = true;
            }
          }
        }
      }

      echo "<div class='rec-botones'>";
      if (isset($_SESSION['user_logeado']) & $boolean) {
        echo "<a class='btn btn-outline-danger btn-rounded'   href='index.php?ctl=eliminarAnime&var1=$mal_id'>QUITAR LISTA</a>";
      } else {
        echo "<a class='btn btn-outline-info btn-rounded'  href='index.php?ctl=recogeAnime&var1=$mal_id&var2=$imagen'> AÑADIR LISTA</a>";
      }
      if ($trailer != null) {
        echo '
        <!-- Button HTML (to Trigger Modal) -->
        <a href="#myModal" class="" data-toggle="modal"><i id="icon_you" class="fa fa-youtube"></i></a>
        
        <!-- Modal HTML -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">YouTube Video</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="cartoonVideo" class="embed-responsive-item" width="560" height="315" src="' . $trailer . '" allowfullscreen></iframe>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        ';
      }
      echo '</div>';
      ?>
    </div>
    <div>

      <p><?php echo $synopsis; ?></p>
      <div class="anime__details__widget">
        <div class="row">
          <div class="col-lg-6 col-md-6">
            <ul>
              <li rel="tipo"><span>Tipo:</span><?php echo $type; ?></li>
              <li><span>Genero:</span> <?php echo $generos; ?> </li>
              <li><span>Studios:</span> <?php echo $studios; ?></li>
              <li><span>Idiomas:</span> Japonés</li>
              <li><span>Episodios:</span> <?php echo $episodes; ?></li>
              <li><span>Duracion:</span><?php echo $duration; ?></li>
              <li><span>Emitido:</span> <?php echo $emitido; ?> </li>
              <li><span>Estado:</span> <span class="enemision finished"><?php echo $status; ?></span></li>
            </ul>
            <?php
            if ($array_recomendations != null) {
              echo '  
              <div  class="mr-5">
                <h3>Recommendations</h3>
                <div class="rec-list">';
              foreach (array_slice($array_recomendations, 0, 10) as $value) {
                echo '<a href="index.php?ctl=anime&var1=' . $value['mal_id'] . '">
              <img height=80px; style="margin:5px;"   src=' . $value['images']['webp']['image_url'] . ' alt="">
              </a>';
              }
              echo '
            </div>
            </div>';
            }
            ?>
          </div>
          <div class="col-lg-6">
            <div class="anime__details__btn">
              <div>
                <h4>Main Characters</h4>
                <div class="rec-list">
                  <?php
                  foreach ($data_characters as $ch) {
                    if ($ch['role'] == 'Main') {
                      $img = $ch['character']['images']['webp']['image_url'];
                      echo '<a href="index.php?ctl=character&var1=' . $ch['character']['mal_id'] . '"><img height=200px; style="margin:5px;"  
                      class="rounded-circle" src=' . $img . ' alt=""></a>
                      ';
                    }
                  }
                  echo ' </div>';
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script>
    $(document).ready(function() {
      /* Get iframe src attribute value i.e. YouTube video url
      and store it in a variable */
      var url = $("#cartoonVideo").attr('src');

      /* Assign empty url value to the iframe src attribute when
      modal hide, which stop the video playing */
      $("#myModal").on('hide.bs.modal', function() {
        $("#cartoonVideo").attr('src', '');
      });

      /* Assign the initially stored url back to the iframe src
      attribute when modal is displayed again */
      $("#myModal").on('show.bs.modal', function() {
        $("#cartoonVideo").attr('src', url);
      });
    });
  </script>
  <?php include "parte_inferior.php"; ?>