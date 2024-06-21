<?php
require 'config/function.php';


    $datetime1 = isset($_SESSION['start_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['start_date'])): date("d-m-Y h:00 A");
    $datetime2 = isset($_SESSION['end_date']) ? date("d-m-Y h:00 A", strtotime($_SESSION['end_date'])): date("d-m-Y h:00 A");

    $starttimestamp = strtotime($datetime1);
    $endtimestamp = strtotime($datetime2);
    $difference = abs($endtimestamp - $starttimestamp)/3600;

    $convenienceFee = 99;
    $cardCid = isset($_SESSION['carCid']) ? $_SESSION['carCid']: 0;

    $query = "SELECT id,price_per_hour FROM cars WHERE car_cid='$cardCid' LIMIT 1";
    $query_run = mysqli_query($conn, $query);
    
    $carId = 0;
    if(mysqli_num_rows($query_run) > 0){
        $data = mysqli_fetch_array($query_run, MYSQLI_ASSOC);
        $pricePerHour = $data['price_per_hour'];
        $carId = $data['id'];
    }else{
        // redirect("cars.php", "Something went wrong!!");
        echo 500;
    }
    $finalPrice = (($difference * $pricePerHour) + $convenienceFee) * 100;

    if(isset($_POST['razorpay_btn']))
    {

        $data = array('amount' => $finalPrice, 'currency' => "INR");                                                                    
        $data_string = json_encode($data);   

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, "rzp_test_bIWLpl8yJY5O9Q:2pEafOPMzO23Srle1iXDXl0F");
        curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            exit(0);
        }
        curl_close($ch);
        $razorpayResponse = json_decode($result,true);
        $mydata = [
            'status' => 200,
            'order_id' => $razorpayResponse['id'], 
            'total_amount' => $finalPrice,
            'username' => $_SESSION['loggedInUser']['name'],
            'email' => $_SESSION['loggedInUser']['email'],
            'phone' => $_SESSION['loggedInUser']['phone']
        ];
      
        echo json_encode($mydata);
    }
    
    if(isset($_POST['payment_success'])){
        if(isset($_POST['payment_id']) && $_POST['payment_id'] != ""){

            $userId = $_SESSION['loggedInUser']['user_id'];
            $todayDate = date('Y-m-d');
            $paymentId = $_POST['payment_id'];
            $paymentMode = $_POST['payment_mode'];

            $newStartdate = date("Y-m-d H:00:s", strtotime($_SESSION['start_date']));
            $newEnddate = date("Y-m-d H:00:s", strtotime($_SESSION['end_date']));

            function generateRandomString($length = 6) {
                return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
            }

            $totalPrice = (($difference * $pricePerHour) + $convenienceFee);
            $bookingNo = "CR".generateRandomString().rand(1111,9999);
            $query = "INSERT INTO bookings (booking_no, user_id, car_id, start_date, end_date, total_price, booking_date, payment_mode, payment_status, payment_id, booking_status) VALUES 
                                        ('$bookingNo' ,'$userId', '$carId', '$newStartdate', '$newEnddate', '$totalPrice', '$todayDate', '$paymentMode', 'completed', '$paymentId', 'booked')";
            $query_run = mysqli_query($conn, $query);

            if($query_run)
            {
                $mydata = [
                    'status' => 200,
                    'message' => "Order Placed Successfully", 
                ];
              
            }else{
                $mydata = [
                    'status' => 500,
                    'message' => "Something Went Wrong!", 
                ];
            }
            echo json_encode($mydata);
        }else{
            $mydata = [
                'status' => 500,
                'message' => "Something Went Wrong Outside!", 
            ]; 
            echo json_encode($mydata);
        }
    }
?>