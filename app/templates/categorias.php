<?php include "parte_superior.php"; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    #container-cards {
        justify-content: center;
    }

    #search {
        justify-content: center;
    }

    img {
        border-radius: 10px;
    }
    a:hover{
        text-decoration: none;
        color:black;
    }

    .active {
        color: black;
        font-size: 16px;
        text-transform: none;
    }
</style>

<body>
    <script>
        function setActive() {
            aObj = document.getElementById('nav').getElementsByTagName('a');
            for (i = 0; i < aObj.length; i++) {
                if (document.location.href.indexOf(aObj[i].href) >= 0) {
                    aObj[i].className = 'active';
                }
            }
        }
        window.onload = setActive;
    </script>

    <!--NAVBAR WITH SEACCH -->
    <div class="m-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" id='nav'>
            <div class="container-fluid">
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav">

                        <a href="index.php?ctl=animeView&var=Movie" class="nav-item nav-link">PELICULAS</a>
                        <a href="index.php?ctl=animeView&var=TV" class="nav-item nav-link">SERIES</a>
                        <a href="index.php?ctl=animeView&var=OVA" class="nav-item nav-link">OVAS</a>
                        <a href="index.php?ctl=animeView&var=Special" class="nav-item nav-link">SPECIALS</a>

                    </div>
                    <form class="d-flex" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" name="keywords">
                            <button type="submit" name="search" class="btn btn-secondary"><i class="bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-4" id="container-cards">
        <div class="row" id="anime">
            <?php
            $array = [];
            for ($i = 0; $i < count($animes); $i++) {
                $id = $animes[$i]["mal_id"];
                $title = $animes[$i]["title"];
                $img = $animes[$i]["images"]['jpg']['image_url'];
               



               
                echo '
                    <div class="col-lg-4 text-center card-box">
                    <div class="">
                        <div> <a href="index.php?ctl=anime&var1=' . $id . '"><img name="foto" src="' . $img . '" ></a></div>
                        <p>' . $title . '</p>
                    </div>
                    </div>';
                  

                if (isset($_POST["search"])) {
                    $valor = $_POST["keywords"];
                    if ($valor != "") {
                        if (strstr(strtolower($title), strtolower($valor))) {
                            array_push($array, strtolower($title));
                        }
                    }
                }
            }
            ?>
        </div>
    </div>

  
</body>
<script>
    var passedArray = <?php echo json_encode($array); ?>;
    var div_principal = document.getElementById('anime');
    var child = div_principal.children;

    for (var i = 0; i < div_principal.children.length; i++) {
        child[i].style.display = 'block';
    }

    for (var i = 0; i < div_principal.children.length; i++) {

        if (passedArray.length > 0 && !passedArray.includes(child[i].innerText.toLowerCase())) {
            child[i].style.display = 'none';
        }
    }
</script>

<?php include "parte_inferior.php"; ?>