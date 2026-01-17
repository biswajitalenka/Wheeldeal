<?php include_once "navbar.php" ?>
<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
        $_SESSION['model']=$_POST['model'];
        $_SESSION['regnum']=$_POST['regnum'];
        $_SESSION['engine']=$_POST['engine'];
        $_SESSION['travelled']=$_POST['travelled'];
        $_SESSION['controlsys']=$_POST['controlsys'];
        
        header("location:addimage.php");
        exit();
    }
?>
<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mx-auto">
            <h1 class="mb-3">Add Car Details</h1>
                <form action="sell_car.php" method="post" id="addcar" onsubmit="validate(event)">
                    <label class="form-label">Model</label>
                    <input class="form-control mb-2" type="text" name="model">
                    <label class="error" id="modelError"></label><br>
                    
                    <label class="form-label">Registration Number</label>
                    <input class="form-control mb-2" type="text" name="regnum">
                    <label class="error" id="regError"></label><br>
                    
                    <label class="form-label">Engine Type</label>
                    <select class="form-control mb-2" name="engine">
                        <option value=""></option>
                        <option value="petrol">Petrol</option>
                        <option value="diesel">Diesel</option>
                        <option value="electric">Electric</option>
                    </select>
                    <label class="error" id="engineError"></label><br>
                    
                    <label class="form-label">Distance Travelled (km)</label>
                    <input type="number" class="form-control mb-2" name="travelled" placeholder="e.g., 50000">
                    <label class="error" id="travelError"></label><br>
                    
                    <label class="form-label">Control System</label>
                    <select class="form-control mb-2" name="controlsys">
                        <option value=""></option>
                        <option value="manual">Manual</option>
                        <option value="automatic">Automatic</option>
                    </select>
                    <label class="error" id="controlsysError"></label><br>
                     
                    <input type="submit" value="Next: Add Image" class="btn btn-light">
                </form>
        </div>
    </div>
</div>
<script>
    function validate(e){
    let error = false;

    let form = document.getElementById("addcar");
    let model = form.elements['model'].value
    let regnum = form.elements['regnum'].value
    let engine = form.elements['engine'].value
    let travelled = form.elements['travelled'].value
    let controlsys = form.elements['controlsys'].value

    let modelError = document.getElementById("modelError")
    let regError = document.getElementById("regError")
    let engineError = document.getElementById("engineError")
    let travelError = document.getElementById("travelError")
    let controlsysError = document.getElementById("controlsysError")

    if(model === ""){
        modelError.innerHTML = "Model is required"
        error = true
    } else {
        modelError.innerHTML = ""
    }
    
    if(regnum === ""){
        regError.innerHTML = "Registration number is required"
        error = true
    }
    else if(regnum.includes(" ")){
        regError.innerHTML = "Registration number should not contain spaces";
        error = true
    }
    else {
        let indianRegx = /^[A-Z]{2}[0-9]{2}[A-Z]{1,2}[0-9]{4}$/;
        if(!indianRegx.test(regnum)){
            regError.innerHTML = "Invalid registration number format (e.g., OD02AB1234)";
            error = true
        }else {
            regError.innerHTML = "";
        }
    }
    
    if(engine === ""){
        engineError.innerHTML = "Please select engine type"
        error = true
    } else {
        engineError.innerHTML = ""
    }
    
    if(travelled === ""){
        travelError.innerHTML="Distance travelled is required<br>"
        error = true
    }
    else if(isNaN(travelled)){
        travelError.innerHTML="This must be a valid number<br>"
        error = true
    }
    else if(Number(travelled)<0){
        travelError.innerHTML="Travelled kilometer can't be negative<br>"
        error = true
    }
    else if(Number(travelled)>1000000){
        travelError.innerHTML="Travelled kilometer can't exceed 1,000,000 km<br>"
        error = true
    }
    else if(!/^\d+$/.test(travelled)){
        travelError.innerHTML="Please enter only numbers without decimals<br>"
        error = true
    }
    else {
        travelError.innerHTML = ""
    }
    
    if(controlsys === ""){
        controlsysError.innerHTML = "Please select control system"
        error = true
    } else {
        controlsysError.innerHTML = ""
    }
    
    if(error){
        e.preventDefault();
    }
}
</script>
<?php
include_once "footer.php"; 
?>