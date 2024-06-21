<?php 
include('includes/header.php'); 

// if(!isset($_SESSION['start_date'])){

//     $_SESSION['start_date'] = isset($_GET['start']) ? $_GET['start']: date('Y-m-d H:i'); 
// }else{
$_SESSION['start_date'] = isset($_GET['start']) ? $_GET['start']: date('Y-m-d H:i'); 
// }

// if(!isset($_SESSION['end_date'])){

$_SESSION['end_date'] = isset($_GET['end']) ? $_GET['end']: date('Y-m-d H:i', strtotime("+24 hours"));
// }
?>

<div class="banner py-5">
    <div class="container">
        <h4 class="banner-heading text-center mb-3">The perfect car for your next trip is just around the corner</h4>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="title-sm fs-12">Select start date</label> <br>
                            <input type="datetime-local" value="<?= $_SESSION['start_date']; ?>" min="<?= date('Y-m-d H:i') ?>" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="col-md-4">
                            <label class="title-sm fs-12">Select end date</label>
                            <input type="datetime-local" value="<?= $_SESSION['end_date']; ?>" name="end_date"  class="form-control" id="end_date">
                        </div>
                        <div class="col-md-4">
                            <br/>
                            <button class="btn btn-web filtercar w-100" type="button">Search</button>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <?php alertSuccess(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section bg-gray" id="content">
    <div class="container">
       
        <div class="row">

            <div class="col-md-3">
                <div class="card filterBox border-0 mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"> Filter Car
                            <button class="btn btn-primary float-end d-md-none d-inline-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilterMobile">
                                <span class="fa fa-bars"></span>
                            </button>
                        </h5>
                    </div>
                    <div class="collapse dont-collapse-sm card-body" id="collapseFilterMobile">

                        <?php include('filter.php'); ?>

                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                <?php
                    
                    $fuel = isset($_GET['fuel']) ? $_GET['fuel'] :"";
                    $transmission = isset($_GET['transmission']) ? $_GET['transmission'] :"";

                    
                    $sun_roof = isset($_GET['sun_roof']) ? $_GET['sun_roof'] :"";
                    $cruise_control = isset($_GET['cruise_control']) ? $_GET['cruise_control'] :"";
                    $camera_360 = isset($_GET['360_camera']) ? $_GET['360_camera'] :"";
                    
                    $airbags = isset($_GET['airbags']) ? $_GET['airbags'] :"";

                    $newBrandId = $brandId > 0 ? "='$brandId'": "LIKE '%'";
                    $newBodyTypeId = $bodyTypeId > 0 ? "='$bodyTypeId'": "LIKE '%'";

                    $newTransmission = $transmission ? "='$transmission'": "LIKE '%'";
                    $newFuel = $fuel ? "='$fuel'": "LIKE '%'";

                    
                    $new_sun_roof = $sun_roof ? "='1'": "LIKE '%'";
                    $new_cruise_control = $cruise_control ? "='1'": "LIKE '%'";
                    $newCamera_360 = $camera_360 ? "='1'": "LIKE '%'";
                    
                    $new_airbags = $airbags ? "='1'": "LIKE '%'";

                    
                    $selectedStartDate = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d h:i A');
                    $selectedEndDate = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d h:i A', strtotime("+24 hours"));

                    $mystrDate = date("Y-m-d H:i:s", strtotime($selectedStartDate));
                    $myendDate = date("Y-m-d H:i:s", strtotime($selectedEndDate));
                    
                    // $query = "SELECT * FROM cars c WHERE id NOT IN (SELECT car_id FROM bookings WHERE (start_date BETWEEN '$mystrDate' AND '$myendDate') OR (end_date BETWEEN '$mystrDate' AND '$myendDate'))
                    $query = "SELECT * FROM cars c WHERE id NOT IN (
                                    SELECT car_id FROM bookings WHERE ((start_date BETWEEN '$mystrDate' AND '$myendDate') OR (end_date BETWEEN '$mystrDate' AND '$myendDate'))
                                    OR (('$mystrDate' > start_date) AND ('$myendDate' < end_date)))
                                    AND c.brand_id $newBrandId 
                                    AND c.transmission $newTransmission 
                                    AND c.body_type_id $newBodyTypeId 
                                    AND c.fuel $newFuel 
                                    
                                    AND c.sun_roof $new_sun_roof 
                                    AND c.cruise_control $new_cruise_control 
                                    AND c.360_camera $newCamera_360 
                                   
                                    AND c.airbags $new_airbags
                                    GROUP BY c.id
                                    ";

                    $cars = mysqli_query($conn, $query);
                    if($cars)
                    {
                        if(mysqli_num_rows($cars) > 0){
                            foreach ($cars as $item) {
                                ?>
                                <div class="col-md-4 mb-3">

                                    <div class="car-card">
                                        <div class="car-card-box">
                                            <a href="car.php?car=<?=$item['car_cid'];?>">
                                                <img src="<?=$item['image'] != '' ? $item['image']:"assets/images/no-img.jpg";?>" alt="img" />
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <a href="car.php?car=<?=$item['car_cid'];?>">
                                                <h5 class="car-card-title"><?=$item['name'];?></h5>
                                            </a>
                                            <div class="mb-1">
                                                <label class="title-sm fs-12"><?=$item['transmission'];?></label> - 
                                                <label class="title-sm fs-12"><?=$item['fuel'];?></label> - 
                                                <label class="title-sm fs-12"><?=$item['seating_capacity'];?> Seats</label>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <small class="title-sm fs-11">Pricing from</small>
                                                    <h5 class="title1">$ <?=$item['price_per_hour'];?> /Day</h5>
                                                </div>
                                                <div class="col-md-4 my-auto">
                                                    <a href="car.php?car=<?=$item['car_cid'];?>" class="btn btn-sm bg-white text-dark w-100 btn-outline-secondary">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <?php
                            }
                        }else{
                            ?>
                            <div class="col-md-12">
                                <h4>No cars Available</h4>
                            </div>
                            <?php
                        }
                    }else{
                        ?>
                        <div class="col-md-12">
                            <h4>No cars Available</h4>
                        </div>
                        <?php
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
