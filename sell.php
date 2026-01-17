<?php 
include_once "navbar.php";

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}

if(!isset($_SESSION['model']) || !isset($_SESSION['travelled']) || !isset($_SESSION['engine'])){
    echo "<script>alert('Please complete the car details first!'); window.location='sell_car.php';</script>";
    exit();
}

$model = $_SESSION['model'];
$travelled = $_SESSION['travelled'];
$engine = $_SESSION['engine'];

function calculatePrice($model, $travelled, $engine) {
    $base_prices = [
        'SUV500' => 1500000,
        'BMW' => 2000000,
        'Audi' => 1800000,
        'Mercedes' => 2500000,
        'Toyota' => 1200000,
        'Honda' => 1000000,
        'Maruti' => 800000,
        'Hyundai' => 900000,
        'Tata' => 700000,
        'Mahindra' => 850000,
        'Ford' => 950000,
        'Volkswagen' => 1100000
    ];
    
    $price = 1000000;
    foreach($base_prices as $car_model => $base_price) {
        if(stripos($model, $car_model) !== false) {
            $price = $base_price;
            break;
        }
    }

    if($travelled <= 10000) {
        $depreciation = 0.95; 
    } elseif($travelled <= 30000) {
        $depreciation = 0.85; 
    } elseif($travelled <= 50000) {
        $depreciation = 0.75; 
    } elseif($travelled <= 75000) {
        $depreciation = 0.65; 
    } elseif($travelled <= 100000) {
        $depreciation = 0.55; 
    } else {
        $depreciation = 0.45; 
    }
    
    $price = $price * $depreciation;
    
    $engine_lower = strtolower($engine);
    if(strpos($engine_lower, 'diesel') !== false) {
        $price = $price * 1.1; 
    } elseif(strpos($engine_lower, 'electric') !== false || strpos($engine_lower, 'ev') !== false) {
        $price = $price * 1.15; 
    } elseif(strpos($engine_lower, 'hybrid') !== false) {
        $price = $price * 1.12; 
    }
    
    return round($price, -3); 
}
$estimated_price = calculatePrice($model, $travelled, $engine);

if($_SERVER['REQUEST_METHOD']=="POST"){
    $_SESSION['estimated_price'] = $estimated_price;
    header("location:sell2.php");
    exit();
}
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card bg-dark text-light border-warning">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 text-warning">Car Price Estimation</h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Model:</strong></p>
                            <p class="text-warning"><?php echo htmlspecialchars($model); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Engine:</strong></p>
                            <p class="text-warning"><?php echo htmlspecialchars($engine); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Distance:</strong></p>
                            <p class="text-warning"><?php echo number_format($travelled); ?> km</p>
                        </div>
                    </div>
                    
                    <hr class="border-secondary">
                    
                    <div class="text-center my-4">
                        <p class="mb-2 text-light">Estimated Price</p>
                        <h1 class="text-success fw-bold">₹<?php echo number_format($estimated_price); ?></h1>
                        <p class="text-muted small">*Price calculated based on model, engine type, and distance travelled</p>
                    </div>
                    
                    <!-- <hr class="border-secondary"> -->
                    
                    <!-- Price breakdown -->
                    <!-- <div class="text-start mb-4">
                        <p class="text-light small mb-1"><strong>Price Breakdown:</strong></p>
                        <ul class="text-muted small">
                            <li>Base price determined by model</li>
                            <li>Adjusted for distance travelled (<?php echo number_format($travelled); ?> km)</li>
                            <li>Adjusted for engine type (<?php echo htmlspecialchars($engine); ?>)</li>
                        </ul>
                    </div> -->
                    
                    <div class="text-center mt-4">
                        <p class="fw-bold mb-3">Do you want to sell your car at this price?</p>
                        <form method="POST" class="d-inline">
                            <button type="submit" class="btn btn-light btn-lg px-5 me-2">
                                <i class="bi bi-check-circle"></i> YES, SELL NOW
                            </button>
                        </form>
                        <a href="home.php" class="btn btn-light btn-lg px-5">
                            <i class="bi bi-x-circle"></i> CANCEL
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>