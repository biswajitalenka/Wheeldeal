<?php 
include_once "navbar.php";
require_once "dbcon.php"; 

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}
$user_id = $_SESSION['id'];

$user_qry = "SELECT * FROM viewer WHERE id = ?";
$user_stmt = $conn->prepare($user_qry);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

$seller_qry = "SELECT * FROM seller WHERE vid = ?";
$seller_stmt = $conn->prepare($seller_qry);
$seller_stmt->bind_param("i", $user_id);
$seller_stmt->execute();
$seller_result = $seller_stmt->get_result();
$seller = $seller_result->num_rows > 0 ? $seller_result->fetch_assoc() : null;

// $cars_qry = "SELECT c.*, ci.image_name 
//              FROM car c 
//              LEFT JOIN car_image ci ON c.cid = ci.cid 
//              ORDER BY c.cid DESC";
// $cars_stmt = $conn->prepare($cars_qry);
// $cars_stmt->execute();
// $cars_result = $cars_stmt->get_result();

    $cars_qry = "SELECT c.*, ci.image_name 
                FROM car c 
                LEFT JOIN car_image ci ON c.cid = ci.cid 
                WHERE c.seller_id = ?
                ORDER BY c.cid DESC";
    $cars_stmt = $conn->prepare($cars_qry);
    $cars_stmt->bind_param("i", $user_id);
    $cars_stmt->execute();
    $cars_result = $cars_stmt->get_result();
?>

<div class="container my-5">
    <h2 class="text-center mb-5 text-light">My Profile</h2>
   
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card bg-dark text-light" style="border: 2px solid rgba(255, 78, 24, 0.5);">
                <div class="card-header text-white" style="background-image: linear-gradient(to right, rgba(255, 78, 24, 0.97), rgba(7, 6, 6, 0.97));">
                    <h4 class="mb-0"><i class="bi bi-person-circle"></i> Personal Information</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Name</p>
                            <p class="fs-5 fw-bold text-light"><?php echo htmlspecialchars($user['name']); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Email</p>
                            <p class="fs-5 fw-bold text-light"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                    
                    <?php if($seller){ ?>
                    <hr class="border-secondary my-3">
                    <h5 class="mb-3" >Seller Details</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <p class="mb-1 fw-bold">Mobile</p>
                            <p class="fs-6 fw-bold text-light"><?php echo htmlspecialchars($seller['mobile']); ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <p class="mb-1 fw-bold">Aadhaar</p>
                            <p class="fs-6 fw-bold text-light"><?php echo htmlspecialchars($seller['aadhaar']); ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <p class="mb-1 fw-bold">Address</p>
                            <p class="fs-6 fw-bold text-light"><?php echo htmlspecialchars($seller['address']); ?></p>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i> You haven't listed any cars yet. <a href="sell_car.php" class="alert-link">List your first car!</a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-light mb-4"><i class="bi bi-car-front"></i> My Listed Cars</h3>

    <div class="row">
    <?php if($cars_result->num_rows > 0){ ?>
        
        <?php while($car = $cars_result->fetch_assoc()){ ?>
        <div class="col-md-4 mb-4">
            <div class="card bg-black text-light" style="width: 18rem;">
                <?php if(!empty($car['image_name'])){ ?>
                    <img src="./carimage/<?php echo $car['image_name']; ?>" 
                         class="card-img-top" 
                         alt="car"
                         style="height: 200px; object-fit: contain;">
                <?php } else { ?>
                    <img src="./carimage/default.png" 
                         class="card-img-top" 
                         alt="car">
                <?php } ?>

                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <?php echo htmlspecialchars($car['model']); ?>
                    </h5>

                    <p class="text-success fs-5">₹<?php echo number_format($car['price']); ?></p>

                    <p class="small">
                        <strong>Regno:</strong> <?php echo htmlspecialchars($car['regno']); ?><br>
                        <strong>Engine:</strong> <?php echo htmlspecialchars($car['engine']); ?><br>
                        <strong>Distance:</strong> <?php echo number_format($car['distance']); ?> km<br>
                        <strong>Control:</strong> <?php echo htmlspecialchars($car['controlsys']); ?>
                    </p>

                </div>

                <div class="card-footer bg-transparent border-0 d-flex gap-2">
                    <a href="update_car.php?cid=<?php echo $car['cid']; ?>" class="btn btn-warning btn-sm flex-grow-1">
                        <i class="bi bi-pencil-square"></i> Update
                    </a>

                    <a href="delete_car.php?cid=<?php echo $car['cid']; ?>" 
                       class="btn btn-light btn-sm flex-grow-1"
                       onclick="return confirm('Delete this listing?');">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </div>

            </div>
        </div>
        <?php } ?>

    <?php } else { ?>
        <div class="alert alert-warning text-light">
            <i class="bi bi-exclamation-triangle"></i> No cars listed yet.
            <a href="sell_car.php" class="alert-link">List your first car.</a>
        </div>
    <?php } ?>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    $('.card').hover(
        function(){ $(this).css('transform','translateY(-10px)'); },
        function(){ $(this).css('transform','translateY(0)'); }
    );
});
</script>

<?php include_once "footer.php"; ?>