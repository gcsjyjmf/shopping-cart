<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Fei Ma">
    <title>Thank you</title>
</head>
<body>
<?php 
session_start();
if(isset($_SESSION['cart'])){
    $_SESSION['cart'] = null;
}

?>
<h1>Thank you for your purchase!</h1>
<a href="ACMEpurchases.php"><button>Back to home page</button></a>

</body>
