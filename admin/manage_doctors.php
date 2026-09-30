<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



// 1.Check  Doctor Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $sql = "DELETE FROM doctors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$delete_id])) {
        $success_msg = "The doctor's account was successfully removed from the system.!";
    } else {
        $error_msg = "An error occurred during removal.";
    }
}



// 2. Select all  Doctors from Database 
$query = $conn->query("SELECT * FROM doctors ORDER BY id DESC");
$doctors = $query->fetchAll();
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



    <!-- Doctors Table  -->
    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h3 class="fw-bold mb-2" style="color: #07484a;">Manage Doctors</h3>
            <p class="text-muted small">View, edit, or remove registered doctors from the system.</p>
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




            <!-- Doctors Table  -->
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle border">

                    <thead class="text-white" style="background-color: #07484a;">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Name</th>
                            <th class="py-3">Specialization</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Phone</th>
                            <th class="py-3">Fees (Rs.)</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($doctors) > 0): ?>
                            <?php $sn = 1; foreach ($doctors as $doctor): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $sn++; ?></td>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($doctor['name']); ?></td>
                                    <td><span class="badge px-3 py-2 text-white" style="background-color: #0d5c5f;"><?php echo htmlspecialchars($doctor['specialization']); ?></span></td>
                                    <td><?php echo htmlspecialchars($doctor['email']); ?></td>
                                    <td><?php echo htmlspecialchars($doctor['phone']); ?></td>
                                    <td class="fw-bold"><?php echo number_format($doctor['fees'], 2); ?></td>
                                    <td class="text-center">


                                        <!-- Edit Button -->
                                        <a href="edit_doctor.php?id=<?php echo $doctor['id']; ?>" class="btn btn-sm btn-warning text-dark me-1 fw-bold">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>




                                        <!-- Delete Button -->
                                        <a href="manage_doctors.php?delete_id=<?php echo $doctor['id']; ?>" 
                                           class="btn btn-sm btn-danger fw-bold" 
                                           onclick="return confirm('Are you sure you want to remove this doctor from the system?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No doctors have been added to the system yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>