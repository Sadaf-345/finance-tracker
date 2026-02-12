<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("header.php");
include_once("db_connection.php");
// echo "Add income page";
$user_id = $_SESSION["id"];
$srcLst = mysqli_query($cn,"select name from income_sources where user_id = $user_id");

if(isset($_REQUEST["btnaddincome"]))
    {
        $source = trim($_REQUEST["source"]);
        $flg=0;
        $amt = $_REQUEST["amt"];
        $incomeDt = $_REQUEST["incomeDt"];
        $freq = $_REQUEST["freq"];

        if(!preg_match("/^[a-zA-Z ]+$/", $source))
        {
            $errmsg = "Invalid Source";
        }
        else if(!is_numeric($amt))
        {
            $errmsg = "Amount must be number";
        }
        else
        {
            while($arsrc=mysqli_fetch_array($srcLst))
            {
                if($arsrc[0]==$source)  //for checking if source already exists
                    {
                        $flg=1;
                        break;
                    }
            }
            if($flg==0)
            {
                // echo "inserted";
                $srcid=mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(id),200) +1 from income_sources"))[0];
                mysqli_query($cn,"insert into income_sources (id,name,user_id) values ($srcid,'$source',$user_id)");
            }
            $inid = mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(id),300) +1 from income"))[0];
            $sql = "insert into income (id,user_id,amount,source,income_date,frequency) values ($inid,$user_id,$amt,'$source','$incomeDt','$freq')";
            $rslt = mysqli_query($cn,$sql);
            $tid = mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(tid),600) +1 from transactions"))[0];
            mysqli_query($cn,"insert into transactions (tid,user_id,ttype,category,amount,tdate,ref_id) values ($tid,$user_id,'Income','$source',$amt,'$incomeDt',$inid)");
            $successmsg = "Income added";
        }   
    }

if(isset($_REQUEST["btnupdt"]))
    {
        // echo "<script>alert('update button clicked')</script>";
        $idedt=$_REQUEST["idedt"];
        $srcedt=$_REQUEST["edtsource"];
        $edtamt=$_REQUEST["edtamt"];
        $edtindt=$_REQUEST["edtindt"];
        $edtfreq=$_REQUEST["edtfreq"];
        // echo $idedt;
        // echo $srcedt;
        // echo $edtamt;
        // echo $edtindt;
        if(!preg_match("/^[a-zA-Z ]+$/", $srcedt))
        {
          $errmsg = "Invalid Source";
        }
        else if(!is_numeric($edtamt))
        {
          $errmsg = "Amount must be number";
        }
        else
        {
          mysqli_query($cn,"update income set source='$srcedt' , amount='$edtamt' , income_date='$edtindt', frequency='$edtfreq' where id='$idedt'");
          mysqli_query($cn,"update transactions set category='$srcedt' , amount='$edtamt' , tdate='$edtindt' where ref_id='$idedt'");
        }
    }


?>
<?php include_once("navbar.php"); ?>

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

<form action="addIncome.php" method="post">
  <br><h3>Add Income</h3><br>
    <div class="form-group">
      <label for="incomeDt">Received date <sup style="color:red">*<sup></label>
      <input type="date" class="form-control" id="incomeDt" name="incomeDt" required>
    </div>
    <div class="form-group">
      <label for="source">Source <sup style="color:red">*<sup></label>

      <input list="sources" class="form-control" id="source" name="source" placeholder="Select or type" required>

        <datalist id="sources">
            <?php 
            while($arsrc=mysqli_fetch_array($srcLst))
                {
                    echo "<option value='$arsrc[0]'>";
                }
            ?>
        </datalist>
    </div>

    <div class="form-group">
      <label for="freq">Frequency <sup style="color:red">*<sup></label>
      <select class="form-control" name="freq" id="freq" required>
        <option value="">Select Frequency</option>
        <option value="Yearly">Yearly</option>
        <option value="Monthly">Monthly</option>
        <option value="Weekly">Weekly</option>
        <option value="One-time">One-time</option>
    </select>
    </div>

    <div class="form-group">
      <label for="amt">Amount <sup style="color:red">*</sup></label>
      <input type="text" class="form-control" id="amt" name="amt" required>
    </div>
   
    <button type="submit" class="btn btn-success" name="btnaddincome">Add</button><br>
  </form><br><br><br><hr>

  <div class="container" style="margin-top:20px;" id="incometable">
  <?php include('income_table.php');?>
  </div>




  <!-- Edit modal -->

<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editmodalLabel">Update Income</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="addIncome.php" method="post">

      <input type="hidden" id="idedt" name="idedt">

      <div class="mt-2">
      <label for="edtsource">Source <sup style="color:red">*<sup></label>
      <input type="text" class="form-control" id="edtsource" name="edtsource" required>
    </div>
    
    <!-- <div class="row"> -->
    <div class="mt-2 col-6">
      <label for="edtamt">Amount <sup style="color:red">*<sup></label>
      <input type="text" class="form-control" id="edtamt" name="edtamt" required>
    </div>
    <div class="form-group">
      <label for="edtfreq">Frequency <sup style="color:red">*<sup></label>
      <select class="form-control" name="edtfreq" id="edtfreq" required>
        <option value="">Select Frequency</option>
        <option value="Yearly">Yearly</option>
        <option value="Monthly">Monthly</option>
        <option value="Weekly">Weekly</option>
        <option value="One-time">One-time</option>
    </select>
    </div>
    <div class="mt-2 col-6">
      <label for="edtindt">Income Date <sup style="color:red">*<sup></label>
      <input type="date" class="form-control" id="edtindt" name="edtindt" required>
    </div>
  <!-- </div> -->
  <br>
    <button type="submit" class="btn btn-success" name="btnupdt">Update</button>
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include_once("footer.php"); ?>