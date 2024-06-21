<?php

require 'config/function.php';

if(isset($_POST['loginBtn']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    if($email != '' && $password != '')
    {
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){

            if(mysqli_num_rows($result) == 1){

                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
               

                if($row['role'] == 'admin'){

                    if($row['is_ban'] == 1){
                        redirect('login.php','You account has been banned. Contact your Admin');
                    }

                    $_SESSION['loggedIn'] = true;
                    $_SESSION['loggedInUserRole'] = $row['role'];
                    $_SESSION['loggedInUser'] = [
                        'user_id' => $row['id'], 
                        'name' => $row['name'], 
                        'email' => $row['email'],
                        'phone' => $row['phone']
                    ];
                    
                    redirect('admin/dashboard.php','Logged In Successfully');

                }elseif($row['role'] == 'user'){

                    if($row['is_ban'] == 1){
                        redirect('login.php','You account has been banned. Contact your Admin');
                    }

                    $_SESSION['loggedIn'] = true;
                    $_SESSION['loggedInUserRole'] = $row['role'];
                    $_SESSION['loggedInUser'] = [
                        'user_id' => $row['id'], 
                        'name' => $row['name'], 
                        'email' => $row['email'],
                        'phone' => $row['phone']
                    ];

                    if(isset($_SESSION['carCid'])){

                        $carCid = $_SESSION['carCid'];
                        redirect('car.php?car='.$carCid,'Login Successful');
                    }else{

                        redirect('cars.php','Login Successful');
                    }
                }

            }else{

                redirect('login.php','Invalid Email Id or Password');
            }
        }else{
            
            redirect('login.php','Something Went Wrong!');
        }
    }
    else
    {
        redirect('login.php','All Fields are mandetory');
    }
}

?>
