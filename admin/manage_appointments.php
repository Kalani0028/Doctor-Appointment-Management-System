<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



// 1. Status Update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $appointment_id = $_GET['id'];
    
    if ($action == 'confirm') {
        $status = 'Confirmed';
    } elseif ($action == 'cancel') {
        $status = 'Cancelled';
    }

    if (isset($status)) {
        $sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$status, $appointment_id])) {
            $success_msg = "Appointment status successfully updated.!";
        } else {
            $error_msg = "An error occurred while changing the status.";
        }
    }
}




// 2.  Get patient and doctor name 
$sql = "SELECT appointments.*, patients.name AS patient_name, doctors.name AS doctor_name 
        FROM appointments 
        JOIN patients ON appointments.patient_id = patients.id 
        JOIN doctors ON appointments.doctor_id = doctors.id 
        ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC";

$query = $conn->query($sql);
$appointments = $query->fetchAll();
?>



<div class="row">
    <!-- dmin Navigation Sidebar  -->
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
                    <a href="manage_patients.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-users me-2" style="color: #07484a;"></i> Manage Patients
                    </a>
                    <a href="manage_appointments.php" class="list-group-item list-group-item-action active py-3 border-0 text-white fw-bold" style="background-color: #0d5c5f;">
                        <i class="fa-solid fa-calendar-check me-2"></i> Appointments
                    </a>
                    <a href="../logout.php" class="list-group-item list-group-item-action py-3 text-danger fw-bold">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>





    <!-- Appointments Table  -->
    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h3 class="fw-bold mb-2" style="color: #07484a;">Manage Appointments</h3>
            <p class="text-muted small">View and manage all channel appointments booked by patients.</p>
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




            <!-- Table -->
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle border">
                    <thead class="text-white" style="background-color: #07484a;">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Patient Name</th>
                            <th class="py-3">Doctor Name</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Time</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($appointments) > 0): ?>
                            <?php $sn = 1; foreach ($appointments as $app): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $sn++; ?></td>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($app['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($app['doctor_name']); ?></td>
                                    <td><?php echo htmlspecialchars($app['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                                    <td>



                                        <!-- Change Badge colour-->
                                        <?php 
                                        if ($app['status'] == 'Pending') {
                                            echo '<span class="badge bg-warning text-dark px-3 py-2">Pending</span>';
                                        } elseif ($app['status'] == 'Confirmed') {
                                            echo '<span class="badge bg-success px-3 py-2">Confirmed</span>';
                                        } else {
                                            echo '<span class="badge bg-danger px-3 py-2">Cancelled</span>';
                                        }
                                        ?>
                                 </td>
                                    <td class="text-center">
                                        <?php if ($app['status'] == 'Pending'): ?>
                                            <!-- Confirm Button -->
                                            <a href="manage_appointments.php?action=confirm&id=<?php echo $app['id']; ?>" 
                                               class="btn btn-sm btn-success me-1 fw-bold" title="Confirm Appointment">
                                                <i class="fa-solid fa-check"></i>
                                            </a>
                                            <!-- Cancel Button -->
                                            <a href="manage_appointments.php?action=cancel&id=<?php echo $app['id']; ?>" 
                                               class="btn btn-sm btn-danger fw-bold" title="Cancel Appointment"
                                               onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted"><small class="fw-bold">No Actions</small></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No appointments have been booked in the system yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>