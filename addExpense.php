<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("header.php");
include_once("db_connection.php");
$user_id = $_SESSION["id"];
$catLst = mysqli_query($cn,"select name from expense_categories");
if(isset($_REQUEST["btnaddexpense"]))
    {
        $expenseCat = $_REQUEST["expenseCat"];
        $expenseamt = $_REQUEST["expenseamt"];
        $expenseDt = $_REQUEST["expenseDt"];
        $descp = $_REQUEST["descp"];

        if(!is_numeric($expenseamt))
        {
          $errmsg = "Amount must be number";
        }
        else
        {
          $expid = mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(id),500) +1 from expense"))[0];
          $sql = "insert into expense (id,user_id,amount,category,description,expense_date) values ($expid,$user_id,$expenseamt,'$expenseCat','$descp','$expenseDt')";
          $rslt = mysqli_query($cn,$sql);
          $tid = mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(tid),600) +1 from transactions"))[0];
          mysqli_query($cn,"insert into transactions (tid,user_id,ttype,category,amount,tdate,ref_id) values ($tid,$user_id,'Expense','$expenseCat',$expenseamt,'$expenseDt',$expid)");
          $successmsg = "Expense added";
        }
    }

if(isset($_REQUEST["btnupdtexp"]))
    {
        // echo "<script>alert('update button clicked')</script>";
        $idedt=$_REQUEST["idedt"];
        $edtcat=$_REQUEST["edtCat"];
        $edtamt=$_REQUEST["edtamt"];
        $edtdescp=$_REQUEST["edtdescp"];
        $edtexpdt=$_REQUEST["edtexpdt"];
        // echo $idedt;
        // echo $edtcat;
        // echo $edtamt;
        // echo $edtdescp;
        // echo $edtexpdt;
        if(!is_numeric($edtamt))
        {
          $errmsg = "Amount must be number";
        }
        else
        {
          mysqli_query($cn,"update expense set category='$edtcat' , amount='$edtamt' , expense_date='$edtexpdt', description='$edtdescp' where id='$idedt'");
          mysqli_query($cn,"update transactions set category='$edtcat' , amount='$edtamt' , tdate='$edtexpdt' where ref_id='$idedt'");
        }
        
    }

?>

<?php include_once("navbar.php");?>

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
<form action="addExpense.php" method="post">
    <br><h3>Add Expense</h3><br>
    <div class="form-group">
      <label for="expenseDt">Expense date <sup style="color:red">*<sup></label>
      <input type="date" class="form-control" id="expenseDt" name="expenseDt" required>
    </div>

    <div class="form-group">
      <label for="expenseCat">Categories <sup style="color:red">*<sup></label>
      <select class="form-control" name="expenseCat" id="expenseCat" required>
        <option value="">Select Category</option>
        <?php 
            while($arcat=mysqli_fetch_array($catLst))
                {
                    echo "<option value='$arcat[0]'>$arcat[0]</option>";
                }
        ?>
      </select>
    </div>

    <div class="form-group">
      <label for="expenseamt">Amount <sup style="color:red">*</sup></label>
      <input type="text" class="form-control" id="expenseamt" name="expenseamt" required>
    </div>

    <div class="form-group">
      <label for="descp">Description</label>
      <textarea class="form-control" id="descp" name="descp"></textarea>
    </div>
   
    <button type="submit" class="btn btn-warning" name="btnaddexpense">Add</button><br>
  </form><br><br><br><hr>

   <div class="container" style="margin-top:20px;" id="expensetable">
  <?php include('expense_table.php');?>
  </div>

  
<!-- Edit modal -->

<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editmodalLabel">Update Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="addExpense.php" method="post">

      <input type="hidden" id="idedt" name="idedt">
      <div class="form-group">
      <label for="edtCat">Categories <sup style="color:red">*<sup></label>
      <select class="form-control" name="edtCat" id="edtCat" required>
        <option value="">Select Category</option>
        <?php 
        $catLst = mysqli_query($cn,"select name from expense_categories");
            while($edarcat=mysqli_fetch_array($catLst))
                {
                    echo "<option value='$edarcat[0]'>$edarcat[0]</option>";
                }
        ?>
      </select>
      </div>

      <div class="mt-2 col-6">
      <label for="edtamt">Amount <sup style="color:red">*<sup></label>
      <input type="text" class="form-control" id="edtamt" name="edtamt" required>
      </div>

      <div class="mt-2">
      <label for="edtdescp">Description</label>
      <input type="text" class="form-control" id="edtdescp" name="edtdescp">
      </div>
    
    <div class="mt-2 col-6">
      <label for="edtexpdt">Expense Date <sup style="color:red">*<sup></label>
      <input type="date" class="form-control" id="edtexpdt" name="edtexpdt" required>
    </div>

  <br>
    <button type="submit" class="btn btn-success" name="btnupdtexp">Update</button>
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include_once("footer.php") ?>