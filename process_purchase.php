<?php
session_start();
require_once "dbcon.php";

if(!isset($_SESSION['id']) || !isset($_POST['cid'])){
    header('location:login.php');
    exit();
}

$cid = $_POST['cid'];
$buyer_name = $_POST['buyer_name'];
$contact = $_POST['contact'];
$address = $_POST['address'];

$conn->close();
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Purchase Confirmation</title>
    <link rel='stylesheet' href='../bootstrap/css/bootstrap.min.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #000; color: #fff; }
    </style>
</head>
<body>
    <div class='container my-5'>
        <div class='row'>
            <div class='col-md-6 mx-auto text-center'>
                <div class='card bg-dark text-light border-success'>
                    <div class='card-body p-5'>
                        <i class='bi bi-check-circle text-success' style='font-size: 4rem;'></i>
                        <h2 class='text-success mt-3'>Purchase Request Submitted!</h2>
                        <p class='mt-3'>Thank you, <strong><?php echo htmlspecialchars($buyer_name); ?></strong>!</p>
                        <p>Your purchase request has been received.</p>
                        <p>We will contact you at <strong><?php echo htmlspecialchars($contact); ?></strong> soon to complete the purchase.</p>
                        <hr class='my-4'>
                        <p class='mb-0'>Delivery Address:</p>
                        <p class='text-warning'><?php echo htmlspecialchars($address); ?></p>
                        <a href='home.php' class='btn btn-light mt-4'>Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src='../bootstrap/js/bootstrap.bundle.min.js'></script>
</body>
</html>