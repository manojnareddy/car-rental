<?php 
include('includes/header.php'); 

if(!isset($_SESSION['start_date'])){
    redirect('cars.php','Select your pickup date');
}
?>

<div class="banner py-5">
    <div class="container text-center">
        <h4 class="banner-heading mb-3">Book your drive now!</h4>
    </div>
</div>

<div class="section bg-gray">
    <div class="container">
        <div>
            <?= alertSuccess(); ?>
        </div>
        <div class="row">
            <?php 
            if(isset($_GET['car']))
            {
                if($_GET['car'] != ''){
                    $carCid = $_GET['car'];
                    $_SESSION['carCid'] = $carCid;
                            
                }else{
                    return false;
                }
            }
            else
            {
                ?>
                <div class="col-md-12">
                    <h4>No Car Found</h4>
                </div>
                <?php
                return false;
            }
            ?>
          <?php
            $carQuery = "SELECT c.*, c.id as main_car_id, b.id, b.name as brand_name, bt.id, bt.name as body_type_name FROM cars c, brands b, body_types bt WHERE b.id=c.brand_id AND bt.id=c.body_type_id AND c.car_cid='$carCid' LIMIT 1";
            $result = mysqli_query($conn,$carQuery);
            if($result)
            {
                ?>
                <div class="col-md-8">
                <?php
                        if(mysqli_num_rows($result) == 1){

                            $car = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $carId = $car['main_car_id'];
                            $startDate = isset($_SESSION['start_date']) ? date("Y-m-d H:i:s", strtotime($_SESSION['start_date'])): date("Y-m-d H:i:s");
                            $endDate = isset($_SESSION['end_date']) ? date("Y-m-d H:i:s", strtotime($_SESSION['end_date'])):  date("Y-m-d H:i:s", strtotime("+24 hours"));

                            $query = "SELECT * FROM cars WHERE id='$carId' AND id IN (SELECT car_id FROM bookings WHERE ((start_date BETWEEN '$startDate' AND '$endDate') 
                                        OR (end_date BETWEEN '$startDate' AND '$endDate'))
                                        OR (('$startDate' > start_date) AND ('$endDate' < end_date)))";

                            $cars = mysqli_query($conn, $query);
                            if($cars)
                            {
                                if(mysqli_num_rows($cars) > 0){
                                    ?>
                                        <div class="">
                                            <h4>Oops!! This car is not available</h4>
                                            <a href="cars.php" class="btn btn-link mt-2">Checkout our latest cars</a>
                                        </div>
                                    <?php
                                    exit(0);
                                }
                            }

                            $carImageQuery = "SELECT * FROM car_images WHERE car_id='$carId' AND status=0";
                            $carImageResult = mysqli_query($conn, $carImageQuery);
                            if($carImageResult)
                            {
                                if(mysqli_num_rows($carImageResult) > 0){
                                ?>
                                <div class="card shadow-sm border mb-4">
                                    <div class="owl-carousel owl-theme car-carousel">
                                        <?php foreach($carImageResult as $carImgItem) : ?>
                                        <div class="carousel-img-box">
                                            <img src="<?=$carImgItem['image']?>" alt="" />
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php
                                }else{
                                ?>
                                <div class="card shadow-sm border mb-4">
                                    <div class="owl-carousel owl-theme car-carousel">
                                        <div class="carousel-img-box">
                                            <img src="<?=$car['image'] != '' ? $car['image']:"assets/images/no-img.jpg";?>" alt="img" />
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                            }

                            ?>
                                <div class="car-card">
                                    <div class="card-body">
                                        <h5 class="car-card-title"><?=$car['name'];?></h5>
                                        <div class="row my-2">
                                            <div class="col-md-4">
                                                <p class="title-sm fs-12 mb-1"> Brand: <span class="text-dark fw-bold"><?=$car['brand_name'];?></span></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="title-sm fs-12 mb-1"> Body Type: <span class="text-dark fw-bold"> <?=$car['body_type_name'];?></span> </p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="title-sm fs-12 mb-1"> Transmission: <span class="text-dark fw-bold"> <?=$car['transmission'];?></span> </p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="title-sm fs-12 mb-1"> Fuel: <span class="text-dark fw-bold"> <?=$car['fuel'];?></span> </p>
                                            </div>
                                           
                                            <div class="col-md-4">
                                                <p class="title-sm fs-12 mb-1"> kms Driven: <span class="text-dark fw-bold"> <?=$car['kms_driven'];?></span> </p>
                                            </div>
                                        </div>
                                        
                                        <hr class="my-3">

                                        <div class="mb-2">
                                            <h5 class="car-card-title mb-2">Booking time</h5>
                                            
                                            <div class="row">
                                                <small class="title-sm fs-11">Pickup Time</small>
                                                <div class="col-md-3 text-start">
                                                    <h5 class="tet-dark fs-13">
                                                        <?= isset($_SESSION['start_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['start_date'])): "Something went wrong" ?>
                                                    </h5>
                                                </div>
                                                
                                                <small class="title-sm fs-11">Dropoff Time</small>
                                                <div class="col-md-3 text-start">
                                                    <h5 class="tet-dark fs-13">
                                                        <?= isset($_SESSION['end_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['end_date'])): "Something went wrong" ?>
                                                    </h5>
                                                </div>
                                            </div>
                                            <small class="fs-11 title-sm">Note: Time will be rounded off to the lower hour</small>

                                        </div>

                                        <hr class="my-3">

                                        <div class="mb-3">
                                            <h5 class="title1 mb-2">About the car</h5>
                                            <p class="fs-13"><?=$car['description'];?></p>
                                        </div>
                                        <div class="mb-0">
                                            <h5 class="title1 mb-2">Car Features / Details</h5>
                                            <table class="table fs-14 table-striped table-bordered mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="w-25">Sun Roof</td>
                                                        <td><?=$car['sun_roof'] == true ? 'Yes':'No';?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">Cruise Control</td>
                                                        <td><?=$car['cruise_control'] == true ? 'Yes':'No';?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">360 Camera</td>
                                                        <td><?=$car['360_camera'] == true ? 'Yes':'No';?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="w-25">Air Bags</td>
                                                        <td><?=$car['airbags'] == true ? 'Yes':'No';?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">Car Name</td>
                                                        <td><?=$car['name'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">Brand</td>
                                                        <td><?=$car['brand_name'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">Body Type</td>
                                                        <td><?=$car['body_type_name'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">Transmission</td>
                                                        <td><?=$car['transmission'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">Fuel</td>
                                                        <td><?=$car['fuel'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">Model</td>
                                                        <td><?=$car['model'];?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="w-25">Miles Driven </td>
                                                        <td><?=$car['kms_driven'];?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            <?php
                        }else{
                            ?>
                            <div class="mb-3">
                                <h4>No Car Found</h4>
                            </div>
                            <?php
                        }
                ?>
                </div>
                <div class="col-md-4">
                    <div class="car-card sticky2">
                        <div class="mb-3 p-3">
                        <?php 
                            if(!isset($_SESSION['loggedIn'])){
                                ?>
                                    <label class="title-sm fs-12">Log in to see the final fare and offers.</label>
                                <?php 
                            }
                            else
                            {
                                ?>
                                    <label class="title-sm fs-12">Book your favorite car now before someone else does.</label>
                                <?php 
                            }
                        ?>

                            <hr class="my-3">

                            <small class="title-sm fs-11">Pricing from</small>
                            <h5 class="title1 fs-16 mb-1">Per Day <span class="float-end">$ <?=$car['price_per_hour'];?>/hr</span></h5>
                            <h5 class="title1 fs-16 mb-1">Service Fee <span class="float-end">$  30</span></h5>
                            <?php 
                                $datetime1 = isset($_SESSION['start_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['start_date'])): date("d-m-Y h:00 A");
                                $datetime2 = isset($_SESSION['end_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['end_date'])): date("d-m-Y h:00 A");

                                $starttimestamp = strtotime($datetime1);
                                $endtimestamp = strtotime($datetime2);
                                $difference_hours = abs($endtimestamp - $starttimestamp)/ 3600;
                                $difference = ceil($difference_hours / 24);

                                $serviceFee = 30;
                            ?>
                            
                            <h5 class="title1 mb-1">Total Days <span class="float-end"> <?= $difference; ?></span></h5>
                            <hr class="my-3">
                            <h5 class="title1 mb-1">Total Price <span class="float-end"> $ <?= ($difference * $car['price_per_hour']) + $serviceFee; ?></span></h5>

                            <a href="booking.php" class="btn btn-web w-100">CONTINUE</a>
                        </div>
                    </div>
                </div>
            <?php
                }else{
            ?>
                <div class="col-md-12 mb-3">
                    <h4>No cars available</h4>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
