<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



// Get  Patient  ID 
if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
} elseif (isset($_SESSION['user_id'])) {
    $patient_id = $_SESSION['user_id'];
} else {
    header("Location: ../login.php");
    exit();
}




// Profile Update 
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // 1. Profile Information Update 
    if (isset($_POST['update_profile'])) {
        $name   = trim($_POST['name']);
        $email  = trim($_POST['email']);
        $phone  = trim($_POST['phone']);
        $gender = trim($_POST['gender']);
        $dob    = trim($_POST['dob']);

        $sql = "UPDATE patients SET name = ?, email = ?, phone = ?, gender = ?, dob = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$name, $email, $phone, $gender, $dob, $patient_id])) {
            $success_msg = "Profile Information updated successfully!";
        } else {
            $error_msg = "Failed to update profile information.";
        }
    }
}




// Fetch Data 
$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();
?>


<div class="row">
    <!-- Navigation Sidebar -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item text-white text-center py-3 fw-bold" style="background-color: #07484a;">
                        Patient Navigation
                    </div>
                    <a href="dashboard.php" class="list-group-item list-group-item-action py-3">
                        <i class="fa-solid fa-gauge me-2" style="color: #07484a;"></i> My Dashboard
                    </a>
                    <a href="book_appointment.php" class="list-group-item list-group-item-action py-3">
                        <i class="fa-solid fa-calendar-plus me-2" style="color: #07484a;"></i> Book Appointment
                    </a>
                    <a href="my_profile.php" class="list-group-item list-group-item-action active py-3 border-0 text-white" style="background-color: #07484a;">
                        <i class="fa-solid fa-user-gear me-2"></i> My Profile
                    </a>
                    <a href="../logout.php" class="list-group-item list-group-item-action py-3 text-danger fw-bold">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>





    <!-- Main Content -->
    <div class="col-md-9">
        <h3 class="fw-bold mb-4 d-flex align-items-center" style="color: #07484a;">
            <i class="fa-solid fa-user-circle me-2 text-primary"></i> My Profile
        </h3>




        <!-- Alerts -->
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?php echo $success_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo $error_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>





        <!-- Card: Profile Information -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-light py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-secondary">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Profile Information
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="my_profile.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary">Full Name *</label>
                            <input type="text" name="name" class="form-control py-2" style="border-radius: 8px;" value="<?php echo htmlspecialchars($patient['name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary">Email Address *</label>
                            <input type="email" name="email" class="form-control py-2" style="border-radius: 8px;" value="<?php echo htmlspecialchars($patient['email'] ?? ''); ?>" required>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary">Phone Number</label>
                            <input type="text" name="phone" class="form-control py-2" style="border-radius: 8px;" value="<?php echo htmlspecialchars($patient['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary">Gender *</label>
                            <select name="gender" class="form-select py-2" style="border-radius: 8px;" required>
                                <option value="Male" <?php echo (isset($patient['gender']) && strtolower($patient['gender']) == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($patient['gender']) && strtolower($patient['gender']) == 'female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary">Date of Birth</label>
                            <input type="date" name="dob" class="form-control py-2" style="border-radius: 8px;" value="<?php echo htmlspecialchars($patient['dob'] ?? ''); ?>">
                        </div>
                    </div>



                    <div class="mt-3">
                        <button type="submit" name="update_profile" class="btn text-white fw-bold px-4 py-2" style="background-color: #07484a; border-radius: 8px;">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> UPDATE PROFILE
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>