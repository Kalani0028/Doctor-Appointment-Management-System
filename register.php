<?php


// Add Database connection and Header 
include 'config/db.php';
include 'includes/header.php';

$success_msg = "";
$error_msg = "";



// Check the Form submittion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];




    // 1. Checking if the email already exists in the database
    $checkEmail = $conn->prepare("SELECT id FROM patients WHERE email = ?");
    $checkEmail->execute([$email]);


    if ($checkEmail->rowCount() > 0) {
        $error_msg = "This email is already registered in the system.!";
    } else {




        // 2. Password Encrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);




        // 3. Insert  data to database
        $sql = "INSERT INTO patients (name, email, password, phone, gender, dob) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$name, $email, $hashed_password, $phone, $gender, $dob])) {
            $success_msg = "Registration successful! You can now log in.";
        } else {
            $error_msg = "An error occurred. Please try again..";
        }
    }
}
?>






<!--  Background Image & Overlay  -->
<div class="d-flex justify-content-center align-items-center py-5" 
     style="background: linear-gradient(rgba(7, 72, 74, 0.45), rgba(7, 72, 74, 0.45)), url('images/14.jpeg') no-repeat center center/cover; min-height: 88vh;">



    
    <!-- Register Card එක -->
    <div class="card p-4 border-0" 
         style="width: 480px; 
                border-radius: 20px; 
                background: rgba(255, 255, 255, 0.95); 
                backdrop-filter: blur(8px); 
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);">

        
        <div class="text-center mb-3">
            <div class="d-inline-block p-3 rounded-circle mb-2" style="background-color: rgba(7, 72, 74, 0.1);">
                <i class="fa-solid fa-user-plus fa-2x" style="color: #07484a;"></i>
            </div>
            <h3 class="fw-bold mb-1" style="color: #07484a;">Patient Registration</h3>
            <p class="text-muted small mb-0">Create an account to manage your medical appointments.</p>
        </div>


        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success py-2 small rounded-3"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger py-2 small rounded-3"><?php echo $error_msg; ?></div>
        <?php endif; ?>




        <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Full Name</label>
                <input type="text" name="name" class="form-control py-2" style="border-radius: 10px;" placeholder="John Doe" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Email Address</label>
                <input type="email" name="email" class="form-control py-2" style="border-radius: 10px;" placeholder="name@example.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Password</label>
                <input type="password" name="password" class="form-control py-2" style="border-radius: 10px;" placeholder="Create a password" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Phone Number</label>
                <input type="text" name="phone" class="form-control py-2" style="border-radius: 10px;" placeholder="07X XXXXXXX" required>
            </div>



            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold small text-secondary">Gender</label>
                    <select name="gender" class="form-select py-2" style="border-radius: 10px;" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold small text-secondary">Date of Birth</label>
                    <input type="date" name="dob" class="form-control py-2" style="border-radius: 10px;" required>
                </div>
            </div>



            <button type="submit" class="btn w-100 py-2.5 fw-bold text-white shadow-sm mt-2" style="background-color: #07484a; border-radius: 10px;">
                <i class="fa-solid fa-user-check me-1 text-warning"></i> Register
            </button>
        </form>



        <div class="text-center mt-3 small">
            Already have an account? <a href="login.php" class="fw-bold text-decoration-none" style="color: #07484a;">Login Here</a>
        </div>
    </div>
</div>



<?php include 'includes/footer.php'; ?>