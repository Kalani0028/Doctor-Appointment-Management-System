<?php


// Add Session , Database Connection 
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $specialization = trim($_POST['specialization']);
    $phone = trim($_POST['phone']);
    $fees = trim($_POST['fees']);



    // 1. Check email in database
    $checkEmail = $conn->prepare("SELECT id FROM doctors WHERE email = ?");
    $checkEmail->execute([$email]);

    if ($checkEmail->rowCount() > 0) {
        $error_msg = "A doctor with this email address has already been added.!";
    } else {

        // 2. Hash Doctors password 
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);




        // 3. Data Insert 
        $sql = "INSERT INTO doctors (name, email, password, specialization, phone, fees) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$name, $email, $hashed_password, $specialization, $phone, $fees])) {
            $success_msg = "The doctor was successfully entered into the system.!";
        } else {
            $error_msg = "An error occurred while entering data.";
        }
    }
}
?>




<div class="row">

    <!-- Admin Navigation Sidebar -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item text-white text-center py-3 fw-bold" style="background-color: #07484a;">
                        Admin Navigation
                    </div>


                    <a href="dashboard.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-chart-line me-2" style="color: #07484a;"></i> Dashboard
                    </a>


                    <a href="add_doctor.php" class="list-group-item list-group-item-action active py-3 border-0 text-white fw-bold" style="background-color: #0d5c5f;">
                        <i class="fa-solid fa-user-doctor me-2"></i> Add Doctor
                    </a>


                    <a href="manage_doctors.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-user-gear me-2" style="color: #07484a;"></i> Manage Doctors
                    </a>


                    <a href="manage_patients.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-users me-2" style="color: #07484a;"></i> Manage Patients
                    </a>


                    <a href="manage_appointments.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-calendar-check me-2" style="color: #07484a;"></i> Appointments
                    </a>


                    <a href="../logout.php" class="list-group-item list-group-item-action py-3 text-danger fw-bold">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>




    <!-- Add Doctor Form  -->

    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h3 class="fw-bold mb-2" style="color: #07484a;">Add New Doctor</h3>
            <p class="text-muted small">Fill the form below to register a new doctor into the system.</p>
            <hr class="mb-4">



            <!-- Alerts  -->
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?php echo $success_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

            <?php endif; ?>
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo $error_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="add_doctor.php" method="POST" class="mt-2">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Doctor Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Dr. John Doe" required>
                    </div>



                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Specialization</label>
                        <select name="specialization" class="form-select" required>
                            <option value="">-- Select Specialization --</option>
                            <option value="Cardiologist">Cardiologist</option>
                            <option value="Dermatologist">Dermatologist</option>
                            <option value="Pediatrician">Pediatrician</option>
                            <option value="Neurologist">Neurologist</option>
                            <option value="General Physician">General Physician</option>
                        </select>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="doctor@example.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create login password" required>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="0771234567" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Consultation Fees (Rs.)</label>
                        <input type="number" step="0.01" name="fees" class="form-control" placeholder="2000.00" required>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn text-white px-4 py-2 fw-bold shadow-sm" style="background-color: #07484a; border-radius: 8px;">
                        <i class="fa-solid fa-user-plus me-2"></i> Add Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>