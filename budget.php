<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("header.php");
include_once("db_connection.php");
$user_id = $_SESSION["id"];
$catLst = mysqli_query($cn,"select name from expense_categories");
if(isset($_REQUEST["addexpenseCat"]))
    {
        $Ctg = $_REQUEST["Ctg"];
        $limitamt = $_REQUEST["limitamt"];
        $month = $_REQUEST["month"];
        $year = $_REQUEST["year"];
        $flg=0;
        // echo "<script>alert('button clicked')</script>";
        if(!preg_match("/^[a-zA-Z ]+$/", $Ctg))
        {
            $errmsg = "Invalid Category";
        }
        else if(!is_numeric($limitamt))
        {
            $errmsg = "Amount must be number";
        }
        else
        {
            while($arsrc=mysqli_fetch_array($catLst))
            {
                if($arsrc[0]==$Ctg)
                    {
                        $flg=1;
                        break;
                    }
            }
            if($flg==0)
            {
                    // echo "inserted";
                    $catid=mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(id),200) +1 from expense_categories"))[0];
                    mysqli_query($cn,"insert into expense_categories (id,name,user_id) values ($catid,'$Ctg',$user_id)");
            }
            $bgtid = mysqli_fetch_array(mysqli_query($cn,"select ifnull(max(id),700) +1 from budget"))[0];
            mysqli_query($cn,"insert into budget (id,user_id,category,limit_amount,month,year) values ($bgtid,$user_id,'$Ctg',$limitamt,$month,$year)");
            $successmsg = "Expense Category Added";
        }
    }

?>
<?php include_once("navbar.php") ?>

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

<form action="budget.php" method="post">
    <br><h3>Set Monthly Budget</h3><br>
    <div class="form-group">
      <label for="Ctg">Category <sup style="color:red">*</sup></label>

      <input list="ctgs" class="form-control" id="Ctg" name="Ctg" placeholder="Select or type" required>

        <datalist id="ctgs">
            <?php 
            while($arsrc=mysqli_fetch_array($catLst))
                {
                    echo "<option value='$arsrc[0]'>";
                }
            ?>
        </datalist>
    </div>
    
    <div class="form-group">
      <label for="limitamt">Limit Amount <sup style="color:red">*</sup></label>
      <input type="text" class="form-control" id="limitamt" name="limitamt" required>
    </div>

    <select name="month" required>
    <?php 
        
        echo "<option value=''>Select Month</option>";
        for($m=1;$m<=12;$m++) 
        {
            $vl =date("F", mktime(0,0,0,$m));
        echo "<option value='$m'>$vl</option>";
        }
     ?>
    </select>

    <select name="year" required>
    <?php 
        echo "<option value=''>Select Year</option>";
        for($y=date('Y');$y>=2025;$y--) 
        echo "<option>$y</option>"; ?>
    </select>

    <button type="submit" class="btn btn-primary" name="addexpenseCat">Add</button>
    <a href="budget_track.php" class="btn btn-warning">View Budgets</a>
    </form><br><br><br><hr>





<?php include_once("footer.php") ?>