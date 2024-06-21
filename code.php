<?php

require 'config/function.php';


function uploadFileFromRootDirectory($directory,$imageFile) 
{
    $allowedFileTypes = ["jpg","png","jpeg"];
    $imageFileType = strtolower(pathinfo($imageFile['name'],PATHINFO_EXTENSION));
    if(!in_array($imageFileType, $allowedFileTypes)) {
        redirect('users-create.php','Sorry, only JPG, JPEG, PNG & webp files are allowed');
        return false;
    }

    $path = "assets/uploads/$directory/"; /** Path for Uploading your Image **/
    $image_extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION); /** Image Extension **/
    $filename = rand(1111,9999).time().'.'.$image_extension; /** Renaming the Image **/
    move_uploaded_file($imageFile['tmp_name'], $path."/".$filename);
    
    return $filename;
}


if(isset($_POST['enquiryBtn']))
{
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $status = 'pending';

    $query = "INSERT INTO enquiries (name,phone,email,service,comment,status) 
                VALUES ('$name','$phone','$email','$service','$comment','$status')";
    $result = mysqli_query($conn, $query);

    if($result){
        redirect('thank-you.php','Thank you for enquiry. Our team will get back to your as soon as possible.');
    }else{
        redirect('thank-you.php','Something Went Wrong!');
    }
}

if(isset($_POST['registerBtn']))
{
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = 'user';

    if($name != '' || $email != '' || $password != ''){

        $query = "INSERT INTO users (name,email,password,phone,role) VALUES ('$name','$email','$password','$phone','$role')";
        $result = mysqli_query($conn, $query);

        if($result){
            redirect('login.php','Registration Successfull. Login to continue');
        }else{
            redirect('register.php','Something Went Wrong!');
        }

    }else{
        redirect('register.php','Please fill required fields!');
    }

   
}


if(isset($_POST['updateProfile']))
{
    
    $userId = $_SESSION['loggedInUser']['user_id'];

    $userData = getById('users',$userId);
    if($userData['status'] != 200){
        redirect('profile.php','Something went wrong! Contact support');
    }
    
    $isVerifiedStatus = $userData['data']['is_verified'];

    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $alt_phone = validate($_POST['alt_phone']);
    $address = validate($_POST['address']);

    $address = validate($_POST['address']);
    $dl_number = validate($_POST['dl_number']);

    if($_FILES['dl_image_front']['size'] > 0)
    {
        $image = $_FILES['dl_image_front'];

        $fileResult = uploadFileFromRootDirectory('dl-docs',$image);
        $dl_image_front = 'assets/uploads/dl-docs/'.$fileResult;

        $deleteImage = $userData['data']['dl_image_front'];
        if(file_exists($deleteImage) && $userData['data']['dl_image_front'] != ""){
            unlink($deleteImage);
        }

        $isVerifiedStatus = 0;
    }
    else{
        $dl_image_front = $userData['data']['dl_image_front'];
    }

    if($_FILES['dl_image_back']['size'] > 0)
    {
        $image = $_FILES['dl_image_back'];
        $fileResult = uploadFileFromRootDirectory('dl-docs',$image);
        $dl_image_back = 'assets/uploads/dl-docs/'.$fileResult;

        $deleteImage = $userData['data']['dl_image_back'];
        if(file_exists($deleteImage) && $userData['data']['dl_image_back'] != ""){
            unlink($deleteImage);
        }

        $isVerifiedStatus = 0;
    }
    else{
        $dl_image_back = $userData['data']['dl_image_back'];
    }

    $id_proof_type = validate($_POST['id_proof_type']);
    $id_proof_number = validate($_POST['id_proof_number']);

    if($name != '' || $phone != '' || $address != ''){

        $dlCheckQuery = "SELECT * FROM users WHERE id != '$userId' AND (dl_number='$dl_number' OR (id_proof_type='$id_proof_type' AND id_proof_number='$id_proof_number'))";
        $checkResult = mysqli_query($conn, $dlCheckQuery);
        if($checkResult){
            if(mysqli_num_rows($checkResult) > 0){
                redirect('profile.php','Documents Already used by another user');
            }
        }else{
            redirect('profile.php','Something Went Wrong');
        }


        $query = "UPDATE users SET name='$name', 
                    phone='$phone',
                    alt_phone='$alt_phone',
                    address='$address',
                    dl_number='$dl_number',
                    dl_image_front='$dl_image_front',
                    dl_image_back='$dl_image_back',
                    id_proof_type='$id_proof_type',
                    id_proof_number='$id_proof_number',
                    is_verified='$isVerifiedStatus'
                    WHERE id='$userId'";

        $result = mysqli_query($conn, $query);

        if($result){
            redirect('profile.php','Profile Updated Successfully! We will review and verify your profile soon.');
        }else{
            redirect('profile.php','Something Went Wrong');
        }
    }else{
        redirect('profile.php','Please fill required fields!');
    }

}

if(isset($_POST['cancelCarBooking']))
{
    $cancel_status = "1";
    $booking_status = "cancelled";
    $cancel_reason = "Cancelled By User";

    $bookingId = validate($_POST['bookingId']);
    $userId = validate($_SESSION['loggedInUser']['user_id']);

    $query = "UPDATE bookings SET 
        cancel_status='$cancel_status', 
        cancel_reason='$cancel_reason', 
        booking_status='$booking_status'
        WHERE user_id='$userId' AND booking_no='$bookingId' ";
    $result = mysqli_query($conn, $query);

    if($result){
        redirect('my-bookings.php','Booking Trip Cancelled');
    }else{
        redirect('my-bookings.php','Something Went Wrong');
    }
}

?>