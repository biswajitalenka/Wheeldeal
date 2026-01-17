<?php include_once "navbar.php" ;
function getCaptcha(){ 
        $str = 'ASDFY&WVGBHNFSW^GHJKOIU@*125$67hdkg#fiey34hhsfTREgwmlpog89tdset'; 
        $max=strlen($str)-6;
        return substr($str,random_int(0,$max),6); 
} 

if(!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = getCaptcha();
}
if(isset($_GET['regen'])) {
    $_SESSION['captcha'] = getCaptcha();
    header("Location: login.php");
    exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['captcha'] !== $_SESSION['captcha']){
        echo "<div class='text-danger text-center fw-bold'>Captcha Incorrect!</div>";
        $_SESSION['captcha'] = getCaptcha();
    } else{
        $email = $_POST['email'];
        $password = $_POST['password'];
        require_once "dbcon.php";
        
        $qry = "SELECT * FROM viewer WHERE email=? and password=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 1){
            $data = $result->fetch_assoc();
            $_SESSION['id']=$data['id'];
            $_SESSION['name']=$data['name'];
            $_SESSION['email']=$data['email'];
            unset($_SESSION['captcha']);
            header("location:home.php");
            exit();
        } else {
            echo "<div class='text-danger text-center fw-bold'>Invalid Email or Password!</div>";

            $checkEmail = "SELECT * FROM viewer WHERE email=?";
            $stmt2 = $conn->prepare($checkEmail);
            $stmt2->bind_param("s", $email);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            
            if($result2->num_rows == 1){
                echo "<div class='text-warning text-center'>DEBUG: Email exists but password doesn't match</div>";
            } else {
                echo "<div class='text-warning text-center'>DEBUG: Email not found in database</div>";
            }
        }
    }
}
?>
<div class="container my-4">
    <div class="row">
        <div class="col-md-3 mx-auto">
            <h1 class="mb-3">LOG IN</h1>
            <form action="login.php" method="post">
                <label class="form-label">Email</label>
                <input class="form-control mb-2" type="email" name="email" required>
                <label>Password</label>
                <input type="password" class="form-control mb-2" name="password" required>
                <label class="form-label">Enter the Captcha</label>
                <input type="text" class="form-control mb-2" name="captcha" required>
                 <!-- <?php echo $_SESSION['captcha']; ?> -->
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="captcha p-2 fw-bold rounded text-warning fs-5">
                        <?php echo $_SESSION['captcha']; ?>
                    </span>

                    <a href="login.php?regen=1" class="btn btn-sm btn-outline-warning">
                        Regenerate
                    </a>
                </div>

                <input type="submit" value="LogIn" class="btn btn-light">
            </form>
        </div>
    </div>
</div>
<?php

include_once "footer.php" ?>