<?php 
include('includes/header.php'); 

if(isset($_SESSION['loggedIn'])){
    redirect('admin/dashboard.php','Welcome Back');
}


$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate phone: Only allow digits, maximum length 10
    $isValidPhone = preg_match('/^\d+$/', $phone) && strlen($phone) <= 10;

    // Validate password: At least one capital letter, one special character, one digit, minimum length 8
    $isValidPassword = preg_match('/^(?=.*[A-Z])(?=.*[@$!%*?&])(?=.*[0-9]).{8,}$/', $password);

    if (!$isValidPhone || !$isValidPassword) {
        // Display error message if phone or password is invalid
        echo '<div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> Error: Invalid phone number or password.
              </div>';
    } else {
        // Validate email
        $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            // Email already exists, display error
            echo '<div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> Error: Email already exists. Please choose a different email.
                  </div>';
        } else {
            // Email does not exist, proceed with registration
            // Add your registration code here
            $insertUserQuery = "INSERT INTO users (name, phone, email, password) VALUES ('$name', '$phone', '$email', '$password')";

            if ($conn->query($insertUserQuery) === TRUE) {
                // Registration successful
                $successMessage = '<div class="alert alert-success" role="alert">
                                    <i class="bi bi-check-circle-fill"></i> Registration successful! Welcome, ' . $name . '.
                                  </div>';
            } else {
                // Error in registration
                echo '<div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> Error: ' . $conn->error . '
                      </div>';
            }
        }
    }
}
?>

<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary">
                        <h3 class="text-white">Register</h3>
                    </div>
                    <div class="card-body">

                        <?php echo $successMessage; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label>Enter Name</label>
                                <input type="text" name="name" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label>Enter Phone Number</label>
                                <input type="text" name="phone" id="phone" class="form-control" oninput="validatePhone()" required />
                                <div id="phone-error" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label>Enter Email Id</label>
                                <input type="email" name="email" class="form-control" oninput="validateEmail()"required />
                                <div id="email-error" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label>Enter Password</label>
                                <input type="password" id="password" name="password" class="form-control" oninput="validatePassword()" required />
                                <div id="password-error" class="text-danger"></div>
                            </div>
                            <div class="mb-3 mt-4">
                                <button type="submit" name="registerBtn" class="btn btn-primary w-100">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validatePhone() {
        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone-error');
        const isValidPhone = /^\d+$/.test(phoneInput.value) && phoneInput.value.length <= 10;
        
        if (!isValidPhone) {
            phoneError.textContent = 'Please enter only numeric characters, and maximum length is 10.';
        } else {
            phoneError.textContent = '';
        }
    }

    function validatePassword() {
        const passwordInput = document.getElementById('password');
        const passwordError = document.getElementById('password-error');
        const isValidPassword = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(passwordInput.value);
        
        if (!isValidPassword) {
            passwordError.textContent = 'Password must contain at least one uppercase letter, one digit, and one special character, and be at least 8 characters long.';
        } else {
            passwordError.textContent = '';
        }
    }
     function validateEmail() {
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);

        if (!isValidEmail) {
            emailError.textContent = 'Please enter a valid email address.';
        } else {
            emailError.textContent = '';
        }
    }
    function validateForm() {
        validatePhone();
        validatePassword();
        validateEmail();
        // Additional client-side validation can be added here if needed

        // Return false if any validation fails
        return !document.getElementById('phone-error').textContent && !document.getElementById('password-error').textContent && !document.getElementById('email-error').textContent;
    }

    
    
</script>

<?php include('includes/footer.php'); ?>
