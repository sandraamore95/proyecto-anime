<?php include("parte_superior.php"); ?>
<link rel="stylesheet" href="css/register.css">

<body>

  <div class="container-fluid ps-md-0" style="background-color: purple;">
    <div class="row g-0">
      <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
      <div class="col-md-8 col-lg-6">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-8 mx-auto">
                <h3 class="login-heading mb-4">Sign Up</h3>


                <div class="mb-3">
                  <?php
                  if (count($errors) > 0) {
                  ?>
                    <div style="color: hsl(0deg 85% 75%);">
                      <?php
                      foreach ($errors as $showerror) {
                        echo $showerror;
                      }
                      ?>
                    </div>
                  <?php
                  }
                  ?>

                </div>





                <!-- Sign In Form -->
                <form action="index.php?ctl=register" enctype="multipart/form-data" method="post">

                  <div class="form-group">

                    <div class="row">
                      <div class="col"><input type="text" class="form-control" name="first_name" placeholder="First Name" required="required"></div>
                      <div class="col"><input type="text" class="form-control" name="last_name" placeholder="Last Name" required="required"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="username" required="required">
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required="required">
                  </div>
                  <div class="form-group">
                    <label for="birthday">Birthday:</label>
                    <input type="date" max='<?php  echo date('Y-m-d');   ?>' id="birthday" name="birthday">


                  </div>





                  <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required="required">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required="required">
                  </div>
                  <div class="form-group">
                    <p>Subir foto perfil <input type="file" id="avatar" name="avatar" accept="image/*" #file> </p>

                  </div>





                  <div class="form-group">
                    <button type="submit" name="registrar" class="btn btn-lg  btn-login text-uppercase fw-bold mb-2">Sign Up</button>
                  </div>













                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>






</body>



<?php include("parte_inferior.php"); ?>