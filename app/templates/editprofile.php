<?php include "parte_superior.php"; ?>

<link rel="stylesheet" href="css/edit_profile.css">

<script>
    function triggerClick() {
        document.querySelector('#fileImage').click();
    }

    function displayImage(e) {
        if (e.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('#picture').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }
    }
</script>

<body>

    <?php foreach ($matriz_usuarios as $value) : ?>


        <form enctype="multipart/form-data" action="" method="post" data-abide>
            <div class="container mt-3">
                <div class="card p-3 text-center">
                    <div class="d-flex flex-row justify-content-center mb-3">
                        <div class="image">
                            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" id="picture" name="picture" onclick="triggerClick()" src="<?php echo $value["foto"]; ?>" width="120"></div>
                            <input type="file" id="fileImage" name="fileImage" onchange="displayImage(this)" style="display:none" accept="image/*" #file>

                        </div>

                    </div>
                    <h2><?php echo $value["username"]; ?></h2>
                    <h4>Edit Profile</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="inputs"> <label>Name</label> <input class="form-control" type="text" placeholder="Name" name="name" value="<?php echo $value["nombre"]; ?>"> </div>
                        </div>
                        <div class="col-md-6">
                            <div class="inputs"> <label>Email</label> <input class="form-control" type="text" placeholder="Email" name="email" value="<?php echo $value["email"]; ?>"> </div>

                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="about-inputs"> <label>About</label>
                                <textarea name="about" class="form-control" type="text" placeholder="Tell us about yourself"><?php echo $value["about"]; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center mt-3 mb-3">
                        <a href="index.php?ctl=dardeBaja" class="d-block w-100">Eliminar Cuenta</a> <br>
                    </div>

                    <div> <button type="submit" name="update" class="px-3 btn btn-sm btn-outline-primary">Save</button> </div>
                </div>
            </div>

        </form>
    <?php endforeach; ?>
</body>

<?php include "parte_inferior.php"; ?>