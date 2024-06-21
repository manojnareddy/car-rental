<?php include('includes/header.php'); ?>

<div class="container">
    <?= alertSuccess(); ?>
</div>

<div id="carouselExample" class="carousel slide">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/images/car1.jpg" class="d-block w-100" alt="..." width="900" height="600">
    </div>
    <div class="carousel-item">
      <img src="assets/images/car2.jpg" class="d-block w-100" alt="..." width="900" height="600">
    </div>
    <div class="carousel-item">
      <img src="assets/images/car3.jpg" class="d-block w-100" alt="..."width="900" height="600">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


<div class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4 class="main-heading">Welcome to <?= webSetting('title') ?? 'Car Rental '; ?></h4>
                <div class="underline mx-auto"></div>
                <p>
                   A car rental system is a sophisticated platform designed to streamline the entire process of vehicle rental, providing a seamless and efficient experience for both rental agencies and customers. This comprehensive system encompasses user-friendly interfaces for account creation and authentication, enabling customers to easily browse through a detailed inventory of available vehicles. The platform facilitates effortless reservations, allowing users to check vehicle availability, select rental dates, and make secure online bookings with integrated payment options. Beyond the reservation phase, the system incorporates features for generating digital rental agreements, tracking the real-time status and location of vehicles within the fleet, and sending automated notifications to customers. With capabilities for rate management, invoicing, and reporting, the system ensures accurate billing and provides valuable insights into rental patterns and fleet utilization. Additionally, integration with external systems, such as GPS tracking and maintenance scheduling, enhances overall operational efficiency. Through its user-centric design and robust security measures, a car rental system contributes to a more streamlined and customer-focused rental process.
                </p>
            </div>
        </div>
    </div>
</div>


<div class="bg-car1">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2 class="text-white">Rent a car in few steps</h2>
                <div class="underline"></div>
                <h4 class="text-white">Drive worry free with our <?= webSetting('title') ?? 'Car Rental Services'; ?></h4>
                <h4 class="text-white"> More Than a Rental – It's Your Key to Adventure.</h4>
            </div>
            <div class="col-md-4 my-auto">
                <a href="cars.php" class="btn btn-web text-white w-100 fw-bold">Book a car now !</a>
            </div>
        </div>
    </div>
</div>

<?php
// Fetch feedback data
$feedbackQuery = "SELECT * FROM feedback";
$feedbackResult = $conn->query($feedbackQuery);
?>

<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4 class="main-heading text-center">Our Testimonials</h4>
                <div class="underline mx-auto"></div>

                <div class="owl-carousel owl-theme car-testi">
                    <?php
                    if ($feedbackResult->num_rows > 0) {
                        while ($row = $feedbackResult->fetch_assoc()) {
                    ?>
                            <div class="item">
                                <div class="testi-card text-center">
                                    <h4 class="title1 fs-16 mb-2"><?= $row['name']; ?></h4>
                                    <p class="testi-para"><?= $row['comment']; ?></p>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>No testimonials available.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
