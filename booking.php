<?php 
include('includes/header.php'); 

require 'auth-user.php';

$carCid = mysqli_real_escape_string($conn, $_SESSION['carCid']) ?? "";
?>

<div class="banner py-4">
    <div class="container">
        <h4 class="banner-heading mb-3">Booking Summary</h4>
    </div>
</div>

<?php 

$userId = $_SESSION['loggedInUser']['user_id'];

$checkUserVerifiedQuery = "SELECT * FROM users WHERE id='$userId' ";
$userResult = mysqli_query($conn,$checkUserVerifiedQuery);
if($userResult)
{
    if(mysqli_num_rows($userResult) == 1){

        $userData = mysqli_fetch_array($userResult, MYSQLI_ASSOC);
        
            ?>
                <div class="section bg-light">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-7">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-web">
                                        <h4 class="title1 mb-0 text-white">Booking Details</h4>
                                    </div>
                                    <div class="card-body">
                                    <?php

                                        $carQuery = "SELECT c.*, b.id, b.name as brand_name, bt.id, bt.name as body_type_name FROM cars c, brands b, body_types bt 
                                            WHERE b.id=c.brand_id AND bt.id=c.body_type_id AND c.car_cid='$carCid' LIMIT 1";
                                        $result = mysqli_query($conn,$carQuery);
                                        if($result)
                                        {
                                            if(mysqli_num_rows($result) == 1){

                                                $car = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                                ?>
                                                    <h5 class="car-card-title"><?=$car['name'];?></h5>
                                                    <hr class="my-2">
                                                    <p class="title-sm fs-12 mb-1"> Body Type: <span class="text-dark fw-bold"> <?=$car['body_type_name'];?></span> </p>
                                                    <p class="title-sm fs-12 mb-1"> Transmission: <span class="text-dark fw-bold"> <?=$car['transmission'];?></span> </p>
                                                    <p class="title-sm fs-12 mb-1"> Fuel: <span class="text-dark fw-bold"> <?=$car['fuel'];?></span> </p>

                                                    <hr class="my-2">

                                                    <p class="title-sm fs-12 mb-1"> 
                                                        Start Time: 
                                                        <span class="text-dark fw-bold">  <?= isset($_SESSION['start_date']) ? date("D d, M Y, h:00 A", strtotime($_SESSION['start_date'])): "Something went wrong" ?></span> 
                                                    </p>
                                                    <p class="title-sm fs-12 mb-1"> 
                                                        End Time: 
                                                        <span class="text-dark fw-bold">  <?= isset($_SESSION['end_date']) ? date("D d, M Y, h:00 A", strtotime($_SESSION['end_date'])): "Something went wrong" ?></span> 
                                                    </p>

                                                    <hr class="my-2">

                                                    <?php 
                                                        $datetime1 = isset($_SESSION['start_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['start_date'])): date("d-m-Y h:00 A");
                                                        $datetime2 = isset($_SESSION['end_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['end_date'])): date("d-m-Y h:00 A");

                                                        $starttimestamp = strtotime($datetime1);
                                                        $endtimestamp = strtotime($datetime2);
                                                        $difference_hours = abs($endtimestamp - $starttimestamp)/ 3600;
                                                        $difference = ceil($difference_hours / 24);

                                                        $convenienceFee = 30;
                                                        $totalPrice = ($difference * $car['price_per_hour']) + $convenienceFee;
                                                    ?>
                                                        
                                                    <h5 class="title1 mb-1">Total Price 
                                                        <span class="float-end"> 
                                                            $ <?= number_format($totalPrice,0); ?>
                                                        </span>
                                                    </h5>
                                                    <hr class="my-2">

                                                    <label>Pay now</label>
                                                    <br/>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                         
                                                            <button class="btn btn-primary razPayBTn -100 ab-3" onclick="showCardPaymentForm()"> <i class="fa fa-rupee me-1"></i> Pay with Card</button>
                                                            <div id="card-payment-form" style="display: none; background-color: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 400px; width: 100%; text-align: center; margin-top: 10px;">
                                                                <h2 style="margin-bottom: 20px;">Card Payment</h2>
                                                                <label for="cardNumber">Card Number:</label>
                                                                <input type="text" id="cardNumber" placeholder="Enter card number" maxlength="19" required style="width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">

                                                                <label for="expiryDate">Expiry Date:</label>
                                                                <input type="text" id="expiryDate" placeholder="MM/YY" maxlength="5" required style="width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">

                                                                <label for="cvv">CVV:</label>
                                                                <input type="text" id="cvv" placeholder="Enter CVV" maxlength="3" required style="width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">

                                                                <button id="submitCardPayment" style="background-color: #4caf50; color: #fff; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Submit Payment</button>
                                                            </div>
                                                            <div id="paypal-button-container"></div>
                                                                
                                                        </div>
                                                    </div>
                                                    
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <h4>No Car Found</h4>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <h4>No Car Found</h4>
                                            <?php
                                        }
                                    
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <img src="assets/images/booking.jpg" class="w-100" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
        
    }
}
        ?>


<?php include('includes/footer.php'); ?>


<script>
function showCardPaymentForm() {
    document.getElementById('card-payment-form').style.display = 'block';
}

document.getElementById('submitCardPayment').addEventListener('click', function () {
    var cardNumber = document.getElementById('cardNumber').value;
    var expiryDate = document.getElementById('expiryDate').value;
    var cvv = document.getElementById('cvv').value;

    // Validate expiration date format (MM/YY) and values
    var isValidExpiryDate = /^\d{2}\/\d{2}$/.test(expiryDate);

    if (isValidExpiryDate) {
        var month = parseInt(expiryDate.slice(0, 2), 10);
        var year = parseInt(expiryDate.slice(3), 10);

        // Validate month to be between 1 and 12
        isValidExpiryDate = month >= 1 && month <= 12;

        // Validate year to be between 20 and 40
        isValidExpiryDate = isValidExpiryDate && year >= 23 && year <= 40;
    }

    // Add your validation logic here
    if (
        cardNumber.length === 19 &&
        isValidExpiryDate &&
        cvv.length === 3
    ) {
        // Simulate payment success (replace this with your actual payment processing logic)
        // Generate a random 6-digit payment ID
        var paymentId = Math.floor(100000 + Math.random() * 900000);

        // Send an AJAX request to order-create.php
        $.ajax({
            method: "POST",
            url: "order-create.php",
            data: {
                'payment_success': true,
                'payment_mode': 'Paid By Card',
                'payment_id': paymentId
            },
            success: function (response) {
                var jsonNewResponse = JSON.parse(response);

                if (jsonNewResponse.status == 200) {
                    // Redirect to my-bookings.php after successful payment
                    window.location.href = 'my-bookings.php';
                } else if (jsonNewResponse.status == 500) {
                    alert(jsonNewResponse.message);
                } else {
                    alert("Something went wrong");
                }
            }
        });
    } else {
        alert('Please fill in all required fields with valid information.');
    }
});

// Allow only numeric input for the card number
document.getElementById('cardNumber').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');

    var cardNumberInput = this.value;
    if (cardNumberInput.length > 0) {
        var formattedCardNumber = cardNumberInput.match(/.{1,4}/g).join(' ');
        this.value = formattedCardNumber;
    }
});

// Allow only numeric input for the expiry date
document.getElementById('expiryDate').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, ''); // Remove non-numeric characters

    var expiryDateInput = this.value;
    if (expiryDateInput.length > 2) {
        this.value = expiryDateInput.slice(0, 2) + '/' + expiryDateInput.slice(2);
    }
});

// Allow only numeric input for the CVV
document.getElementById('cvv').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
});
</script>












