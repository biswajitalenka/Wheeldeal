<?php 
session_start();
require_once "dbcon.php"; 

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}
if(!isset($_GET['cid'])){
    header('location:profile.php');
    exit();
}

$cid = $_GET['cid'];

$qry = "DELETE FROM car_image WHERE cid = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid);
$stmt->execute();

$car_qry = "DELETE FROM car WHERE cid = ?";
$car_stmt = $conn->prepare($car_qry);
$car_stmt->bind_param("i", $cid);

if($car_stmt->execute()){
    echo "<script>alert('Car deleted successfully!'); window.location='profile.php';</script>";
} else {
    echo "<script>alert('Error deleting car: " . $conn->error . "'); window.location='profile.php';</script>";
}

$conn->close();
?>