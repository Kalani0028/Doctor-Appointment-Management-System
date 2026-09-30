<?php
session_start();
include 'includes/header.php';
include 'config/db.php'; // dp.php for mysqli (PDO connection )
$error = "";

if (isset($_POST['login'])) {
    $role     = $_POST['role'];
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {



        // 1. Admin Login
        if ($role == 'admin') {
            $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();



            // If the admin password is not hashed, check it directly. If it is hashed, use `password_verify`.
            if ($admin && ($password === $admin['password'] || password_verify($password, $admin['password']))) {
                $_SESSION['admin_id']  = $admin['id'];
                $_SESSION['user_role'] = 'admin';
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error = "Invalid Admin Credentials!";
            }
        } 




        // 2. Doctor Login
        elseif ($role == 'doctor') {
            $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
            $stmt->execute([$email]);
            $doctor = $stmt->fetch();

            if ($doctor) {
                if (password_verify($password, $doctor['password'])) {
                    $_SESSION['doctor_id']   = $doctor['id'];
                    $_SESSION['user_role']   = 'doctor';
                    $_SESSION['doctor_name'] = $doctor['name'];
                    header("Location: doctor/dashboard.php");
                    exit();
                } else {
                    $error = "Invalid Password!";
                }
            } else {
                $error = "No Doctor account found with this email!";
            }
        } 





        // 3. Patient Login
        elseif ($role == 'patient') {
            $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
            $stmt->execute([$email]);
            $patient = $stmt->fetch();


            if ($patient) {
                // Verify Hashed Password 
                if (password_verify($password, $patient['password'])) {
                    $_SESSION['patient_id']   = $patient['id'];
                    $_SESSION['patient_name'] = $patient['name'];
                    $_SESSION['user_role']    = 'patient';
                    header("Location: patient/dashboard.php");
                    exit();
                } else {
                    $error = "Invalid Password!";
                }
            } else {
                $error = "No Patient account found with this email!";
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>




<!-- Background Image  -->
<div class="d-flex justify-content-center align-items-center py-5" 
     style="background: linear-gradient(rgba(7, 72, 74, 0.45), rgba(7, 72, 74, 0.45)), url('images/13.jpeg') no-repeat center center/cover; min-height: 85vh;">
    


    <!-- Login Card -->
    <div class="card p-4 border-0" 
         style="width: 410px; border-radius: 20px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);">
        
        <div class="text-center mb-4">
            <div class="d-inline-block p-3 rounded-circle mb-2" style="background-color: rgba(7, 72, 74, 0.1);">
                <i class="fa-solid fa-lock fa-2x" style="color: #07484a;"></i>
            </div>
            <h3 class="fw-bold mb-1" style="color: #07484a;">Account Login</h3>
            <p class="text-muted small">Provide details to log into your account.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small rounded-3"><?php echo $error; ?></div>
        <?php endif; ?>




        <form method="POST" action="">
            <div class="mb-3">

                <label class="form-label fw-semibold small text-secondary">Login As</label>
                <select name="role" class="form-select py-2" style="border-radius: 10px;" required>

                    <option value="admin">Admin</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient" selected>Patient</option>


                </select>
            </div>




            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Email Address / Username</label>
                <input type="text" name="email" class="form-control py-2" style="border-radius: 10px;" placeholder="enter your email" required>
            </div>



            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Password</label>
                <input type="password" name="password" class="form-control py-2" style="border-radius: 10px;" placeholder="enter password" required>
            </div>



            <button type="submit" name="login" class="btn w-100 py-2.5 fw-bold text-white shadow-sm mt-2" style="background-color: #07484a; border-radius: 10px;">
                <i class="fa-solid fa-right-to-bracket me-1 text-warning"></i> Login
            </button>



            <div class="text-center mt-4 small">
                Don't have an account yet? <a href="register.php" class="fw-bold text-decoration-none" style="color: #07484a;">Register Here</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>