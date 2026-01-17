<?php 
include_once "navbar.php";
require_once "dbcon.php";

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}

if (!isset($_POST['cid'])) { 
    header('location:home.php'); 
    exit();
} 

$cid = $_POST['cid'];
$buyer_id = $_SESSION['id'];

$qry = "SELECT c.*, ci.image_name 
        FROM car c 
        LEFT JOIN car_image ci ON c.cid = ci.cid 
        WHERE c.cid = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "<div class='container my-5'><h3 class='text-center text-danger'>Car not found!</h3></div>";
    include_once "footer.php";
    exit();
}

$car = $result->fetch_assoc();
?>

<div class="container-fluid my-5 px-4">
    <h2 class="mb-4 text-center text-white">Confirm Your Purchase</h2>
    
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card bg-dark text-light border-secondary overflow-hidden" style="border-radius: 15px;">

                <div class="row g-0">

                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="w-100 position-relative" style="min-height: 600px;">
                            <?php if(!empty($car['image_name'])) { ?>
                                <img src="./carimage/<?php echo $car['image_name']; ?>" 
                                     class="w-100 h-100" 
                                     alt="car" 
                                     style="object-fit: contain; object-position: center;">
                            <?php } else { ?>
                                <img src="./carimage/default.jpg" 
                                     class="w-100 h-100" 
                                     alt="no image"
                                     style="object-fit: cover;">
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="card-body p-5 d-flex flex-column justify-content-between w-100">

                            <div>
                                <h2 class="card-title mb-4"><?php echo htmlspecialchars($car['model']); ?></h2>
                                
                                <div class="mb-4">
                                    <p class="mb-2"><strong>Registration:</strong> <?php echo htmlspecialchars($car['regno']); ?></p>
                                    <p class="mb-2"><strong>Engine:</strong> <?php echo htmlspecialchars($car['engine']); ?></p>
                                    <p class="mb-2"><strong>Distance:</strong> <?php echo number_format($car['distance']); ?> km</p>
                                    <p class="mb-2"><strong>Control System:</strong> <?php echo htmlspecialchars($car['controlsys']); ?></p>
                                    <?php if(isset($car['price'])){ ?>
                                        <p class="mb-0"><strong>Price:</strong> 
                                            <span class="text-success fs-3">₹<?php echo number_format($car['price']); ?></span>
                                        </p>
                                    <?php } ?>
                                </div>

                                <hr class="my-4 border-secondary opacity-50">
                            </div>

                            <form method="POST" action="process_purchase.php">
                                <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-white">Your Name</label>
                                    <input type="text" name="buyer_name" 
                                           class="form-control form-control-lg bg-black text-light border-secondary" 
                                           value="<?php echo htmlspecialchars($_SESSION['name']); ?>" 
                                           style="border-radius: 10px;"
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-white">Contact Number</label>
                                    <input type="tel" name="contact" 
                                           class="form-control form-control-lg bg-black text-light border-secondary" 
                                           placeholder="Enter your phone number" 
                                           pattern="[0-9]{10}" 
                                           maxlength="10"
                                           style="border-radius: 10px;"
                                           required>
                                    <small class="text-muted">Enter 10-digit mobile number</small>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-white">Delivery Address</label>
                                    <textarea name="address" 
                                              class="form-control form-control-lg bg-black text-light border-secondary" 
                                              rows="3" 
                                              placeholder="Enter your delivery address" 
                                              style="border-radius: 10px;"
                                              required></textarea>
                                </div>
                                
                                <div class="d-flex gap-3">
                                    <button type="submit" 
                                            class="btn btn-light btn-lg flex-grow-1 py-3" 
                                            style="border-radius: 10px;">
                                        <i class="bi bi-check-circle"></i> Confirm Purchase
                                    </button>
                                    <a href="home.php" 
                                       class="btn btn-light btn-lg px-5 py-3" 
                                       style="border-radius: 10px;">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>