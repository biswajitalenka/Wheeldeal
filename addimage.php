<?php
include_once "navbar.php";

if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}
if(!isset($_SESSION['model']) || !isset($_SESSION['regnum']) || !isset($_SESSION['engine']) || 
   !isset($_SESSION['travelled']) || !isset($_SESSION['controlsys'])){
    echo "<script>alert('Please complete car details first!'); window.location='sell_car.php';</script>";
    exit();
}
$msg = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_FILES['cimage']) && $_FILES['cimage']['error'] == 0){
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        $file_type = $_FILES['cimage']['type'];
        
        if(in_array($file_type, $allowed_types)){
            $file_name = $_FILES['cimage']['name'];
            $new_file_name = time()."-".$file_name;
            $tmp_location = $_FILES['cimage']['tmp_name'];
            $upload_path = "carimage/$new_file_name";

            if(move_uploaded_file($tmp_location, $upload_path)){
                $_SESSION['car_image'] = $new_file_name;
                header("location:sell.php");
                exit();
            } else {
                $msg = "Error uploading file: ".$_FILES['cimage']['error'];
            }
        } else {
            $msg = "Only JPG, JPEG, and PNG images are allowed.";
        }
    } else {
        $msg = "Please select an image to upload.";
    }
}
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card bg-dark text-light border-warning">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Upload Car Image</h2>
                    
                    <?php if($msg != ""){ ?>
                        <div class="alert alert-danger"><?php echo $msg; ?></div>
                    <?php } ?>
                    
                    <div class="alert alert-info">
                        <h5>Your Car Details:</h5>
                        <p><strong>Model:</strong> <?php echo htmlspecialchars($_SESSION['model']); ?></p>
                        <p><strong>Registration:</strong> <?php echo htmlspecialchars($_SESSION['regnum']); ?></p>
                        <p><strong>Engine:</strong> <?php echo htmlspecialchars($_SESSION['engine']); ?></p>
                        <p><strong>Distance:</strong> <?php echo number_format($_SESSION['travelled']); ?> km</p>
                        <p class="mb-0"><strong>Control System:</strong> <?php echo htmlspecialchars($_SESSION['controlsys']); ?></p>
                    </div>
                    
                    <form action="addimage.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Select Car Image *</label>
                            <input type="file" class="form-control" name="cimage" accept="image/*" required>
                            <small class="text-muted">Accepted formats: JPG, JPEG, PNG (Max 5MB)</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-light flex-grow-1">
                                <i class="bi bi-upload"></i> Upload & Continue
                            </button>
                            <a href="sell_car.php" class="btn btn-light">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>