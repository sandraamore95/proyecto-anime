<?php include "parte_superior.php"; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>APP de Anime</title>
    <link rel="stylesheet" href="css/inicio.css">
</head>

<body>



	
    <!--CARRUSEL   -->
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item h-100 active">
                <a href="index.php?ctl=anime&var1=36896" class="d-block h-100">
                    <img class="carousel-image-cover" src="images/carrusel1.jpg" alt="First slide">
                </a>
            </div>
            <div class="carousel-item h-100">
                <a href="index.php?ctl=anime&var1=1535" class="d-block h-100">
                    <img class="carousel-image-cover" src="images/carrusel2.jpg" alt="Second slide">
                </a>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>


    <!--CATEGORIAS -->
    <div class="container mt-4">
        <h2>Recommended ANIMES</h2>
        <div class="row inicio-list">
            <?php
            foreach ($array_random as  $value) {

                echo '
            <div class="col">
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                        <img src="' . $value['images']['webp']['image_url'] . ' " alt="">
                        </div>
                        <div class="flip-card-back">
                        
                        <h3> ' . $value['title'] . ' </h3>
                        <p>Episodes :  ' . $value['episodes'] . ' </p>
                        <p> Score : ' . $value['score'] . ' </p>
                      
                            <a class="btn btn-outline-info btn-rounded" href="index.php?ctl=anime&var1=' . $value['mal_id'] . '">GO</a>
                        </div>
                    </div>
                </div>
            </div>
            ';
            }
            ?>
        </div>
        <a class='btn btn-outline-info btn-rounded' href="index.php?ctl=animeView&var=Movie">SEARCH MORE</a>
    </div>
</body>

</html>
<?php include "parte_inferior.php"; ?>