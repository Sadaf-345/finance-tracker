<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("header.php");
include_once("db_connection.php");
$user_id = $_SESSION["id"];
$total_income = mysqli_fetch_array(mysqli_query($cn,"select sum(amount) from income where user_id=$user_id"))[0];
$total_expense = mysqli_fetch_array(mysqli_query($cn,"select sum(amount) from expense where user_id=$user_id"))[0];
$balance = $total_income - $total_expense;

if(isset($_POST["btnreport"]))
{
    $month = $_POST['month'] ?? "";
    $year  = $_POST['year'] ?? "";

    
    if(!empty($month) && !empty($year))
    {

        
        $income_q = mysqli_query($cn,"
            SELECT SUM(amount) 
            FROM income
            WHERE user_id='$user_id'
            AND MONTH(income_date)='$month'
            AND YEAR(income_date)='$year'
        ");

        $total_income = mysqli_fetch_array($income_q)[0] ?? 0;


        
        $expense_q = mysqli_query($cn,"
            SELECT SUM(amount) 
            FROM expense
            WHERE user_id='$user_id'
            AND MONTH(expense_date)='$month'
            AND YEAR(expense_date)='$year'
        ");

        $total_expense = mysqli_fetch_array($expense_q)[0] ?? 0;

        $balance = $total_income - $total_expense;
    }

    
}

?>

<?php include_once("navbar.php");?>

<div class="row">

    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">Total Income</div>
            <div class="panel-body text-center">
                <h3>₹ <?php echo number_format($total_income,2); ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-danger">
            <div class="panel-heading">Total Expense</div>
            <div class="panel-body text-center">
                <h3>₹ <?php echo number_format($total_expense,2); ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">Balance</div>
            <div class="panel-body text-center">
                <h3>₹ <?php echo number_format($balance,2); ?></h3>
            </div>
        </div>
    </div>

</div>

<!-- Filter Form -->
<form action="dashboard.php" method="post" class="form-inline">

    <select name="month" class="form-control">
        <option value="">Select Month</option>
        <?php
        for($m=1;$m<=12;$m++)
        {
            $sel = ($m==$month) ? "selected" : "";
            echo "<option value='$m' $sel>".date("F", mktime(0,0,0,$m))."</option>";
        }
        ?>
    </select>

    <select name="year" class="form-control">
        <option value="">Select Year</option>
        <?php
        for($y=date('Y');$y>=2025;$y--)
        {
            $sel = ($y==$year) ? "selected" : "";
            echo "<option value='$y' $sel>$y</option>";
        }
        ?>
    </select>

    <button type="submit" class="btn btn-primary" name="btnreport">View Report</button>

</form>
<br><br>

<div class="row">

<div class="col-md-6">
<h4>Income vs Expense</h4>
<canvas id="barChart"></canvas>
</div>

<div class="col-md-6">
<h4>Balance Overview</h4>
<canvas id="pieChart"></canvas>
</div>
</div>

<script>

var income = <?php echo $total_income; ?>;
var expense = <?php echo $total_expense; ?>;
var balance = <?php echo $balance; ?>;

/* BAR CHART */
new Chart(document.getElementById('barChart'), {

 type: 'bar',

 data: {
  labels: ['Income','Expense','Balance'],

  datasets: [{
   data: [income, expense, balance],
   backgroundColor: [
     'rgba(54,162,235,0.7)',
     'rgba(255,99,132,0.7)',
     'rgba(75,192,192,0.7)'
   ]
  }]
 },

 options: {
  responsive:true,
  plugins:{
    legend:{display:false}
  },
  scales:{
    y:{beginAtZero:true}
  }
 }

});


/* PIE CHART */
new Chart(document.getElementById('pieChart'), {

 type: 'pie',

 data: {
  labels: ['Income','Expense','Balance'],

  datasets: [{
   data: [income, expense, balance],
   backgroundColor: [
     '#36A2EB',
     '#FF6384',
     '#4BC0C0'
   ]
  }]
 },

 options: {
  responsive:true
 }

});
</script>


<?php include_once("footer.php"); ?>