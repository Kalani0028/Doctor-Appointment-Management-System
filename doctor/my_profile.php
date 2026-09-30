<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



$doctor_id = 1; 



// 1. Profile Update 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $fees = trim($_POST['fees']);
    $password = $_POST['password'];



    // Hash PWD
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE doctors SET name = ?, specialization = ?, email = ?, phone = ?, fees = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([$name, $specialization, $email, $phone, $fees, $hashed_password, $doctor_id]);
    } else {


        // Updating only the other data if the password has not been changed.
        $sql = "UPDATE doctors SET name = ?, specialization = ?, email = ?, phone = ?, fees = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([$name, $specialization, $email, $phone, $fees, $doctor_id]);
    }




    if ($result) {
        $success_msg = "Your profile information has been successfully updated.!";
    } else {
        $error_msg = "An error occurred while updating the information.";
    }
}




// 2. Fetching the current doctor's data from the database and populating the form.
$stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();
?>



<div class="row">
    <!--  Doctor Navigation Sidebar  -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item text-white text-center py-3 fw-bold" style="background-color: #07484a;">
                        Doctor Navigation
                    </div>
                    <a href="dashboard.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-chart-line me-2" style="color: #07484a;"></i> My Appointments
                    </a>
                    <a href="my_profile.php" class="list-group-item list-group-item-action active py-3 border-0 text-white fw-bold" style="background-color: #0d5c5f;">
                        <i class="fa-solid fa-user-gear me-2"></i> My Profile
                    </a>
                    <a href="../logout.php" class="list-group-item list-group-item-action py-3 text-danger fw-bold">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>





    <!--  Profile Form  -->
    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h3 class="fw-bold mb-2" style="color: #07484a;">My Profile</h3>
            <p class="text-muted small">You can update your medical account information here.</p>
            <hr class="mb-4">

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




            <form action="my_profile.php" method="POST" class="mt-2">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Doctor Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($doctor['name'] ?? ''); ?>" required>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Specialization</label>
                        <input type="text" name="specialization" class="form-control" value="<?php echo htmlspecialchars($doctor['specialization'] ?? ''); ?>" required>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($doctor['email'] ?? ''); ?>" required>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($doctor['phone'] ?? ''); ?>" required>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Consultation Fees (Rs.)</label>
                        <input type="number" step="0.01" name="fees" class="form-control" value="<?php echo htmlspecialchars($doctor['fees'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">New Password <small class="text-muted">(Optional)</small></label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn text-white px-4 py-2 fw-bold shadow-sm" style="background-color: #07484a; border-radius: 8px;">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>