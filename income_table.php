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
        <th>Income Date</th>
        <th>Source</th>
        <th>Amount</th>
        <th>Frequency</th>
        <th>Entry Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $incomes=mysqli_query($cn,"select income_date,source,amount,created_at,id,frequency from income where user_id = '$user_id'");
      while($ar=mysqli_fetch_array($incomes))
      {
        $inDt=$ar[0];
        $src=$ar[1];
        $amt=$ar[2];
        $entDt=$ar[3];
        $inid=$ar[4];
        $freq=$ar[5];

        echo "<tr>";
        echo "<td> $inDt </td>";
        echo "<td> $src </td>";
        echo "<td> $amt </td>";
        echo "<td> $freq </td>";
        echo "<td> $entDt </td>";
        echo "<td>
        <a href='#'><i data-fa-symbol='edit' class='edit fa-solid fa-pencil fa-fw' id='$inid'></i></a>
        
        <a href='#' onclick='return fun1(".$inid.");'><i data-fa-symbol='delete' class='fa-solid fa-trash fa-fw'></i> </a>
        </td>";
        echo "</tr>";

      }
      ?>
      
    </tbody>
  </table>
  </div>

  <?php include_once('footer.php');?>