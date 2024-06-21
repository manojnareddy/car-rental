<?php include('includes/header.php'); ?>

<div class="py-5 bg-secondary">
    <div class="container text-center">
        <h4 class="text-white">Thank You</h4>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="card card-body shadow-sm">
            <div class="row">
                <div class="col-md-2">
                    <img src="assets/images/success.png" class="w-100" alt="Thank you" />
                </div>
                <div class="col-md-10 my-auto border-start">
                    <h4>Thank you</h4>
                    <div class="underline"></div>
                    <p>
                        <?= alertSuccess(); ?>
                    </p>
                    <div>
                        <a href="index.php" class="btn btn-primary mx-2">Back to Home manu  page</a>
                        <a href="services.php" class="btn btn-info">Our Serivces</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
