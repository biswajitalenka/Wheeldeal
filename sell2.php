<?php
include_once "navbar.php";
require_once "dbcon.php";

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}

if(!isset($_SESSION['model']) || !isset($_SESSION['regnum']) || !isset($_SESSION['engine']) || 
   !isset($_SESSION['travelled']) || !isset($_SESSION['controlsys']) || !isset($_SESSION['estimated_price'])){
    echo "<script>alert('Please complete all steps!'); window.location='sell_car.php';</script>";
    exit();
}

$seller_id = $_SESSION['id'];
$model = $_SESSION['model'];
$regno = $_SESSION['regnum'];
$engine = $_SESSION['engine'];
$travelled = $_SESSION['travelled'];
$controlsys = $_SESSION['controlsys'];
$price = $_SESSION['estimated_price'];
$image_name = isset($_SESSION['car_image']) ? $_SESSION['car_image'] : null;

if($_SERVER['REQUEST_METHOD']=="POST"){
    $check_seller = "SELECT sid FROM seller WHERE vid = ?";
    $check_stmt = $conn->prepare($check_seller);
    $check_stmt->bind_param("i", $seller_id);
    $check_stmt->execute();
    $seller_result = $check_stmt->get_result();
    
    if($seller_result->num_rows > 0){
        $car_qry = "INSERT INTO car(model, regno, engine, distance, controlsys, price, seller_id) 
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
        $car_stmt = $conn->prepare($car_qry);
        $car_stmt->bind_param("sssisii", $model, $regno, $engine, $travelled, $controlsys, $price, $seller_id);
        
        if($car_stmt->execute()){
            $car_id = $conn->insert_id;

            if($image_name){
                $img_qry = "INSERT INTO car_image(cid, image_name) VALUES(?, ?)";
                $img_stmt = $conn->prepare($img_qry);
                $img_stmt->bind_param("is", $car_id, $image_name);
                $img_stmt->execute();
            }
            unset($_SESSION['model']);
            unset($_SESSION['regnum']);
            unset($_SESSION['engine']);
            unset($_SESSION['travelled']);
            unset($_SESSION['controlsys']);
            unset($_SESSION['estimated_price']);
            unset($_SESSION['car_image']);
            
            $conn->close();

            ?>
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Success</title>
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
                                    <h2 class='text-success mt-3'>Car Listed Successfully!</h2>
                                    <p class='mt-3'>Your <strong><?php echo htmlspecialchars($model); ?></strong> has been listed for sale.</p>
                                    <p class='fs-4 text-success'>Price: ₹<?php echo number_format($price); ?></p>
                                    <p class='text-muted'>Buyers can now see your car listing!</p>
                                    <a href='home.php' class='btn btn-light mt-4 px-5'>Back to Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
            exit();
        } else {
            echo "<div class='container'><div class='alert alert-danger'>Error: " . $conn->error . "</div></div>";
        }
    } else {

        header("location:usersignin2.php");
        exit();
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card bg-dark text-light border-success">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Confirm Listing</h2>
                    
                    <div class="row">
                        <div class="col-md-5">
                            <?php if($image_name){ ?>
                                <img src="./carimage/<?php echo $image_name; ?>" class="img-fluid rounded" alt="car" style="max-height: 300px; width: 100%; object-fit: cover;">
                            <?php } else { ?>
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <p class="text-muted">No image uploaded</p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-7">
                            <h4 class="text-warning"><?php echo htmlspecialchars($model); ?></h4>
                            <p><strong>Registration:</strong> <?php echo htmlspecialchars($regno); ?></p>
                            <p><strong>Engine:</strong> <?php echo htmlspecialchars($engine); ?></p>
                            <p><strong>Distance:</strong> <?php echo number_format($travelled); ?> km</p>
                            <p><strong>Control System:</strong> <?php echo htmlspecialchars($controlsys); ?></p>
                            <hr>
                            <h3 class="text-success">Price: ₹<?php echo number_format($price); ?></h3>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-3">Everything looks good? Click below to list your car!</p>
                        <form method="POST" class="d-inline">
                            <button type="submit" class="btn btn-light btn-lg px-5 me-2">
                                <i class="bi bi-check-circle"></i> Confirm & List Car
                            </button>
                        </form>
                        <a href="sell_car.php" class="btn btn-light btn-lg px-5">
                            <i class="bi bi-x-circle"></i> Start Over
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>