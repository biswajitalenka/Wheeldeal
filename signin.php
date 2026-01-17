<?php include_once "navbar.php" ?>
<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mx-auto">
            <h1 class="mb-3">SIGN UP</h1>
                <form action="signin.php" method="post" id="regForm" onsubmit="validate(event)">
                    <label class="form-label">Name</label>
                    <input class="form-control mb-2" type="text" name="name">
                    <label class="error" id="nameError"></label><br>
                    <label class="form-label">Email</label>
                    <input class="form-control mb-2" type="email" name="email">
                    <label class="error" id="emailError"></label><br>
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control mb-2" name="password">
                    <label class="error" id="passwordError"></label><br>
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control mb-2" name="cpassword">
                    <label class="error" id="cpasswordError"></label><br>
                    <input type="submit" value="SignIn" class="btn btn-light">
                </form>
        </div>
    </div>
</div>
<script>
    function validate(e){
    let error = false;

    let form = document.getElementById("regForm");
    let name = form.elements['name'].value
    let email = form.elements['email'].value
    let password = form.elements['password'].value
    let cpassword = form.elements['cpassword'].value

    let nameError = document.getElementById("nameError")
    let emailError = document.getElementById("emailError")
    let passwordError = document.getElementById("passwordError")
    let cpasswordError = document.getElementById("cpasswordError")

    if(name === ""){
        nameError.innerHTML = "Name is required"
        error = true
    } else {
        nameError.innerHTML = ""
    }
    let emailRegx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email === ""){
        emailError.innerHTML = "Email is required"
        error = true
    } else if(!emailRegx.test(email)){
        emailError.innerHTML = "Please enter a valid Email"
        error = true
    }else {
        emailError.innerHTML = ""
    }

    let passErrMsg = ""
    if(password === ""){
        passErrMsg +="Password is required<br>"
        error = true
    }
    if(!/[a-z]/.test(password)){
        passErrMsg +="Password should have 1 lower case character<br>"
        error = true
    }
    if(!/[A-Z]/.test(password)){
        passErrMsg +="Password should have 1 upper case characte<br>"
        error = true
    }
    if(!/[0-9]/.test(password)){
        passErrMsg +="Password should have 1 number<br>"
        error = true
    }
    if(!/[@#$%^&]/.test(password)){
        passErrMsg +="Password should have 1 special character<br>"
        error = true
    }
    if(password.length < 8 || password.length >15){
        passErrMsg +="Password length should be between 8 -15<br>"
        error = true
    }

    if(passErrMsg === ""){
        passwordError.innerHTML = ""
    } else {
        passwordError.innerHTML = passErrMsg
    }
    if(cpassword === ""){
        cpasswordError.innerHTML = "Confirm Password is required";
        error = true;
    }
    else if(password !== cpassword){
        cpasswordError.innerHTML = "Password and Confirm Password do not match";
        error = true;
    }
    if(error){
        e.preventDefault();
    }
}
</script>
<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
        require_once "dbcon.php";
        $name=$_POST['name'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $qry="INSERT INTO viewer(name, email, password) VALUES(?,?,?)";
        $stmt= $conn->prepare($qry);
        $stmt->bind_param("sss",$name, $email, $password);
        $res=$stmt->execute();
        if($res){
            echo "<script>alert('Your details added'); window.location='home.php';</script>";
            exit();
        }
        else{
            echo "Error".$conn->error;
        }
        $conn->close();
    }
include_once "footer.php"; 
?>
