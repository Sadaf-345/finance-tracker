<?php 
error_reporting(0);
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include_once("header.php");
include_once("db_connection.php");

if(isset($_REQUEST["btnlogin"]))
  {
    // echo "login button clicked";
    $email = $_REQUEST["email"];
    $pass = $_REQUEST["pass"];
    $sql = "select * from users where email='$email'";
    $rst = mysqli_query($cn,$sql);
    if(mysqli_num_rows($rst)>0)
      {
        session_start();
        $userdata = mysqli_fetch_array($rst);
        if(password_verify($pass,$userdata[3]))
          {
            $_SESSION["id"] = $userdata[0];
            $_SESSION["name"] = $userdata[1];

            header("location:dashboard.php");
            exit();
          }
        else
          {
            $errmsg = "Invalid password";
          }

      }
    else
      {
        $errmsg = "Sorry user not found...kindly check info!";
      }
  }

?>

<div class="auth-container">
    <div class="auth-card">

        <div class="user-icon">
            <span class="glyphicon glyphicon-user"></span>
        </div>

        <h3>LOGIN</h3>
<form action="login.php" method="post">
  <?php
    if(isset($errmsg))
      {
        echo "<div class='alert alert-danger'>$errmsg</div>";
      }
  ?>
    <div class="form-group">
      <label for="email">Email <span style="color:red">*<span></label>
      <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="form-group">
      <label for="pass">Password <span style="color:red">*</span></label>
      <input type="password" class="form-control" id="pass" name="pass">
    </div>
    <button type="submit" class="auth-btn" name="btnlogin">Login</button><br>
    <p style="margin-top:10px;">
    Don't have an account?<a href="register.php">Create an account</a>
    </p>
  </form>
  </div>
</div>

<?php include_once("footer.php") ?>