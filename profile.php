<?php 
include('includes/header.php'); 

require 'auth-user.php'; 
?>


<div class="banner py-4">
    <div class="container">
        <h4 class="banner-heading mb-3">My Profile</h4>
    </div>
</div>

<div class="section bg-light">
    <div class="container">
        <div class="row">
      
            <div class="col-md-12">
                <?php
                    alertSuccess();

                    $userId = validate($_SESSION['loggedInUser']['user_id']);
                    $user = getById('users',$userId);
                    if($user['status'] == 200) 
                    {

                        if($user['data']['is_verified'] == 0 && $user['data']['remarks'] != ""){
                            ?>
                                <div class="alert alert-warning">
                                    <span class="fs-18 fw-bold">Remarks: </span>
                                    <?= $user['data']['remarks'] ?>
                                </div>
                            <?php
                        }
                ?>
                <h5 class="fs-16 fw-bold mb-3">Profile Verification Status: 
                    <?php 
                        if($user['data']['dl_number'] != ""){

                            if($user['data']['is_verified']){
                                ?>
                                    <span class="badge bg-success fs-6 rounded-5"><i class="fa fa-check-circle text-white me-1"></i> Verified</span>
                                <?php 
                            } else{
                                ?>
                                    <span class="badge bg-warning fs-6 rounded-5"><i class="fa fa-question-circle text-white me-1"></i> Under Review</span>
                                <?php
                            } 
                        }
                        else{
                            ?>
                                <span class="badge bg-danger fs-6 rounded-5"><i class="fa fa-question-circle text-white me-1"></i> Documents upload pending</span>
                            <?php
                        } 
                    ?>
                </h5>
                <form action="code.php" method="POST" enctype="multipart/form-data">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="mb-0">1. Basic Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?= $user['data']['name']; ?>" class="form-control"  required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email Id</label>
                                        <input type="email" readonly name="email" value="<?= $user['data']['email']; ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" value="<?= $user['data']['phone']; ?>" max="10" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Alternate Phone Number</label>
                                        <input type="text" name="alt_phone" value="<?= $user['data']['alt_phone'] ?? ""; ?>" max="10" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control" required rows="3"> <?= $user['data']['address'] ?? ""; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="mb-0">2. Driving License</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Driving License Number</label>
                                        <input type="text" name="dl_number" value="<?= $user['data']['dl_number'] ?? ""; ?>" <?= $user['data']['dl_number'] != "" ? 'readonly':''; ?> class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Driving License Front Image</label>
                                        <input type="file" name="dl_image_front" class="form-control" />
                                        <?php
                                            if($user['data']['dl_image_front'] != ""){
                                                ?>
                                                    <img src="<?= $user['data']['dl_image_front']; ?>" alt="" class="w-25 p-1">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Driving License Back Image</label>
                                        <input type="file" name="dl_image_back" class="form-control" />
                                        <?php
                                            if($user['data']['dl_image_back'] != ""){
                                                ?>
                                                    <img src="<?= $user['data']['dl_image_back']; ?>" alt="" class="w-25 p-1">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                    
                   

                    <div class="mb-3 mt-4">
                        <button type="submit" name="updateProfile" class="btn btn-primary float-end">Update Profile</button>
                    </div>
                </form>
                <?php
                    }
                    else
                    {

                    }
                ?>

            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>
