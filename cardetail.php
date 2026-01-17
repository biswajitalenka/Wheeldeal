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

$qry = "SELECT c.*, ci.image_name 
        FROM car c 
        LEFT JOIN car_image ci ON c.cid = ci.cid 
        WHERE c.cid = ?"; 

$stmt = $conn->prepare($qry); 
$stmt->bind_param("i", $cid); 
$stmt->execute(); 
$result = $stmt->get_result(); 

if ($result->num_rows > 0) { 
    $data = $result->fetch_assoc(); 
?> 
<div class="container my-5"> 
    <div class="row"> 
        <div class="col-md-10 col-lg-8 mx-auto"> 
            <div class="card bg-black text-light border-0">

                <div class="card-img-wrapper" style="width: 100%; height: 400px; overflow: hidden; border-radius: 10px;">
                    <?php if(!empty($data['image_name'])){ ?>
                        <img src="./carimage/<?php echo $data['image_name']; ?>" 
                             class="card-img-top" 
                             alt="car" 
                             style="width: 100%; height: 100%; object-fit: contain; object-position: center;">
                    <?php } else { ?>
                        <img src="./carimage/default.jpg" 
                             class="card-img-top" 
                             alt="no image"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    <?php } ?>
                </div>

                <div class="card-body px-0 py-4">
                    <h2 class="card-title mb-2 fw-bold"><?php echo htmlspecialchars($data['model']); ?></h2>
                    <h3 class="text-success mb-4">₹<?php echo number_format($data['price']); ?></h3>
                    
                    <p class="mb-2">
                        <strong>Registration Number:</strong> 
                        <?php echo htmlspecialchars($data['regno']); ?>
                    </p>        
                    
                    <p class="mb-2">
                        <strong>Engine:</strong> 
                        <?php echo htmlspecialchars($data['engine']); ?>
                    </p>        
                    
                    <p class="mb-2">
                        <strong>Distance Travelled:</strong> 
                        <?php echo number_format($data['distance']); ?> km
                    </p>  
                    
                    <p class="mb-4">
                        <strong>Control System:</strong> 
                        <?php echo htmlspecialchars($data['controlsys']); ?>
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <form method="POST" action="buynow.php" class="flex-grow-1">
                            <input type="hidden" name="cid" value="<?php echo $data['cid']; ?>">
                            <button type="submit" class="btn btn-light w-100 py-2">
                                <i class="bi bi-cart-check"></i> Buy Now
                            </button>
                        </form>
                        <a href="home.php" class="btn btn-light px-4 py-2">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div> 
    </div> 
</div> 
<?php 
} else { 
    echo "<div class='container my-5'><h1 class='text-center text-danger'>404: Invalid Car ID</h1></div>"; 
} 
include_once 'footer.php'; 
?>