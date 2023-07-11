<?php include "parte_superior.php"; ?>
<link rel="stylesheet" href="css/login.css">
<body>
<div class="container-fluid ps-md-0"  style="background-color: purple;">
  <div class="row g-0">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <img src="images/logo.jpg" width="200px" alt="">
              <h3 class="login-heading mb-4 mt-2 text-lighter">Sign In</h3>

              <!-- Sign In Form -->
              <form action="index.php?ctl=login" method="post">
                <div class="form-floating mb-3 text-lighter">
                <label for="floatingInput">UserName</label>
                  <input type="text" class="form-control" id="floatingInput" name="username" placeholder="enter username">
                 
                </div>
                <div class="form-floating mb-3 text-lighter">
                <label for="floatingPassword">Password</label>
                  <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                </div>

                <div class="mb-3">
                <?php if (count($errors) > 0) { ?>
                        <div  style="color: hsl(0deg 85% 75%);">
                            <?php foreach ($errors as $showerror) {
                                echo $showerror;
                            } ?>
                        </div>
                        <?php } ?>
                </div>

                <div class="d-grid">
                  <button name="logy" class="btn btn-lg  btn-login text-uppercase fw-bold mb-2" type="submit">Sign In</button>
                  <div class="text-center">
                  <a href="index.php?ctl=forgottenPassw">Forgot Password?</a>
                  </div>
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
<?php include "parte_inferior.php"; ?>

