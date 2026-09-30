<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



// 1. Check doctor ID
if (!isset($_GET['id'])) {
    header("Location: manage_doctors.php");
    exit();
}

$doctor_id = $_GET['id'];




// 2. Updating data upon form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $specialization = trim($_POST['specialization']);
    $phone = trim($_POST['phone']);
    $fees = trim($_POST['fees']);




    // Checking whether another doctor is using the email address.
    $checkEmail = $conn->prepare("SELECT id FROM doctors WHERE email = ? AND id != ?");
    $checkEmail->execute([$email, $doctor_id]);

    if ($checkEmail->rowCount() > 0) {
        $error_msg = "This email address belongs to another doctor.!";
    } else {


        // Upade details (not pwd)
        $sql = "UPDATE doctors SET name = ?, email = ?, specialization = ?, phone = ?, fees = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([$name, $email, $specialization, $phone, $fees, $doctor_id]);



        if ($result) {
            $success_msg = "The doctor's information was successfully updated!";
        } else {
            $error_msg = "An error occurred while updating the data.";
        }
    }
}




// 3. Fetching current data from the database to populate the form.
$stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();




// Turning back if a doctor is not met.
if (!$doctor) {
    header("Location: manage_doctors.php");
    exit();
}
?>



<div class="row">
    <!-- Admin Navigation Sidebar  -->
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
                    <a href="add_doctor.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-user-doctor me-2" style="color: #07484a;"></i> Add Doctor
                    </a>
                    <a href="manage_doctors.php" class="list-group-item list-group-item-action active py-3 border-0 text-white fw-bold" style="background-color: #0d5c5f;">
                        <i class="fa-solid fa-user-gear me-2"></i> Manage Doctors
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




    <!--  Edit Form  -->
    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold mb-0" style="color: #07484a;">Edit Doctor Details</h3>
                <a href="manage_doctors.php" class="btn btn-sm text-white fw-bold px-3" style="background-color: #6c757d;">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
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




            <!-- Edit Form  -->
            <form action="edit_doctor.php?id=<?php echo $doctor_id; ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Doctor Name</label>
                        <input type="text" name="name" class="form-control py-2" value="<?php echo htmlspecialchars($doctor['name']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Specialization</label>
                        <select name="specialization" class="form-select py-2" required>
                            <option value="Cardiologist" <?php echo ($doctor['specialization'] == 'Cardiologist') ? 'selected' : ''; ?>>Cardiologist</option>
                            <option value="Dermatologist" <?php echo ($doctor['specialization'] == 'Dermatologist') ? 'selected' : ''; ?>>Dermatologist</option>
                            <option value="Pediatrician" <?php echo ($doctor['specialization'] == 'Pediatrician') ? 'selected' : ''; ?>>Pediatrician</option>
                            <option value="Neurologist" <?php echo ($doctor['specialization'] == 'Neurologist') ? 'selected' : ''; ?>>Neurologist</option>
                            <option value="General Physician" <?php echo ($doctor['specialization'] == 'General Physician') ? 'selected' : ''; ?>>General Physician</option>
                        </select>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Email Address</label>
                        <input type="email" name="email" class="form-control py-2" value="<?php echo htmlspecialchars($doctor['email']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Phone Number</label>
                        <input type="text" name="phone" class="form-control py-2" value="<?php echo htmlspecialchars($doctor['phone']); ?>" required>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Consultation Fees (Rs.)</label>
                        <input type="number" step="0.01" name="fees" class="form-control py-2" value="<?php echo htmlspecialchars($doctor['fees']); ?>" required>
                    </div>
                </div>



                <div class="text-end mt-4">
                    <button type="submit" class="btn text-white px-4 py-2 fw-bold" style="background-color: #07484a;">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Update Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>