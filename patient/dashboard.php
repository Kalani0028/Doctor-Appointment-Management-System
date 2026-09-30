<?php
session_start();
include '../config/db.php';
include '../includes/header.php';



// Get Patient ID from Session 
if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
} elseif (isset($_SESSION['user_id'])) {
    $patient_id = $_SESSION['user_id'];
} else {

    // If not Login , Redirect to Login Page
    header("Location: ../login.php");
    exit();
}




// 1. Get appointment details
$sql = "SELECT appointments.*, doctors.name AS doctor_name, doctors.specialization 
        FROM appointments 
        JOIN doctors ON appointments.doctor_id = doctors.id 
        WHERE appointments.patient_id = ? 
        ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$patient_id]);
$my_appointments = $stmt->fetchAll();
?>



<div class="row">
    <!--Patient Navigation Sidebar  -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item text-white text-center py-3 fw-bold" style="background-color: #07484a;">
                        Patient Navigation
                    </div>
                    <a href="dashboard.php" class="list-group-item list-group-item-action active py-3 border-0 text-white" style="background-color: #07484a;">
                        <i class="fa-solid fa-gauge me-2"></i> My Dashboard
                    </a>
                    <a href="book_appointment.php" class="list-group-item list-group-item-action py-3">
                        <i class="fa-solid fa-calendar-plus me-2" style="color: #07484a;"></i> Book Appointment
                    </a>
                    <a href="my_profile.php" class="list-group-item list-group-item-action py-3">
                        <i class="fa-solid fa-user-gear me-2" style="color: #07484a;"></i> My Profile
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
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h3 class="fw-bold mb-2" style="color: #07484a;">Welcome !</h3>
            <p class="text-muted small">You can view your current channel details and history here.</p>
            <hr class="mb-4">

            <h5 class="fw-bold text-secondary mb-3">
                <i class="fa-solid fa-list-check me-2" style="color: #07484a;"></i> My Appointments
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="text-white" style="background-color: #07484a;">
                        <tr>
                            <th class="py-3">App. No</th>
                            <th class="py-3">Doctor Name</th>
                            <th class="py-3">Specialization</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Time</th>
                            <th class="py-3 text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($my_appointments) > 0): ?>
                            <?php foreach ($my_appointments as $app): ?>
                                <tr>
                                    <!-- Appointment ID / Number -->
                                    <td class="fw-bold" style="color: #07484a;">
                                        #APP-<?php echo str_pad($app['id'], 4, '0', STR_PAD_LEFT); ?>
                                    </td>


                                    
                                    <!-- Doctor Name -->
                                    <td class="fw-semibold">
                                        <?php echo htmlspecialchars($app['doctor_name']); ?>
                                    </td>
                                    


                                    <!-- Specialization -->
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal px-2 py-1">
                                            <?php echo htmlspecialchars($app['specialization']); ?>
                                        </span>
                                    </td>


                                    
                                    <!-- Date -->
                                    <td>
                                        <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                        <?php echo date('Y-m-d', strtotime($app['appointment_date'])); ?>
                                    </td>
                                   


                                    <!-- Time -->
                                    <td>
                                        <i class="fa-regular fa-clock me-1 text-muted"></i>
                                        <?php echo date('h:i A', strtotime($app['appointment_time'])); ?>
                                    </td>
                                    



                                    <!-- Status -->
                                    <td class="text-center">
                                        <?php 
                                        $status = strtolower($app['status']);
                                        if ($status == 'pending') {
                                            echo '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-hourglass-half me-1"></i> Pending</span>';
                                        } elseif ($status == 'confirmed' || $status == 'approved') {
                                            echo '<span class="badge bg-success px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> Confirmed</span>';
                                        } else {
                                            echo '<span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-xmark me-1"></i> Cancelled</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-folder-open fa-2x mb-2 d-block text-secondary"></i>
                                    You have not booked any appointments yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>