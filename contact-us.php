<?php include('includes/header.php'); ?>


<div class="py-5 bg-secondary">
    <div class="container text-center">
        <h4 class="text-white">Contact Us</h4>
    </div>
</div>

<?php

// Check if the contactUs table exists, create it if not
$tableExistsQuery = "SHOW TABLES LIKE 'contactUs'";
$tableExistsResult = $conn->query($tableExistsQuery);

if ($tableExistsResult->num_rows == 0) {
    // Table doesn't exist, create it
    $createTableQuery = "CREATE TABLE contactUs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(255),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($createTableQuery) === TRUE) {
        echo "Table 'contactUs' created successfully.";
    } else {
        echo "Error creating table: " . $conn->error;
        exit;
    }
}

if (isset($_POST['enquiryBtn'])) {
    // Get form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];

    // Insert data into the contactUs table
    $contactUsQuery = "INSERT INTO contactUs (name, phone, email, comment) VALUES ('$name', '$phone', '$email', '$comment')";
    
    if ($conn->query($contactUsQuery) === TRUE) {
        echo "contactUs submitted successfully";
    } else {
        echo "Error: " . $contactUsQuery . "<br>" . $conn->error;
    }
}

?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-web">
                        <h4 class="title1 mb-0 text-white">Contact Us</h4>
                    </div>
                    <div class="card-body">
                                
                        <form action="#" method="POST">
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
                                <label>  Message</label>
                                <textarea name="comment" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-1">
                                <button type="sumbit" name="enquiryBtn" class="btn btn-primary w-100">SUBMIT</button>
                                <!-- You can configure your email with this form -->
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <h4>Address Info</h4>
                <div class="underline"></div>
                <p>Address: <?= webSetting('address'); ?></p>

                <hr>

                <h4>Email Address</h4>
                <div class="underline"></div>
                <p><?= webSetting('email1'); ?></p>
                <p><?= webSetting('email2'); ?></p>

                <hr>

                <h4>Phone Number</h4>
                <div class="underline"></div>
                <p><?= webSetting('phone1'); ?></p>
                <p><?= webSetting('phone2') ?? '-'; ?></p>

            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
