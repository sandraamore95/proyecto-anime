<?php include("parte_superior.php"); ?>
<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>

    <div class="container d-flex justify-content-center">
        <div class="card mt-5 p-4">
            <form action="" method="post">

                <div class="input-group mb-3">

                    <input type="text" name="username" class="form-control">
                    <div class="input-group-append"><button type="submit" name="update" class="btn btn-primary"><i class="fas fa-search"></i></button></div>

                </div>
                <p style="color:red"><?php echo $message   ?></p>

            </form>


        </div>
    </div>






    <?php foreach ($datos_usuario as $value) : ?>


        <div class="row py-5 px-4">
            <div class="col-md-10 mx-auto">
                <!-- Profile widget -->
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="info-wrapper">
                        <div class="info-img">
                            <img src="<?php echo $value['foto'] ?>" alt="Avatar">
                        </div>
                        <div class="info-name">
                            <h4><?php echo $value['username'] ?></h4>
                            <p><?php echo $value['nombre'] ?> </p>
                        </div>
                        <div class="info-contact">
                            <p>Cumpleaños :</p>
                            <p><?php echo $value['fecha_nac'] ?></p>
                        </div>
                    </div>



                    <div class="bg-light p-4 d-flex justify-content-end text-center">
                        <ul class="list-inline mb-0">

                            <?php

                            if ($id_seleccionado != $_SESSION['id']) {
                                if ($is_already_friends) {
                                    echo '<a class="btn btn-outline-dark btn-sm"   href="index.php?ctl=functions&action=unfriend_req&id=' . $id_seleccionado . '" class="req_actionBtn unfriend">Unfriend</a>';
                                } elseif ($check_req_sender) {
                                    echo '<a class="btn btn-outline-dark btn-sm"  href="index.php?ctl=functions&action=cancel_req&id=' . $id_seleccionado . '" class="req_actionBtn cancleRequest">Cancel Request</a>';
                                } elseif ($check_req_receiver) {
                                    echo '<a  class="btn btn-outline-dark btn-sm"  href="index.php?ctl=functions&action=ignore_req&id=' . $id_seleccionado . '" class="req_actionBtn ignoreRequest">Ignore</a>&nbsp;
                                    <a href="index.php?ctl=functions&action=accept_req&id=' . $id_seleccionado . '" class="req_actionBtn acceptRequest">Accept</a>';
                                } else {
                                    echo '<a class="btn btn-outline-dark btn-sm"   href="index.php?ctl=functions&action=send_req&id=' . $id_seleccionado . '" class="req_actionBtn sendRequest">Send Request</a>';
                                }
                            }

                            ?>

                        </ul>
                    </div>
                    <div class="px-4 py-3">
                        <h5 class="mb-0">About</h5>
                        <div class="p-4 rounded shadow-sm bg-light">
                            <p class="font-italic mb-0"><?php echo $value['about'] ?></p>

                        </div>
                    </div>



                    <?php if (($is_already_friends) or ($id_seleccionado == $_SESSION['id'])) {
                        echo '
                        
                        <div class="px-4 py-3">
                        <h5 class="mb-0">Friends</h5>
                        <div class="row">';


                        foreach ($get_all_friends as $row) {
                            echo '
                         <div>
                        <div class="m-2">
                    
                        <div class="thumb-lg member-thumb mx-auto"><a href="index.php?ctl=profile&id=' . $row->id . '"><img style="width:80px;height:80px" src="' . $row->foto . '" class="rounded-circle img-thumbnail" alt="profile-image"></a></div>
                    
                        </div>
                            </div>';
                        }
                        echo '
                        </div>

                    </div>
                        <div>
                        <div class="py-4 px-4">
                       
                            <div class="row">';

                        foreach (array_slice($favoritos, 0, 4) as $value) {

                            echo ' <div>
                            <a href="index.php?ctl=anime&var1=' . $value['mal_id'] . '"> <img class="img-fluid img-center"   style="width:120px;padding:10px;height:175px"  src="' . $value['imagen'] . '" ></a>
                           </div>
                           ';
                        }
                        echo '</div>';
                        if ($favoritos != null) {
                            echo '  <a class="btn btn-outline-info btn-rounded" href="index.php?ctl=favoritos&id=' . $id_seleccionado . '">SHOW MORE</a>';
                        } else if ($id_seleccionado == $_SESSION['id']) { {
                                echo '  <a class="btn btn-outline-info btn-rounded" href="index.php?ctl=favoritos&id=' . $id_seleccionado . '">AÑADIR ANIME</a>';
                            }
                        }

                        echo '
                            </div>

                            <div class="py-4 px-4">
                           
                            <div class="dibujo-list">
                            
                            ';

                        foreach ($datos as $value) {

                            echo ' 

                            <div>
                                <img class="rounded shadow-sm" id="myImg"    onclick="clickDibujo(this)"  src="data:image/png;base64,' . base64_encode($value['imagenes']) . '">
                            </div>
                            ';
                        }
                    }
                    ?>
                </div>

                <div id="myModal" class="modal-wrapper">
                    <div class="backdrop"></div>
                    <img class="modal-content" id="img01">
                </div>
                <?php
                if ($datos != null && $is_already_friends) {
                    echo ' <a class="btn btn-outline-info btn-rounded" href="index.php?ctl=misdibujos&id=' . $id_seleccionado . '">SHOW MORE</a>';
                } else if ($id_seleccionado == $_SESSION['id']) {
                    echo ' <a class="btn btn-outline-info btn-rounded" href="index.php?ctl=misdibujos&id=' . $id_seleccionado . '">SUBIR DIBUJO</a>';
                }
                ?>

            </div>
        </div>
        </div>
        </div>

    <?php endforeach; ?>

    <script>
        function clickDibujo(item) {
            const modal = document.getElementById("myModal");
            const modalImg = document.getElementById("img01");
            const captionText = document.getElementById("caption");
            const backdrop = document.querySelector(".backdrop");
            modal.style.display = "block";
            modalImg.src = item.src;
            backdrop.addEventListener("click", () => {
                modal.style.display = "none";
            });
        }
    </script>


</body>

</html>


<?php include("parte_inferior.php"); ?>