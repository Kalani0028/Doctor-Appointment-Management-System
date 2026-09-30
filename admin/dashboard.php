<?php


// Checking the session (checking if an admin is logged in)
session_start();

/*
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
*/



include '../config/db.php';
include '../includes/header.php';



// Get Real-time counts from Database

// 1. Total Doctors
$stmt1 = $conn->query("SELECT COUNT(*) FROM doctors");
$total_doctors = $stmt1->fetchColumn();



// 2. Total Patients
$stmt2 = $conn->query("SELECT COUNT(*) FROM patients");
$total_patients = $stmt2->fetchColumn();



// 3. Total Appointments
$stmt3 = $conn->query("SELECT COUNT(*) FROM appointments");
$total_appointments = $stmt3->fetchColumn();



// 4. Today's Appointments
$today_date = date('Y-m-d');
$stmt4 = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date = ?");
$stmt4->execute([$today_date]);
$todays_appointments = $stmt4->fetchColumn();



// 5. Pending Appointments
$stmt5 = $conn->query("SELECT COUNT(*) FROM appointments WHERE status = 'Pending'");
$pending_appointments = $stmt5->fetchColumn();



// 6. Total Unique Specialties (Specializations)
$stmt6 = $conn->query("SELECT COUNT(DISTINCT specialization) FROM doctors");
$total_specialties = $stmt6->fetchColumn();
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

                    <a href="dashboard.php" class="list-group-item list-group-item-action active py-3 border-0 text-white fw-bold" style="background-color: #0d5c5f;">
                        <i class="fa-solid fa-chart-line me-2"></i> Dashboard
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



    <!-- Admin Dashboard Content -->
    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h2 class="fw-bold mb-1" style="color: #07484a;">Admin Dashboard</h2>
            <p class="text-muted small">Welcome back, Admin! Here is the live system summary.</p>
            <hr class="mb-4">



            <!-- First Row -->
            <div class="row g-3">
                <div class="col-md-4 mb-3">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #07484a;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.9;">Total Doctors</h6>
                                <h2 class="fw-bold mb-0"><?php echo $total_doctors; ?></h2>
                            </div>
                            <i class="fa-solid fa-user-doctor fa-3x" style="opacity: 0.4;"></i>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #198754;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.9;">Total Patients</h6>
                                <h2 class="fw-bold mb-0"><?php echo $total_patients; ?></h2>
                            </div>
                            <i class="fa-solid fa-users fa-3x" style="opacity: 0.4;"></i>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #ffc107;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.9;">Appointments</h6>
                                <h2 class="fw-bold mb-0"><?php echo $total_appointments; ?></h2>
                            </div>
                            <i class="fa-solid fa-calendar-check fa-3x" style="opacity: 0.4;"></i>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Second Row-->
            <div class="row g-3">
                <div class="col-md-4 mb-3">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #0d5c5f;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.9;">Today's Appt.</h6>
                                <h2 class="fw-bold mb-0"><?php echo $todays_appointments; ?></h2>
                            </div>
                            <i class="fa-solid fa-calendar-day fa-3x" style="opacity: 0.4;"></i>
                        </div>
                    </div>
                </div>



                <div class="col-md-4 mb-3">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #dc3545;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.9;">Pending Appt.</h6>
                                <h2 class="fw-bold mb-0"><?php echo $pending_appointments; ?></h2>
                            </div>
                            <i class="fa-solid fa-clock-rotate-left fa-3x" style="opacity: 0.4;"></i>
                        </div>
                    </div>
                </div>



                <div class="col-md-4 mb-3">
                    <div class="card text-white border-0 shadow-sm rounded-3 p-3" style="background-color: #17a2b8;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.9;">Specialties</h6>
                                <h2 class="fw-bold mb-0"><?php echo $total_specialties; ?></h2>
                            </div>
                            <i class="fa-solid fa-stethoscope fa-3x" style="opacity: 0.4;"></i>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>