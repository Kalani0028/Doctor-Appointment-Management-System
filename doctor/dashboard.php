<?php
session_start();
include '../config/db.php';
include '../includes/header.php';




// Get Login Doctor ID 
if (!isset($_SESSION['doctor_id'])) {
    header("Location: ../login.php");
    exit();
}
$doctor_id = $_SESSION['doctor_id'];




// 1. Dashboard Stat Cards 5 
$stmt_all = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ?");
$stmt_all->execute([$doctor_id]);
$total_appointments = $stmt_all->fetchColumn();

$stmt_new = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND status = 'Pending'");
$stmt_new->execute([$doctor_id]);
$new_appointments = $stmt_new->fetchColumn();

$stmt_approved = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND (status = 'Approved' OR status = 'Confirmed')");
$stmt_approved->execute([$doctor_id]);
$approved_appointments = $stmt_approved->fetchColumn();

$stmt_cancelled = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND status = 'Cancelled'");
$stmt_cancelled->execute([$doctor_id]);
$cancelled_appointments = $stmt_cancelled->fetchColumn();

$stmt_completed = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND status = 'Completed'");
$stmt_completed->execute([$doctor_id]);
$completed_appointments = $stmt_completed->fetchColumn();





// 2. Get data for Appointments Table 
$sql = "SELECT appointments.*, patients.name AS patient_name, patients.phone AS patient_phone 
        FROM appointments 
        JOIN patients ON appointments.patient_id = patients.id 
        WHERE appointments.doctor_id = ? 
        ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();
?>




<div class="row">
    <!-- Doctor Navigation Sidebar -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item text-white text-center py-3 fw-bold" style="background-color: #07484a;">
                        Doctor Navigation
                    </div>
                    <a href="dashboard.php" class="list-group-item list-group-item-action active py-3 border-0 text-white fw-bold" style="background-color: #0d5c5f;">
                        <i class="fa-solid fa-chart-line me-2"></i> My Appointments
                    </a>
                    <a href="my_profile.php" class="list-group-item list-group-item-action py-3 text-dark fw-bold">
                        <i class="fa-solid fa-user-gear me-2" style="color: #07484a;"></i> My Profile
                    </a>
                    <a href="../logout.php" class="list-group-item list-group-item-action py-3 text-danger fw-bold">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>




    <!--  Main Content -->
    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h2 class="fw-bold mb-1" style="color: #07484a;">Welcome back!</h2>
            <p class="text-muted small">Here is your appointment summary and patient details.</p>
            <hr class="mb-4">




            <!-- STAT CARDS SECTION -->
            <div class="row g-3 mb-4">
                
                <!-- 1. All Appointment Card -->
                <div class="col-md-4">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #ffc107;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; opacity: 0.9;">All Appointments</h6>
                                <h2 class="fw-bold mb-0"><?php echo $total_appointments; ?></h2>
                            </div>
                            <div>
                                <i class="fa-solid fa-file-lines fa-3x" style="opacity: 0.4;"></i>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- 2. New Appointment Card -->
                <div class="col-md-4">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #17a2b8;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; opacity: 0.9;">New Appointments</h6>
                                <h2 class="fw-bold mb-0"><?php echo $new_appointments; ?></h2>
                            </div>
                            <div>
                                <i class="fa-solid fa-folder-plus fa-3x" style="opacity: 0.4;"></i>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- 3. Approved Appointment Card -->
                <div class="col-md-4">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #6f42c1;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; opacity: 0.9;">Approved Appointments</h6>
                                <h2 class="fw-bold mb-0"><?php echo $approved_appointments; ?></h2>
                            </div>
                            <div>
                                <i class="fa-solid fa-calendar-check fa-3x" style="opacity: 0.4;"></i>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- 4. Cancelled Appointment Card -->
                <div class="col-md-6">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #dc3545;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; opacity: 0.9;">Cancelled Appointments</h6>
                                <h2 class="fw-bold mb-0"><?php echo $cancelled_appointments; ?></h2>
                            </div>
                            <div>
                                <i class="fa-solid fa-calendar-xmark fa-3x" style="opacity: 0.4;"></i>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- 5. Completed Appointment Card -->
                <div class="col-md-6">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #198754;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; opacity: 0.9;">Completed Appointments</h6>
                                <h2 class="fw-bold mb-0"><?php echo $completed_appointments; ?></h2>
                            </div>
                            <div>
                                <i class="fa-solid fa-circle-check fa-3x" style="opacity: 0.4;"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>




            <!-- END STAT CARDS -->

            <h5 class="fw-bold text-secondary mb-3 mt-2">
                <i class="fa-solid fa-list me-2" style="color: #07484a;"></i> MY APPOINTMENTS
            </h5>

            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="text-white" style="background-color: #07484a;">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Patient Name</th>
                            <th class="py-3">Contact Number</th>
                            <th class="py-3">Appointment Date</th>
                            <th class="py-3">Appointment Time</th>
                            <th class="py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($appointments) > 0): ?>
                            <?php $sn = 1; foreach ($appointments as $app): ?>
                                <tr>
                                    <td><?php echo $sn++; ?></td>
                                    <td class="fw-bold text-secondary"><?php echo htmlspecialchars($app['patient_name']); ?></td>
                                    <td>
                                        <i class="fa-solid fa-phone me-1 text-muted"></i>
                                        <?php echo htmlspecialchars($app['patient_phone']); ?>
                                    </td>
                                    <td>
                                        <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                        <?php echo htmlspecialchars($app['appointment_date']); ?>
                                    </td>
                                    <td>
                                        <i class="fa-regular fa-clock me-1 text-muted"></i>
                                        <?php echo date('h:i A', strtotime($app['appointment_time'])); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        $status = strtolower($app['status']);
                                        if ($status == 'pending') {
                                            echo '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>';
                                        } elseif ($status == 'confirmed' || $status == 'approved') {
                                            echo '<span class="badge bg-primary px-3 py-2 rounded-pill">Approved</span>';
                                        } elseif ($status == 'completed') {
                                            echo '<span class="badge bg-success px-3 py-2 rounded-pill">Completed</span>';
                                        } else {
                                            echo '<span class="badge bg-danger px-3 py-2 rounded-pill">Cancelled</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-folder-open fa-2x mb-2 d-block text-secondary"></i>
                                    No appointment has been booked for you yet.
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