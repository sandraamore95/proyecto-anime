<?php
include "parte_superior.php"; ?>
<link rel="stylesheet" href="css/style.css">
<div class="container mt-4 mr-4">
    <div class="row bootstrap snippets bootdeys">
        <div class="col-md-8 col-sm-12">
            <div class="comment-wrapper">
                <div class="panel panel-info">
                    <?php echo '<img width="500" class="img-fluid rounded shadow-sm" src="data:image/png;base64,' .
                        base64_encode($dibujo) .
                        '">'; ?>
                    <div class="panel-heading">
                        Comment panel
                    </div>
                    <div class="panel-body">
                        <form action="" method="post">
                            <textarea class="form-control" name=" comentario" placeholder="write a comment..." rows="3"></textarea>
                            <br>

                            <input type="submit" name="publicar" class="btn btn-info pull-right" value="Post">
                            <div class="clearfix"></div>
                        </form>
                        <hr>


                        <div class="dibujo-coments">


                            <?php if ($matriz_comentarios != null) {
                                foreach ($matriz_comentarios as $row) {
                                    $id_usuario = $row["id_usuario"];
                                    $matriz_usuarios = $m->getUsuarioDatos($id_usuario);
                                    foreach ($matriz_usuarios as $value) {
                                        $foto = $value["foto"];
                                        echo '
                                <div class="row">
                                <div class="col-12">
                                    <div class="card card-white post">
                                        <div class="post-heading">
                                            <div class="float-left image">
                                            <img class="img-circle avatar" src="' . $foto . '" width="60">
                                            </div>
                                            <div class="float-left meta">
                                                <div class="title h5">
                                                    <a href="index.php?ctl=profile&id=' . $value["id"] . '">
                                                    <b>' . $value['username'] . '</b></a>
                                                    made a post.
                                                </div>
                                                <h6 class="text-muted time">1 minute ago</h6>
                                            </div>
                                        </div> 
                                        <div class="post-description"> 
                                            <p>' . $row['texto'] . '</p>
                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                  ';
                                    }
                                }
                            } ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "parte_inferior.php"; ?>