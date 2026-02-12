<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("db_connection.php");
include_once("header.php");

$user_id = $_SESSION["id"];

$month = date('n');
$year  = date('Y');

if(isset($_REQUEST["btnview"]))
    {
        if(!empty($_REQUEST["month"]) && !empty($_REQUEST["year"]))
        {
        $month = $_REQUEST["month"];
        $year  = $_REQUEST["year"];
        }
        else
        {
        $month = date('n');
        $year  = date('Y');
        }
    }
$q = mysqli_query($cn,"select category,limit_amount,month,year from budget where user_id = '$user_id' and month = '$month' and year = '$year'");
?>

<?php include_once("navbar.php"); ?>

<h3>Budget Tracking (<?php echo date("F", mktime(0,0,0,$month)); echo " ".$year; ?>)</h3>
<br>

<a href="budget.php" class="btn btn-warning">Add Expense Category</a>

<form action="budget_track.php" method="post">
<br><br>
<select name="month">
    <option value="">Select month</option>
    <?php 
        for($m=1;$m<=12;$m++) 
        {
        $vl =date("F", mktime(0,0,0,$m));
        $sel = ($m==$month) ? "selected" : "";
        echo "<option value='$m' $sel>$vl</option>";
        }
     ?>
    </select>

    <select name="year">
        <option value="">Select year</option>
    <?php 
        for($y=date('Y');$y>=2025;$y--) 
        {
        $sel = ($y==$year) ? "selected" : "";
        echo "<option value='$y' $sel>$y</option>"; 
        }
        ?>
    </select>
    <button name="btnview" class="btn btn-primary">View</button><br><br>
</form>

<table class="table table-bordered">

<tr>
<th>Category</th>
<th>Budget</th>
<th>Spent</th>
<th>Status</th>
</tr>


<?php 
$labels = [];
$budgets = [];
$spents = [];
while($row=mysqli_fetch_array($q)) {

$cat = $row[0];
$budget = $row[1];


$sp = mysqli_query($cn,"
 SELECT SUM(amount) AS spent
 FROM expense
 WHERE user_id='$user_id'
 AND category='$cat'
 AND MONTH(expense_date)='$month'
 AND YEAR(expense_date)='$year'
");

$spent = mysqli_fetch_array($sp)[0] ?? 0;

$labels[] = $cat;
$budgets[] = $budget;
$spents[] = $spent;

if($budget > 0)
{
   $per = ($spent / $budget) * 100;
}
else
{
   $per = 0;
}
if($per>100) 
    $per=100;


if($per<60) 
    $cls="success";
elseif($per<90) 
    $cls="warning";
else 
    $cls="danger";
?>

<tr>

<td><?php echo $cat; ?></td>

<td>₹<?php echo $budget; ?></td>

<td>₹<?php echo $spent; ?></td>

<td>
<div class="progress">

 <div class="progress-bar progress-bar-<?php echo $cls; ?>" style="width:<?php echo $per; ?>%">

 <?php echo round($per); ?>%

 </div>

</div>
</td>

</tr>

<?php } ?>

</table>



<h4 class="mt-4">Budget vs Spent Chart</h4>

<canvas id="budgetChart" height="120"></canvas>

<script>

var labels = <?php echo json_encode($labels); ?>;
var budgets = <?php echo json_encode($budgets); ?>;
var spents = <?php echo json_encode($spents); ?>;

var ctx = document.getElementById('budgetChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',

    data: {
        labels: labels,

        datasets: [
        {
            label: 'Budget',
            data: budgets,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        },
        {
            label: 'Spent',
            data: spents,
            backgroundColor: 'rgba(255, 99, 132, 0.7)'
        }
        ]
    },

    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>


<?php include "footer.php"; ?>
