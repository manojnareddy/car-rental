<?php include('includes/header.php'); ?>

<?php

    if(isset($_GET['slug'])){
        if($_GET['slug'] == null){
            redirect('services.php','');
        }
    }else{
        redirect('services.php','');
    }

    $service = getBySlug('services',checkParamId('slug'));
    if($service['status'] !== 200){
        redirect('services.php','');
    }
?>

<div class="py-5 bg-secondary">
    <div class="container text-center">
        <h4 class="text-white"><?= $service['data']['name']; ?></h4>
    </div>
</div>

<div class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-body shadow-sm">
                    <h4 class="heading"><?= $service['data']['name']; ?></h4>
                    <div class="underline"></div>
                    <p>
                        <?= $service['data']['small_description']; ?>
                    </p>
                    <div class="mb-3">
                        <img src="<?=$service['data']['image'] != '' ? $service['data']['image']:"assets/images/no-img.jpg";?>" class="w-100" alt="img" />
                    </div>
                    <div>
                        <?= $service['data']['long_description']; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 ">

                <?= alertSuccess(); ?>

                <div class="card sticky-top" style="top: 120px;">
                    <div class="card-header">
                        <h4 class="text-primary">Enquire Now</h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="mb-3">
                                <label>Name *</label>
                                <input type="text" name="name" required class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label>Phone *</label>
                                <input type="text" name="phone" required class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label>Service *</label>
                                <input type="text" name="service" value="<?= $service['data']['name']; ?>" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label>Comment</label>
                                <textarea name="comment" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-1">
                                <button type="sumbit" name="enquiryBtn" class="btn btn-primary w-100">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
