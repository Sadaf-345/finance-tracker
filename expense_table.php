<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("db_connection.php");
include_once("header.php");
$user_id = $_SESSION["id"];
?>

<div class="table-responsive">
  <table class="table  table-hover table-bordered" id="mytable">
    <thead style='position: sticky;top: 0' class="table table-hover table-bordered table-striped">
      <tr>
        <th>Expense Date</th>
        <th>Category</th>
        <th>Amount</th>
        <th>Description</th>
        <th>Entry Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $expenses=mysqli_query($cn,"select expense_date,category,amount,description,created_at,id from expense where user_id = '$user_id'");
      while($ar=mysqli_fetch_array($expenses))
      {
        $expDt=$ar[0];
        $expCat=$ar[1];
        $expamt=$ar[2];
        $descp=$ar[3];
        $entDt=$ar[4];
        $expid=$ar[5];
    

        echo "<tr>";
        echo "<td> $expDt </td>";
        echo "<td> $expCat </td>";
        echo "<td> $expamt </td>";
        echo "<td> $descp </td>";
        echo "<td> $entDt </td>";
        echo "<td>
        <a href='#'><i data-fa-symbol='edit' class='edit fa-solid fa-pencil fa-fw' id='$expid'></i></a>
        
        <a href='#' onclick='return fun2(".$expid.");'><i data-fa-symbol='delete' class='fa-solid fa-trash fa-fw'></i> </a>
        </td>";
        echo "</tr>";

      }
      ?>
      
    </tbody>
  </table>
  </div>

  <?php include_once('footer.php');?>