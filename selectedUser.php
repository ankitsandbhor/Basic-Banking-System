<?php
$server  = "localhost:3307";
$username = "root";
$password = "";

$mysqli = new mysqli($server,$username,$password);
if ($mysqli->connect_error) { 
    die('Connect Error (' .  
    $mysqli->connect_errno . ') '.  
    $mysqli->connect_error); 
} 
if(isset($_POST['submit']))
{
 
$from = $_GET['id'];
$to = $_POST['to'];
$amount = $_POST['amount'];

$sql = "SELECT * FROM tsf1.user where id=$from "; 
$result = $mysqli->query($sql);
$rows=$result->fetch_assoc();


$sql = "SELECT * from tsf1.user where id=$to";
$result = $mysqli->query($sql);
$rows1=$result->fetch_assoc();



if (($amount)<0)
{
     echo '<script type="text/javascript">';
     echo ' alert("Oops! Negative values cannot be transferred")';  // showing an alert box.
     echo '</script>';
 }

 else if($amount >$rows['balance']) 
 {
     
     echo '<script type="text/javascript">';
      echo ' alert("Bad Luck! Insufficient Balance")';  // showing an alert box.
      echo '</script>';
 }
 

 else if($amount == 0){

  echo "<script type='text/javascript'>";
    echo "alert('Oops! Zero value cannot be transferred')";
    echo "</script>";
}





else {
        
  // deducting amount from sender's account
  $newbalance = $rows['balance'] - $amount;
  $sql = "UPDATE tsf1.user set balance=$newbalance where id=$from";
  mysqli_query($mysqli,$sql);


  // adding amount to reciever's account
  $newbalance = $rows1['balance'] + $amount;
  $sql = "UPDATE tsf1.user set balance=$newbalance where id=$to";
  mysqli_query($mysqli,$sql);
  
  $sender = $rows['name'];
  $receiver = $rows1['name'];
  $sql = "INSERT INTO tsf1.transaction(`sender`, `receiver`, `amount`) VALUES ('$sender','$receiver','$amount')";
  $query=mysqli_query($mysqli,$sql);

  if($query){
       echo "<script> alert('Transaction Successful');
        window.location = 'transactionhistory.php';
      </script>";
      
  }

  $newbalance= 0;
  $amount =0;
}
}
$mysqli->close();  
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="selectedUserStyle.css">
  <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
  <title>User Details</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="image/logo.png" alt="" id="logo" class="d-inline-block align-text-top">
        Indusind Bank Ltd 
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="index.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="About.html">AboutUs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.html">contactUs</a>
          </li>

        </ul>

      </div>
    </div>
  </nav>

  <div class="container">
    <h2 class="text-center pt-4">Transaction</h2>
    <?php
                $server  = "localhost:3307";
                $username = "root";
                $password = "";
              
                
                $mysqli = mysqli_connect($server,$username,$password);
                if ($mysqli->connect_error) { 
                    die('Connect Error (' .  
                    $mysqli->connect_errno . ') '.  
                    $mysqli->connect_error); 
                } 
                
                 
               
                $sid=$_GET['id'];
                $sql = "SELECT * FROM  tsf1.user where id=$sid";
                $result = $mysqli->query($sql); 
              
                if(!$result)
                {
                    echo "Error : ".$sql."<br>".mysqli_error($mysqli);
                }
                $rows=mysqli_fetch_assoc($result);
            ?>
    <form method="post" name="tcredit" class="tabletext"><br>
      <div>
        <table class="table table-striped table-condensed table-bordered">
          <tr>
            <th class="text-center">Id</th>
            <th class="text-center">Name</th>
            <th class="text-center">Email</th>
            <th class="text-center">Balance</th>
          </tr>
          <tr>
            <td class="py-2">
              <?php echo $rows['id'] ?>
            </td>
            <td class="py-2">
              <?php echo $rows['name'] ?>
            </td>
            <td class="py-2">
              <?php echo $rows['email'] ?>
            </td>
            <td class="py-2">
              <?php echo $rows['balance'] ?>
            </td>
          </tr>
        </table>
      </div>
      <br>
      <label>Transfer To:</label>
      <select name="to" class="form-control" required>
        <option value="" disabled selected>Choose</option>
        <?php
                $server  = "localhost:3307";
                $username = "root";
                $password = "";
                $mysqli = mysqli_connect($server,$username,$password);
                if ($mysqli->connect_error) { 
                    die('Connect Error (' .  
                    $mysqli->connect_errno . ') '.  
                    $mysqli->connect_error); 
                } 
                
                $sid=$_GET['id'];
                $sql = "SELECT * FROM tsf1.user where id!=$sid";
                 $result = $mysqli->query($sql);
                 
              
                if(!$result)
                {
                    echo "Error : ".$sql."<br>".mysqli_error($mysqli);
                } 
               
                   // LOOP TILL END OF DATA  
                   while($rows=$result->fetch_assoc()) {
                
             
            ?>
        <option class="table" value="<?php echo $rows['id'];?>">

          <?php echo $rows['name'] ;?> (Balance:
          <?php echo $rows['balance'] ;?> )

        </option>
        <?php 
                } 
            ?>
        <div>
      </select>
      <br>
      <br>
      <label>Amount:</label>
      <input type="number" class="form-control" name="amount" required>
      <br><br>
      <div class="text-center">
        <button class="btn btn-dark" name="submit" type="submit">Make Payment</button>

      </div>
    </form>
  </div>

  <div id="foot">
    <hr>
    <footer class="container">
      <p class="float-end"><a href="#">Back to top</a></p>

      <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bank2"
          viewBox="0 0 16 16">
          <path
            d="M8.277.084a.5.5 0 0 0-.554 0l-7.5 5A.5.5 0 0 0 .5 6h1.875v7H1.5a.5.5 0 0 0 0 1h13a.5.5 0 1 0 0-1h-.875V6H15.5a.5.5 0 0 0 .277-.916l-7.5-5zM12.375 6v7h-1.25V6h1.25zm-2.5 0v7h-1.25V6h1.25zm-2.5 0v7h-1.25V6h1.25zm-2.5 0v7h-1.25V6h1.25zM8 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2zM.5 15a.5.5 0 0 0 0 1h15a.5.5 0 1 0 0-1H.5z" />
        </svg>© 2021–2025  Indusind Bank Ltd · <a href="#">Privacy</a> · <a href="#">Terms</a></p>
    </footer>
  </div>

  </div>


  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
    crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
</body>

</html>