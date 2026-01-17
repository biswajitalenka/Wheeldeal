<?php
include_once 'navbar.php';
require_once "dbcon.php";

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}

$search_performed = false;
$search_key = '';
$result = null;

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['search_key'])){
    $search_performed = true;
    $search_key = trim($_POST['search_key']);
    
    if(!empty($search_key)){
        $search_pattern = "%" . $search_key . "%";
        $current_user_id = $_SESSION['id'];
        
        // Query with image join and seller_id filter
        $qry = "SELECT c.*, ci.image_name 
                FROM car c 
                LEFT JOIN car_image ci ON c.cid = ci.cid 
                WHERE (c.seller_id IS NULL OR c.seller_id != ?)
                  AND (c.model LIKE ? OR c.regno LIKE ? OR c.engine LIKE ? OR c.controlsys LIKE ?)
                ORDER BY c.cid DESC";
        
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("issss", $current_user_id, $search_pattern, $search_pattern, $search_pattern, $search_pattern);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            
            <?php if($search_performed): ?>
                <?php if($result && $result->num_rows > 0): ?>
                    
                    <!-- Results Header -->
                    <h3 class="mb-4">
                        <i class="bi bi-check-circle text-success"></i> 
                        Found <?php echo $result->num_rows; ?> car(s) for "<?php echo htmlspecialchars($search_key); ?>"
                    </h3>
                    
                    <!-- Results Grid -->
                    <div class="row">
                        <?php while($data = $result->fetch_assoc()): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card bg-dark text-light" style="border: 1px solid rgba(255, 78, 24, 0.3);">
                                    
                                    <!-- Car Image -->
                                    <div style="height: 200px; overflow: hidden;">
                                        <?php if(!empty($data['image_name'])): ?>
                                            <img src="./carimage/<?php echo $data['image_name']; ?>" 
                                                 class="card-img-top w-100 h-100" 
                                                 alt="car"
                                                 style="object-fit: cover; object-position: center;">
                                        <?php else: ?>
                                            <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Car Details -->
                                    <div class="card-body">
                                        <h5 class="card-title" style="color: rgba(255, 78, 24, 0.97);">
                                            <?php echo htmlspecialchars($data['model']); ?>
                                        </h5>
                                        <p class="text-success fs-5 mb-2">
                                            ₹<?php echo number_format($data['price']); ?>
                                        </p>
                                        <p class="card-text small">
                                            <i class="bi bi-fuel-pump text-warning"></i> 
                                            Engine: <?php echo htmlspecialchars($data['engine']); ?><br>
                                            <i class="bi bi-speedometer2 text-primary"></i> 
                                            Distance: <?php echo number_format($data['distance']); ?> km<br>
                                            <i class="bi bi-credit-card text-info"></i> 
                                            Regno: <?php echo htmlspecialchars($data['regno']); ?>
                                        </p>
                                        
                                        <form method="POST" action="cardetail.php">
                                            <input type="hidden" name="cid" value="<?php echo $data['cid']; ?>">
                                            <button type="submit" class="btn btn-light w-100 mt-2">
                                                <i class="bi bi-eye"></i> View Details
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                <?php else: ?>
                    
                    <!-- No Results -->
                    <div class="alert alert-warning text-center py-5">
                        <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                        <h3 class="mt-3">No Cars Found</h3>
                        <p>No cars match your search: <strong>"<?php echo htmlspecialchars($search_key); ?>"</strong></p>
                        <a href="home.php" class="btn btn-warning mt-3">
                            <i class="bi bi-house"></i> Back to Home
                        </a>
                    </div>
                    
                <?php endif; ?>
            <?php else: ?>
                
                <!-- No search performed yet -->
                <div class="text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h4 class="mt-3 text-muted">Use the search box above to find cars</h4>
                    <a href="home.php" class="btn btn-outline-light mt-3">
                        <i class="bi bi-arrow-left"></i> Browse All Cars
                    </a>
                </div>
                
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>