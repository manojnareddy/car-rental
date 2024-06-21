<?php 
include('includes/header.php'); 

require 'auth-user.php'; 
?>


<div class="banner py-4">
    <div class="container">
        <h4 class="banner-heading mb-3">My Bookings</h4>
    </div>
</div>

<div class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <?= alertSuccess(); ?>
            </div>

        <?php 

            $userId = $_SESSION['loggedInUser']['user_id'];

            $query = "SELECT * FROM bookings WHERE user_id='$userId' ";
            $bookings = mysqli_query($conn, $query);

            if($bookings)
            {
                if(mysqli_num_rows($bookings) > 0)
                {
                    foreach ($bookings as $item) {
                        $car = getById('cars', $item['car_id']);

                        ?>
                            <div class="col-md-12 mb-3">
                                <div class="card shadow rounded-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="<?= $car['data']['image']; ?>" class="w-100 rounded-4 border shadow-sm" alt="Img" />
                                            </div>
                                            <div class="col-md-8">
                                                <p class="title-sm fs-16 mb-1"> Booking Id: <span class="text-dark fw-bold"> <?=$item['booking_no'];?></span> </p>
                                                <p class="title-sm fs-16 mb-1"> Start Time: <span class="text-dark fw-bold"> <?=$item['start_date'];?></span> </p>
                                                <p class="title-sm fs-16 mb-1"> End Time: <span class="text-dark fw-bold"> <?=$item['end_date'];?></span> </p>
                                                <hr/>
                                                <p class="title-sm fs-16 mb-1"> 
                                                    Booking Status:  <br/>
                                                    <?php
                                                    if($item['booking_status'] == 'booked'){
                                                        echo '<span class="btn btn-success text-white py-1 fs-14 fw-bold">Upcoming</span>';
                                                    }elseif($item['booking_status'] == 'completed'){
                                                        echo '<span class="btn btn-info text-white py-1 fs-14 fw-bold">Completed</span>';
                                                    }elseif($item['booking_status'] == 'cancelled'){
                                                        echo '<span class="btn btn-danger text-white py-1 fs-14 fw-bold">Cancelled</span>';
                                                    }else{
                                                        echo '<span class="btn btn-warning py-1 text-white fs-14 fw-bold">Live</span>';
                                                    }
                                                    ?>
                                                    <a href="my-booking-view.php?booking-id=<?= $item['booking_no']; ?>" class="btn btn-primary text-white float-end">View</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                }else{
                    ?>
                        <div class="card card-body text-center">
                            <h5>No bookings yet</h5>
                        </div>
                    <?php
                }
            }else{
                ?>
                    <div class="card card-body text-center">
                        <h5>Something went wrong</h5>
                    </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>
