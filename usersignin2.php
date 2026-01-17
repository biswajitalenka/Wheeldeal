<?php 
include_once "navbar.php";
require_once "dbcon.php";

if(!isset($_SESSION['id'])){
    header("Location:login.php");
    exit();
}
if(!isset($_SESSION['model']) || !isset($_SESSION['regnum']) || !isset($_SESSION['engine']) || 
   !isset($_SESSION['travelled']) || !isset($_SESSION['controlsys']) || !isset($_SESSION['estimated_price'])){
    echo "<script>alert('Please complete all steps first!'); window.location='sell_car.php';</script>";
    exit();
}
if($_SERVER['REQUEST_METHOD']=="POST"){
    $id = $_SESSION['id'];
    $mobile = $_POST['mobile'];
    $address = $_POST['addr'];
    $addhaar = $_POST['addhaar'];
    
    $model = $_SESSION['model'];
    $regno = $_SESSION['regnum'];
    $engine = $_SESSION['engine'];
    $travelled = $_SESSION['travelled'];
    $controlsys = $_SESSION['controlsys'];
    $price = $_SESSION['estimated_price'];
    $image_name = isset($_SESSION['car_image']) ? $_SESSION['car_image'] : null;

    $conn->begin_transaction();
    
    try {
        // $car_qry = "INSERT INTO car(model, regno, engine, distance, controlsys, price) 
        //             VALUES(?, ?, ?, ?, ?, ?)";
        // $car_stmt = $conn->prepare($car_qry);
        // $car_stmt->bind_param("sssisi", $model, $regno, $engine, $travelled, $controlsys, $price);
        // $car_stmt->execute();
        // $car_id = $conn->insert_id;

        $car_qry = "INSERT INTO car(model, regno, engine, distance, controlsys, price, seller_id) 
                VALUES(?, ?, ?, ?, ?, ?, ?)";
            $car_stmt = $conn->prepare($car_qry);
            $car_stmt->bind_param("sssisii", $model, $regno, $engine, $travelled, $controlsys, $price, $id);
            $car_stmt->execute();
            $car_id = $conn->insert_id;

        if($image_name){
            $img_qry = "INSERT INTO car_image(cid, image_name) VALUES(?, ?)";
            $img_stmt = $conn->prepare($img_qry);
            $img_stmt->bind_param("is", $car_id, $image_name);
            $img_stmt->execute();
        }

        $check_seller = "SELECT sid FROM seller WHERE vid = ?";
        $check_stmt = $conn->prepare($check_seller);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $seller_result = $check_stmt->get_result();

        if($seller_result->num_rows == 0){
            $seller_qry = "INSERT INTO seller(vid, mobile, address, aadhaar) VALUES(?, ?, ?, ?)";
            $seller_stmt = $conn->prepare($seller_qry);
            $seller_stmt->bind_param("issi", $id, $mobile, $address, $addhaar);
            $seller_stmt->execute();
        } else {
            $update_qry = "UPDATE seller SET mobile = ?, address = ?, aadhaar = ? WHERE vid = ?";
            $update_stmt = $conn->prepare($update_qry);
            $update_stmt->bind_param("ssii", $mobile, $address, $addhaar, $id);
            $update_stmt->execute();
        }

        $conn->commit();

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
                                <hr class='my-4'>
                                <p class='mb-0'><strong>Your Contact Details:</strong></p>
                                <p class='text-warning'>Mobile: <?php echo htmlspecialchars($mobile); ?></p>
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
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<div class='container'><div class='alert alert-danger'>Error: " . $e->getMessage() . "</div></div>";
    }
}
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card bg-dark text-light border-warning">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 text-warning">Enter Your Contact Details</h2>
                    <p class="text-center text-muted mb-4">We need these details to complete your car listing</p>                   
                    <form action="usersignin2.php" method="post" id="regForm" onsubmit="validate(event)">
                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input class="form-control bg-black text-light border-secondary" type="text" name="mobile" placeholder="Enter 10-digit mobile number">
                            <label class="error" id="mobileError"></label>
                        </div>                       
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control bg-black text-light border-secondary" name="addr" rows="3" placeholder="Enter your complete address"></textarea>
                            <label class="error" id="addressError"></label>
                        </div>                       
                        <div class="mb-3">
                            <label class="form-label">Aadhaar Number</label>
                            <input class="form-control bg-black text-light border-secondary" type="text" name="addhaar" placeholder="Enter 12-digit Aadhaar number" maxlength="12">
                            <label class="error" id="addhaarError"></label>
                        </div>                       
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-light flex-grow-1 py-2">
                                <i class="bi bi-check-circle"></i> Submit & List Car
                            </button>
                            <a href="sell_car.php" class="btn btn-light px-4 py-2">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validate(e){
        let error = false;

        let form = document.getElementById("regForm");
        let mobile = form.elements['mobile'].value
        let addr = form.elements['addr'].value
        let addhaar = form.elements['addhaar'].value;

        let mobileError = document.getElementById("mobileError")
        let addressError = document.getElementById("addressError")
        let addhaarError = document.getElementById("addhaarError")

        let mobregx = /^[6-9][0-9]{9}$/;
        if(mobile === ""){
            mobileError.innerHTML = "Please enter mobile number";
            error = true;
        } else if(!(mobregx.test(mobile))){
            mobileError.innerHTML = "Please enter valid mobile number";
            error = true;
        } else{
            mobileError.innerHTML = ""; 
        }  
        if(addr === ""){
            addressError.innerHTML = "Please enter address";
            error = true;
        } else{
            addressError.innerHTML = ""; 
        } 
        if(addhaar === ""){
            addhaarError.innerHTML = "Aadhaar is required";
            error = true;
        } else if(!/^\d+$/.test(addhaar)){
            addhaarError.innerHTML = "Aadhaar number must contain digits only";
            error = true;
        } else if(addhaar.length !== 12){
            addhaarError.innerHTML = "Aadhaar number must be exactly 12 digits";
            error = true;
        } else if(/^[01]/.test(addhaar)){
            addhaarError.innerHTML = "Aadhaar number cannot start with 0 or 1";
            error = true;
        } else {
            addhaarError.innerHTML = "";
        }
        
        if(error){
            e.preventDefault();
        }
    }
</script>
<?php include_once "footer.php"; ?>