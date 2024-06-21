<?php

if(!isset($_SESSION['loggedIn'])){

    redirect('login.php','Login to Continue...');
}

?>
