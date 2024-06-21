<form action="" method="GET">

    <input type="hidden" name="start" value="<?= isset($_GET['start']) ? $_GET['start'] : date('Y-m-d H:i') ?>">
    <input type="hidden" name="end" value="<?= isset($_GET['end']) ? $_GET['end'] : date('Y-m-d H:i', strtotime("+24 hours")) ?>">
    <div class="mb-2">
        <label class="title-sm fs-12">Select Brand *</label>
        <select name="brand" class="form-select">
            <option value="">Select Brand</option>
            <?php
            $brands = getAll('brands','status');
            if($brands){
                if(mysqli_num_rows($brands) > 0){
                    foreach($brands as $brandItem){
                        ?>
                        <option 
                            value="<?= $brandItem['slug']; ?>"
                            <?php if(isset($_GET['brand']) && $_GET['brand'] == $brandItem['slug']) { echo 'selected'; }; ?>
                        >
                            <?= $brandItem['name']; ?>
                        </option>
                        <?php
                    }
                }else{
                    echo '<option value=""> Brands not Available</option>';
                }
            }else{
                echo '<option value="">Something is Wrong</option>';
            }
            ?>
        </select>
    </div>
    <div class="mb-2">
        <label class="title-sm fs-12">Select Body Types</label>
        <select name="body_type" class="form-select">
            <option value="">Select Body Types</option>
            <?php
            $bodyTypes = getAll('body_types','status');
            if($bodyTypes){
                if(mysqli_num_rows($bodyTypes) > 0){
                    foreach($bodyTypes as $bodyTypeItem){
                        ?>
                        <option 
                            value="<?= $bodyTypeItem['slug']; ?>"
                            <?php if(isset($_GET['body_type']) && $_GET['body_type'] == $bodyTypeItem['slug']) { echo 'selected'; }; ?>
                        >
                            <?= $bodyTypeItem['name']; ?>
                        </option>
                        <?php
                    }
                }else{
                    echo '<option value="">No Body Types Available</option>';
                }
            }else{
                echo '<option value="">Something Went Wrong</option>';
            }
            ?>
        </select>
    </div>

    <div class="mb-2">
        <label class="title-sm fs-12">Select Transmission *</label>
        <select name="transmission" class="form-select">
            <option value="">Select Transmission</option>
            <option value="Automatic" <?php if(isset($_GET['transmission']) && $_GET['transmission'] == 'Automatic') { echo 'selected'; }; ?>>Automatic</option>
            <option value="Manual" <?php if(isset($_GET['transmission']) && $_GET['transmission'] == 'Manual') { echo 'selected'; }; ?>>Manual</option>
        </select>
    </div>

    <div class="mb-2">
        <label class="title-sm fs-12">Select Fuel</label>
        <select name="fuel" class="form-select">
            <option value="">Select Fuel Tyoe</option>
            <option value="Petrol" <?php if(isset($_GET['fuel']) && $_GET['fuel'] == 'Petrol') { echo 'selected'; }; ?>>Petrol</option>
            <option value="Diesel" <?php if(isset($_GET['fuel']) && $_GET['fuel'] == 'Diesel') { echo 'selected'; }; ?>>Diesel</option>
        </select>
    </div>
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="sun_roof" <?php if(isset($_GET['sun_roof'])) { echo 'checked'; }; ?> id="sun_roofCheckbox">
        <label class="form-check-label" for="sun_roofCheckbox">Sun Roof</label>
    </div>
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="cruise_control" <?php if(isset($_GET['cruise_control'])) { echo 'checked'; }; ?> id="cruise_controlCheckbox">
        <label class="form-check-label" for="cruise_controlCheckbox">Cruise Control</label>
    </div>
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="360_camera" <?php if(isset($_GET['360_camera'])) { echo 'checked'; }; ?> id="360_cameraCheckbox">
        <label class="form-check-label" for="360_cameraCheckbox">360 Camera</label>
    </div>
       <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="airbags" <?php if(isset($_GET['airbags'])) { echo 'checked'; }; ?> id="airbagsCheckbox">
        <label class="form-check-label" for="airbagsCheckbox">Air Bags</label>
    </div>
    <div class="mb-0">
        <hr>
        <button type="submit" class="btn btn-primary w-100">Submit</button>
    </div>
</form>

<?php
    $brandId = 0;
    if(isset($_GET['brand']) && $_GET['brand'] != ""){
        $brand = $_GET['brand'];
        $brandData = getIdByName("brands",$brand);
        if($brandData["status"] == 200){
            $brandId = $brandData['data']['id'];

        }else{
            echo $brandData["message"];
        }
    }

    $bodyTypeId = 0;
    if(isset($_GET['body_type']) && $_GET['body_type'] != ""){
        $bodyType = $_GET['body_type'];
        $bodyTypeData = getIdByName("body_types",$bodyType);
        if($bodyTypeData["status"] == 200){
            $bodyTypeId = $bodyTypeData['data']['id'];
        }else{
            echo $bodyTypeData["message"];
        }
    }
?>