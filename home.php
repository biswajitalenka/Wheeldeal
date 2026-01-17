<?php 
include_once "navbar.php";

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}

require_once 'dbcon.php';
$current_user_id = $_SESSION['id'];
$qry = "SELECT c.*, ci.image_name 
        FROM car c 
        LEFT JOIN car_image ci ON c.cid = ci.cid 
        WHERE c.seller_id IS NULL OR c.seller_id != ?
        ORDER BY c.cid DESC";

$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="container mt-5">
    <p class="fw-bold text-md-start">SELL YOUR CAR</p>
    <div class="row mb-5 mt-3">
        <div class="col-md-8">
            <button class="btn btn-light" onclick="window.location.href='sell_car.php'">Sell Car</button>
        </div>
    </div>
    <p class="fw-bold text-md-start">CARS YOU MAY LIKE</p>
    <div class="row mb-5 mt-3">
        <?php 
        if($result->num_rows > 0){
            while($data = $result->fetch_assoc()){ 
        ?> 
        <div class="col-md-4 mb-4">
            <div class="card bg-black text-light" style="width: 18rem;">
                <?php if(!empty($data['image_name'])){ ?>
                    <img src="./carimage/<?php echo $data['image_name']; ?>" class="card-img-top" alt="car">
                <?php } else { ?>
                    <img src="./carimage/default.png" class="card-img-top" alt="default">
                <?php } ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($data['model']); ?></h5>
                    <p class="card-text">
                        <strong class="text-success fs-5">₹<?php echo number_format($data['price']); ?></strong><br>
                        <small>Engine: <?php echo htmlspecialchars($data['engine']); ?></small><br>
                        <small>Distance: <?php echo number_format($data['distance']); ?> km</small>
                    </p>                  
                    <form method="POST" action="cardetail.php">
                        <input type="hidden" name="cid" value="<?php echo $data['cid']; ?>">
                        <button type="submit" class="btn btn-light">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
        <?php 
            } 
        } else {
            echo "<div class='col-12'><p class='text-center'>No cars available at the moment.</p></div>";
        }
        ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    $('.card').hover(
        function(){ 
            $(this).css({
                'transform':'translateY(-10px)',
            });
        },
        function(){ 
            $(this).css({
                'transform':'translateY(0)',
            });
        }
    );
});
</script>
<?php include_once "footer.php"; ?>