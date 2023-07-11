
<?php include("parte_superior.php");?>
<style>
  form {
    width: 384px;
    margin: 0 auto;
  }
  h5{
    color:purple;
  }
</style>
<!--Section: Block Content-->
<section class="mt-5 text-center">

  <h5>CHANGE PASSWORD</h5>

  <form action="index.php?ctl=resetPassw" method="post">


    <div class="md-form md-outline">
	    <label data-error="wrong" data-success="right" >Old password</label>
      <input type="password" name="old" class="form-control">
     
    </div>

    <div class="md-form md-outline">
	<label data-error="wrong" data-success="right" for="newPassConfirm">New password</label>
      <input type="password" name="passw1"  class="form-control">
     
    </div>



	<div class="md-form md-outline">
	<label data-error="wrong" data-success="right" for="newPassConfirm">Confirm password</label>
      <input type="password" name="passw2"  class="form-control">
     
    </div>


    <button type="submit" name="change" class="btn btn-primary mb-4 mt-3">Change password</button>



	<?php
                    if(count($errors) > 0){
                        ?>
                        <div  style="background-color: orange;">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>








  </form>

 

</section>
<!--Section: Block Content-->


<?php include("parte_inferior.php");?>

