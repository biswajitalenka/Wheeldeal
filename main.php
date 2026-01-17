<?php include_once "navbar.php" ?>

<div id="carouselExampleCaptions" class="carousel slide mt-4" data-bs-ride="carousel">

  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="./Images/car1.jpg" class="d-block w-100" alt="Car Image 1">
      <div class="carousel-caption d-none d-md-block">
        <div class="c-heading bg-dark bg-opacity-50 p-3 rounded">
          <h5>Drive Smart. Buy Safe. Sell Easy.</h5>
          <p>Let us find the perfect car for you today</p>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <img src="./Images/car26.jpg" class="d-block w-100" alt="Car Image 2">
      <div class="carousel-caption d-none d-md-block">
        <div class="c-heading bg-dark bg-opacity-50 p-3 rounded">
          <h6>Because Every Ride Deserves a</h6>
          <h4>Second Chance.</h4>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <img src="./Images/car3.jpg" class="d-block w-100" alt="Car Image 3">
      <div class="carousel-caption d-none d-md-block">
        <div class="c-heading bg-dark bg-opacity-50 p-3 rounded">
          <h6>Where Every Deal Is a</h6>
          <h3>WheelDeal!</h3>
        </div>
      </div>
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<?php include_once "footer.php";
?>