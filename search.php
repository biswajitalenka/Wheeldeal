<?php
include_once 'navbar.php';
if($_SERVER['REQUEST_METHOD']=="POST"){
    require_once "dbcon.php";
    $search_key = $_POST['search_key'];
    $search_key = "%" . $search_key . "%";
    $qry = "SELECT * FROM car WHERE model LIKE ? OR regno LIKE ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ss", $search_key, $search_key);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "<h1>No Such Product Available</h1>";
    } else {?>
    <div class="container my-3">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h1 class="text-center mb-2">Available Cars</h1>
                    <?php
                        while ($data = $result->fetch_assoc()) {
                    ?>
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
                    <?php
                        }
                    ?>
            </div>
        </div>
    </div>

<?php
    }
}
?>