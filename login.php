<?php 
include('includes/header.php'); 

if(isset($_SESSION['loggedIn'])){
    redirect('cars.php','Welcome Back');
}
?>

<div class="section">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary">
                        <h3 class="text-white">Login</h3>
                    </div>
                    <div class="card-body">

                        <?php alertSuccess(); ?>

                        <form action="login-code.php" method="POST">
                            <div class="mb-3">
                                <label>Enter Email Id</label>
                                <input type="email" name="email" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label>Enter Password</label>
                                <input type="password" name="password" class="form-control" required />
                            </div>
                            <div class="mb-3 mt-4">
                                <button type="submit" name="loginBtn" class="btn btn-primary w-100">Sign in</button>
                            </div>
                            <a href="register.php">new user? register now</a>
                        </form>
                    </div>
                </div>
            </div>
      
        </div>
    </div>
</div>

        

<?php include('includes/footer.php'); ?>
