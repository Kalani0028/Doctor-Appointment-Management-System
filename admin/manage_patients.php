<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



// 1. Delete Patient 
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $sql = "DELETE FROM patients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$delete_id])) {
        $success_msg = "The patient's account was successfully removed from the system.!";
    } else {
        $error_msg = "An error occurred during removal.";
    }
}



// 2. Select all patient details from database
$query = $conn->query("SELECT * FROM patients ORDER BY id DESC");
$patients = $query->fetchAll();
?>



<div class="row">
    <!--  Admin Navigation Sidebar  -->
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


                    <a href="manage_doctors.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-user-gear me-2" style="color: #07484a;"></i> Manage Doctors
                    </a>


                    <a href="manage_patients.php" class="list-group-item list-group-item-action active py-3 border-0 text-white fw-bold" style="background-color: #0d5c5f;">
                        <i class="fa-solid fa-users me-2"></i> Manage Patients
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



    <!--  Patients Table  -->
    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h3 class="fw-bold mb-2" style="color: #07484a;">Manage Patients</h3>
            <p class="text-muted small">View or remove registered patients from the system.</p>
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




            <!-- Patients Table  -->
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle border">

                    <thead class="text-white" style="background-color: #07484a;">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Full Name</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3">Phone Number</th>
                            <th class="py-3">Gender</th>
                            <th class="py-3">Date of Birth</th> 
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php if (count($patients) > 0): ?>
                            <?php $sn = 1; foreach ($patients as $patient): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $sn++; ?></td>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($patient['name']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['email']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                                    <td>
                                        <span class="badge px-3 py-2 text-white <?php echo (strtolower($patient['gender']) == 'male') ? 'bg-primary' : 'bg-danger'; ?>">
                                            <?php echo htmlspecialchars($patient['gender']); ?>
                                        </span>
                                    </td>
                                    


                                    <!-- 👈 2. Add DOB Data to data table -->
                                    <td>
                                        <?php 
                                            if (!empty($patient['dob'])) {
                                                echo htmlspecialchars(date('m/d/Y', strtotime($patient['dob'])));
                                            } else {
                                                echo '<span class="text-muted">N/A</span>';
                                            }
                                        ?>
                                    </td>



                                    <td class="text-center">

                                        <!-- Delete Button -->
                                        <a href="manage_patients.php?delete_id=<?php echo $patient['id']; ?>" 
                                           class="btn btn-sm btn-danger fw-bold px-3" 
                                           onclick="return confirm('Are you sure you want to remove this patient from the system?');">
                                            <i class="fa-solid fa-trash me-1"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No patients have been registered in the system yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>