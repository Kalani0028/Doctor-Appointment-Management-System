<?php 
session_start();
include 'includes/header.php'; 
?>




<!-- Custom CSS Style for Modern Teal Theme -->
<style>
    .hero-banner {
        background: linear-gradient(135deg, #07484a 0%, #0d5c56 100%);
        color: #ffffff;
        border-radius: 20px;
    }
    .btn-warning-custom {
        background-color: #f7be38;
        color: #000;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-warning-custom:hover {
        background-color: #e0aa2b;
        color: #000;
        transform: translateY(-2px);
    }
    .btn-outline-custom {
        border: 2px solid #ffffff;
        color: #ffffff;
        transition: all 0.3s ease;
    }
    .btn-outline-custom:hover {
        background-color: #ffffff;
        color: #0d5c56;
        transform: translateY(-2px);
    }
    .feature-card {
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>





<div class="container my-4">

    <!-- Hero Banner Section -->
    <div class="hero-banner p-5 text-center shadow-lg my-3">
        <div class="py-3">
            <i class="fa-solid fa-heart-pulse fa-3x mb-3 text-warning"></i>
            <h1 class="display-4 fw-bold mb-3">Welcome to MediCare Channelling Center</h1>
            <p class="fs-5 mx-auto w-75 opacity-90 fw-light">
                Avoid long waiting times and uncertainty. Use the MediCare digital system to easily and quickly channel your doctors from the comfort of your home.
            </p>
            

            <div class="mt-4 pt-2">
                <?php if(isset($_SESSION['role'])): ?>
                    <a href="<?php echo $_SESSION['role']; ?>/dashboard.php" class="btn btn-warning-custom btn-lg px-4 py-2 fw-bold rounded-pill shadow">
                        <i class="fa-solid fa-gauge me-2"></i> Go to Dashboard
                    </a>

                <?php else: ?>
                    <a href="login.php" class="btn btn-warning-custom btn-lg px-4 py-2 fw-bold rounded-pill shadow me-2">
                        <i class="fa-solid fa-calendar-check me-2"></i> Book Appointment Now
                    </a>


                    <a href="register.php" class="btn btn-outline-custom btn-lg px-4 py-2 fw-bold rounded-pill">
                        <i class="fa-solid fa-user-plus me-2"></i> Patient Registration
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>




<!-- Quick Features Section -->
<div class="container my-5">
    <div class="row text-center">

        <!-- Card 1: Expert Doctors -->
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white rounded-4 shadow h-100 border border-2 feature-card position-relative overflow-hidden" style="border-color: #0d5c56 !important;">
                <div style="height: 5px; background-color: #0d5c56; position: absolute; top: 0; left: 0; right: 0;"></div>
                <div class="rounded-circle d-inline-flex p-3 mb-3" style="background-color: #e6f2f2;">
                    <i class="fa-solid fa-user-doctor fa-2x" style="color: #0d5c56;"></i>
                </div>
                <h5 class="fw-bold" style="color: #07484a;">Expert Doctors</h5>
                <p class="text-muted small mb-0">Leading medical specialists across various fields can be connected through the system.</p>
            </div>
        </div>



        <!-- Card 2: Easy Booking -->
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white rounded-4 shadow h-100 border border-2 feature-card position-relative overflow-hidden" style="border-color: #f7be38 !important;">
                <div style="height: 5px; background-color: #f7be38; position: absolute; top: 0; left: 0; right: 0;"></div>
                <div class="rounded-circle d-inline-flex p-3 mb-3" style="background-color: #fff8e6;">
                    <i class="fa-solid fa-calendar-check fa-2x" style="color: #f7be38;"></i>
                </div>
                <h5 class="fw-bold" style="color: #07484a;">Easy Booking</h5>
                <p class="text-muted small mb-0">Choose a date and time that is convenient for you and book an appointment in just a few seconds.</p>
            </div>
        </div>



        <!-- Card 3: Secure System -->
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white rounded-4 shadow h-100 border border-2 feature-card position-relative overflow-hidden" style="border-color: #0d5c56 !important;">
                <div style="height: 5px; background-color: #0d5c56; position: absolute; top: 0; left: 0; right: 0;"></div>
                <div class="rounded-circle d-inline-flex p-3 mb-3" style="background-color: #e6f2f2;">
                    <i class="fa-solid fa-shield-halved fa-2x" style="color: #0d5c56;"></i>
                </div>
                <h5 class="fw-bold" style="color: #07484a;">Secure System</h5>
                <p class="text-muted small mb-0">Your personal information and medical records are stored very securely within the system.</p>
            </div>
        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>