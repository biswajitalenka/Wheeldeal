<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WheelDeal</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">  
  <style>
    .navbar {
      background-color: #000;
      box-shadow: 0 2px 10px rgba(255, 78, 24, 0.3);
    }
     .navbar-brand {
      font-weight: 700;
      font-size: 1.6rem;
      color: #fd7e14 !important;
      letter-spacing: 1px;
    }
    .dropdown-color{
      background-image: linear-gradient(to right, rgba(7, 6, 6, 0.97), rgba(255, 78, 24, 0.97));
      border-radius: 5px;
    }
    .home-link{
      background-image: linear-gradient(to right, rgba(255, 78, 24, 0.97), rgba(7, 6, 6, 0.97));
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
    }
    .home-link:hover {
      background-image: linear-gradient(to right, rgba(7, 6, 6, 0.97), rgba(255, 78, 24, 0.97));
      color: white;
    }
    .form-control {
      background-color: #111;
      border: 1px solid #444;
      color: #fff;
      width: 260px;
      border-radius: 10px;
      padding-left: 15px;
    }
    .btn{
      background-image: linear-gradient(to right, rgba(255, 78, 24, 0.97), rgba(7, 6, 6, 0.97));
      border: none;
      color: white;
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: 600;
    }
    .btn:hover {
      background-image: linear-gradient(to right, rgba(7, 6, 6, 0.97), rgba(255, 78, 24, 0.97));
    }
    .error{
      color:red;
    }
    .captcha{
      background-color:rgba(83, 78, 76, 0.97);
    }
    .c-heading{
       text-align: right; 
       padding: 120px 20px; 
    }
    .c-heading-third{
      color: rgba(92, 206, 247, 0.97);
    }
    .carousel-item{
      height:500px;
    }
    .card-img-top{
      height:180px;
    }
    .hotdeal{
      color:rgba(255, 78, 24, 0.97);
    }
    .dropdown-item:hover {
      background-color: white !important;
      color: black !important;
    }
  </style>
</head>
<body class="bg-black text-white">
  <nav class="navbar navbar-dark bg-black">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      
      <a class="navbar-brand" href="main.php"><i class="bi bi-speedometer2 me-1"></i>WheelDeal</a>

    <div class="d-flex align-items-center gap-3">

    <?php if(isset($_SESSION['id'])){ ?>

      <form class="d-flex search-box" method="post" action="search1.php">
        <input name="search_key" class="form-control me-2" type="search" placeholder="Search cars..." aria-label="Search">
        <button class="btn btn-light" type="submit">Search</button>
      </form>
          <a class="home-link" href="home.php">
            <i class="bi bi-house-door-fill"></i> Home
          </a>
    <?php } ?>

      <div class="dropdown dropdown-color">
        <button class="navbar-toggler " type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="navbar-toggler-icon  "></span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end bg-black">
          <li><a class="dropdown-item text-light" href="#about"><i class="bi bi-info-circle"></i> About Us</a></li>
          <?php if(isset($_SESSION['id'])){ ?>  
          <li><a class="dropdown-item text-light" href="profile.php"><i class="bi bi-person-fill"></i> Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-warning" href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
           <?php }else{ ?>
            <li><a class="dropdown-item text-light" href="login.php"><i class="bi bi-box-arrow-in-right"></i> LogIn</a></li>
          <li><a class="dropdown-item text-light" href="signin.php"><i class="bi bi-person-plus-fill"></i> Sign Up</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
  </nav>