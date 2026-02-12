<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("header.php");
include_once("db_connection.php");
$user_id = $_SESSION["id"];
$q = mysqli_query($cn,"select tdate,ttype,category,amount from transactions order by tdate desc");

?>
<?php include_once("navbar.php");?>


<div class="container" style="margin-top:20px;">

<h3>Transaction History</h3><br><br>



<br>

<table class="table table-bordered table-hover">
<thead>
<tr>
<th>Date</th>
<th>Type</th>
<th>Category</th>
<th>Amount</th>
</tr> 
</thead>
<tbody>
<?php while($row=mysqli_fetch_array($q)) { ?>

<tr>
<td><?php echo $row[0]; ?></td>

<td><?php echo $row[1]; ?></td>

<td><?php echo $row[2]; ?></td>
<td>
<?php
if($row[1]=='Income')
 echo "<span class='text-success'>+ ₹$row[3]</span>";
else
 echo "<span class='text-danger'>- ₹$row[3]</span>";
?>
</tr>
<?php } ?>
</tbody>
</table>

</div>



<?php include_once("footer.php"); ?>