<?php include('includes/header.php'); ?>


<div class="py-5 bg-secondary">
    <div class="container text-center">
        <h4 class="text-white">Feed-Back</h4>
    </div>
</div>

<?php
// Check if the feedback table exists, create it if not
$tableExistsQuery = "SHOW TABLES LIKE 'feedback'";
$tableExistsResult = $conn->query($tableExistsQuery);

if ($tableExistsResult->num_rows == 0) {
    // Table doesn't exist, create it
    $createTableQuery = "CREATE TABLE feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($createTableQuery) === TRUE) {
        echo "Table 'feedback' created successfully.";
    } else {
        echo "Error creating table: " . $conn->error;
        exit;
    }
}

if (isset($_POST['enquiryBtn'])) {
    // Get form data
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    // Insert data into the feedback table
    $feedbackQuery = "INSERT INTO feedback (name, comment) VALUES ('$name', '$comment')";
    
    if ($conn->query($feedbackQuery) === TRUE) {
        echo "Feedback submitted successfully";
    } else {
        echo "Error: " . $feedbackQuery . "<br>" . $conn->error;
    }
}

?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-web">
                        <h4 class="title1 mb-0 text-white">Feed-Back</h4>
                    </div>
                    <div class="card-body">
                                
                        <form action="#" method="POST">
                            
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label> Message</label>
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
            

            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
