<?php 
include_once "navbar.php";
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

$qry = "SELECT c.*, ci.image_name 
        FROM car c 
        LEFT JOIN car_image ci ON c.cid = ci.cid 
        WHERE c.cid = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "<script>alert('Car not found!'); window.location='profile.php';</script>";
    exit();
}
$car = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $model = $_POST['model'];
    $regno = $_POST['regno'];
    $engine = $_POST['engine'];
    $distance = $_POST['distance'];
    $controlsys = $_POST['controlsys'];
    $price = $_POST['price'];
    
    $update_qry = "UPDATE car SET model=?, regno=?, engine=?, distance=?, controlsys=?, price=? WHERE cid=?";
    $update_stmt = $conn->prepare($update_qry);
    $update_stmt->bind_param("sssisii", $model, $regno, $engine, $distance, $controlsys, $price, $cid);
    
    if($update_stmt->execute()){
        echo "<script>alert('Car updated successfully!'); window.location='profile.php';</script>";
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card bg-dark text-light" style="border: 2px solid rgba(255, 78, 24, 0.5);">
                <div class="card-header text-white" style="background-image: linear-gradient(to right, rgba(255, 78, 24, 0.97), rgba(7, 6, 6, 0.97));">
                    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Update Car Details</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model</label>
                                <input type="text" name="model" class="form-control bg-black text-light border-secondary" 
                                       value="<?php echo htmlspecialchars($car['model']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Registration Number</label>
                                <input type="text" name="regno" class="form-control bg-black text-light border-secondary" 
                                       value="<?php echo htmlspecialchars($car['regno']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Engine Type</label>
                                <select name="engine" class="form-control bg-black text-light border-secondary" required>
                                    <option value="">-- Select Engine Type --</option>
                                    <option value="petrol" <?php echo ($car['engine'] == 'petrol') ? 'selected' : ''; ?>>Petrol</option>
                                    <option value="diesel" <?php echo ($car['engine'] == 'diesel') ? 'selected' : ''; ?>>Diesel</option>
                                    <option value="electric" <?php echo ($car['engine'] == 'electric') ? 'selected' : ''; ?>>Electric</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Distance (km)</label>
                                <input type="number" name="distance" class="form-control bg-black text-light border-secondary" 
                                       value="<?php echo $car['distance']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Control System</label>
                                <select name="controlsys" class="form-control bg-black text-light border-secondary" required>
                                    <option value="">-- Select Control System --</option>
                                    <option value="manual" <?php echo ($car['controlsys'] == 'manual') ? 'selected' : ''; ?>>Manual</option>
                                    <option value="automatic" <?php echo ($car['controlsys'] == 'automatic') ? 'selected' : ''; ?>>Automatic</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (₹)</label>
                                <input type="number" name="price" class="form-control bg-black text-light border-secondary" 
                                       value="<?php echo $car['price']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-light flex-grow-1">
                                <i class="bi bi-check-circle"></i> Update Car
                            </button>
                            <a href="profile.php" class="btn btn-light px-5">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "footer.php"; ?>