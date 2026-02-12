<?php 
error_reporting(0);
include_once("header.php");
include_once("db_connection.php");

if(isset($_REQUEST["btnregister"]))
    {
        // echo "<script>alert('register button clicked')</script>";

        $name = $_REQUEST["name"];
        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];
        $cpass = $_REQUEST["cpass"];
        $password = password_hash($pass,PASSWORD_DEFAULT);  //ecrypted password

        // echo "<script>alert('$password')</script>";
        // echo "<script>alert('$email')</script>";
        try
        {
          $id=mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(id),100) +1 from users"))[0];
          $sql = "insert into users (id,name,email,pass) values ($id,'$name','$email','$password')";
          mysqli_query($cn,$sql);
          $name = "";
          $email = "";
          $pass = "";
          $cpass = "";
          $successmsg = "Registration done";
        }
        catch(mysqli_sql_exception $e)
        {
          if($e->getCode() == 1062) 
            {
              $errmsg = "Email already registered";
            }
        }

    }
?>

<div class="auth-container">
    <div class="auth-card">

        <div class="user-icon">
            <span class="glyphicon glyphicon-user"></span>
        </div>

        <h3>REGISTER</h3>
<form action="register.php" method="post" onsubmit="return validateFrm();">
  <?php
    if(isset($errmsg))
      {
        
        echo "<div class='alert alert-danger alert-dismissible fade in'>
        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
        $errmsg
        </div>";
      }
    if(isset($successmsg))
      {
        echo "<div class='alert alert-success alert-dismissible fade in'>
        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
        $successmsg
        </div>";
      }
  ?>
    <div class="form-group">
      <label for="name">Name <span style="color:red">*<span></label>
      <input type="text" class="form-control" id="name" name="name" value="<?php echo $name?>">
    </div>
    <div class="form-group">
      <label for="email">Email <span style="color:red">*<span></label>
      <input type="email" class="form-control" id="email" name="email" value="<?php echo $email?>">
    </div>
    <div class="form-group">
      <label for="pass">Password <span style="color:red">*</span></label>
      <input type="password" class="form-control" id="pass" name="pass" value="<?php echo $pass?>">
    </div>
    <div class="form-group">
      <label for="cpass">Confirm Password <span style="color:red">*</span></label>
      <input type="password" class="form-control" id="cpass" name="cpass" value="<?php echo $cpass?>">
    </div>
   
    <button type="submit" class="auth-btn" name="btnregister">Create Account</button><br>
     <p style="margin-top:10px;">
    Already registered?<a href="login.php">Sign in</a>
    </p>
  </form>
  </div>
</div>
<?php include_once("footer.php") ?>