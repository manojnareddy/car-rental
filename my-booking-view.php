<?php 
$pageTitle = "Booking View";
include('includes/header.php'); 

require 'auth-user.php'; 
?>


<div class="banner py-4">
    <div class="container">
        <h4 class="banner-heading mb-3">Booking View</h4>
    </div>
</div>



<div class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">

        <?php 
            if(isset($_GET['booking-id']))
            {
                if($_GET['booking-id'] != ''){
                    $bookingId = validate($_GET['booking-id']);
                }else{
                    return false;
                }
            }
            else
            {
                ?>
                <div class="col-md-12">
                    <h4>No Booking Id Found</h4>
                </div>
                <?php
                return false;
            }
        ?>

        <?php
        $userId = validate($_SESSION['loggedInUser']['user_id']);

        $bookingQuery = "SELECT * FROM bookings WHERE booking_no='$bookingId' AND user_id='$userId' LIMIT 1";//getById('bookings',$bookingId);
        $booking = mysqli_query($conn, $bookingQuery);

        if($booking)
        {
            if(mysqli_num_rows($booking) > 0)
            {
                $booking = mysqli_fetch_array($booking, MYSQLI_ASSOC);
                $carId = $booking['car_id'];
                $car = getById('cars',$carId);
                
                $bodyTypeId = $car['data']['body_type_id'];
                
                $bodyType = getById('body_types',$bodyTypeId);
                
        ?>

            <div class="col-md-12 mb-4">
                <a href="my-bookings.php" class="btn btn-danger float-end">Back</a>
            </div>

            <div class="col-md-8 mb-3">
                <div class="card shadow rounded-4 mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Booking Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> Booking Id: <span class="text-dark fw-bold"> <?=$booking['booking_no'];?></span> </p>
                            </div>
                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> Booking Date: <span class="text-dark fw-bold"> <?=$booking['booking_date'];?></span> </p>
                            </div>
                            <div class="col-md-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> Start Time: <span class="text-dark fw-bold"> <?=$booking['start_date'];?></span> </p>
                            </div>
                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> End Time: <span class="text-dark fw-bold"> <?=$booking['end_date'];?></span> </p>
                            </div>
                            <div class="col-md-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-md-12">
                                <p class="title-sm fs-14 mb-1"> Total Price: <span class="text-dark fw-bold"> <?=$booking['total_price'];?></span> </p>
                            </div>
                            <div class="col-md-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> Payment Mode: <span class="text-dark fw-bold"> <?=$booking['payment_mode'];?></span> </p>
                            </div>
                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> Payment Id: <span class="text-dark fw-bold"> <?=$booking['payment_id'];?></span> </p>
                            </div>
                            <div class="col-md-12">
                                <hr class="my-2">
                            </div>

                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> 
                                    Payment Status: <br/>
                                    <?php
                                    if($booking['payment_status'] == 'completed'){
                                        echo '<span class="btn btn-success btn-sm text-white py-1 fs-14 fw-bold">Completed</span>';
                                    }elseif($booking['booking_status'] == 'pending'){
                                        echo '<span class="btn btn-danger text-white py-1 fs-14 fw-bold">Pending</span>';
                                    }else{
                                        echo '<span class="btn btn-info py-1 text-white fs-14 fw-bold">Refunded</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <p class="title-sm fs-14 mb-1"> 
                                    Booking Status:  
                                    <br/>
                                    <?php
                                    if($booking['booking_status'] == 'booked'){
                                        echo '<span class="btn btn-success text-white py-1 fs-14 fw-bold">Upcoming</span>';
                                    }elseif($booking['booking_status'] == 'completed'){
                                        echo '<span class="btn btn-info text-white py-1 fs-14 fw-bold">Completed</span>';
                                    }elseif($booking['booking_status'] == 'cancelled'){
                                        echo '<span class="btn btn-danger text-white py-1 fs-14 fw-bold">Cancelled</span>';
                                    }else{
                                        echo '<span class="btn btn-warning py-1 text-white fs-14 fw-bold">Live</span>';
                                    }
                                    ?>
                                </p>
                            </div>

                            <?php if($booking['cancel_status']) : ?>
                            <div class="col-md-12">
                                <hr class="my-2">
                                <p class="title-sm fs-14 mb-1"> 
                                    Cancel Status: 
                                    <span class="badge bg-danger text-white py-1 fs-14 fw-bold">Booking Cancelled</span>
                                </p>
                                <p class="title-sm fs-14 mb-1"> 
                                    Cancel Reason:  <?=$booking['cancel_reason'];?>
                                </p>
                                <hr class="my-2">
                            </div>
                            <?php endif; ?>

                            <?php if(!$booking['cancel_status']) : ?>
                            <?php if(!round((strtotime($booking['start_date']) - strtotime(date('Y-m-d H:i')))/3600, 1) < 0) : ?>
                            <div class="col-md-12">
                                <hr class="my-2">
                                <p class="title-sm fs-14 mb-0"> Cancel Booking:  </p>
                                <label class="title-sm fs-12 mb-2">Cancellation available 24 Hours prior to trip start time</label>
                                <br/>
                                <?php 
                                $hourdiff = round((strtotime($booking['start_date']) - strtotime(date('Y-m-d H:i')))/3600, 1);
                                if($hourdiff > 24){
                                ?>
                                    <form action="code.php" method="POST">
                                        <input type="hidden" name="bookingId" value="<?= $bookingId; ?>" />
                                        <button type="submit" name="cancelCarBooking" onclick="return confirm('Are you sure you want to cancel?')" class="btn btn-danger">Cancel</button>
                                    </form>
                                <?php
                                }
                                else
                                {
                                ?>
                                    <button type="button" disabled class="btn btn-danger">Cancel</button>
                                <?php
                                }
                                ?>
                            </div>
                            
                            <?php endif; ?>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>

                <div class="card shadow rounded-4">
                    <div class="card-header">
                        <h5 class="mb-0">Car Features / Details</h5>
                    </div>
                    <div class="card-body">
                        <h5 class="car-card-title"><?=$car['data']['name'];?></h5>
                        <hr class="my-2">
                        <table class="table fs-14 table-striped table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td class="w-25">Fastag</td>
                                    <td><?=$car['data']['fastag'] == true ? 'Yes':'No';?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Sun Roof</td>
                                    <td><?=$car['data']['sun_roof'] == true ? 'Yes':'No';?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Cruise Control</td>
                                    <td><?=$car['data']['cruise_control'] == true ? 'Yes':'No';?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">360 Camera</td>
                                    <td><?=$car['data']['360_camera'] == true ? 'Yes':'No';?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Home Delivery</td>
                                    <td><?=$car['data']['home_delivery'] == true ? 'Yes':'No';?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Air Bags</td>
                                    <td><?=$car['data']['airbags'] == true ? 'Yes':'No';?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Car Name</td>
                                    <td><?=$car['data']['name'];?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Body Type</td>
                                    <td><?=$bodyType['data']['name'] ?? '-';?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Transmission</td>
                                    <td><?=$car['data']['transmission'];?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Fuel</td>
                                    <td><?=$car['data']['fuel'];?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Model</td>
                                    <td><?=$car['data']['model'];?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Car Reg No. </td>
                                    <td><?=$car['data']['car_reg_no'];?></td>
                                </tr>
                                <tr>
                                    <td class="w-25">Miles Driven </td>
                                    <td><?=$car['data']['kms_driven'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="sticky2">
                    <img src="<?=$car['data']['image'] != '' ? $car['data']['image']:"assets/images/no-img.jpg";?>" class="w-100 shadow-sm rounded-3 mb-3" alt="img" />
                </div>
            </div>
        <?php
            }
            else
            {
                ?>
                <div class="col-md-12">
                    <h4>No Booking Found</h4>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="col-md-12">
                <h4>Something Went Wrong!</h4>
            </div>
            <?php
        }
        ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
