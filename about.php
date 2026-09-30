<?php 
session_start();
include 'includes/header.php'; 
?>



<div class="container my-5">
    <!-- Header Section With Background Image -->
    <div class="text-center mb-5 p-5 text-white rounded-3 shadow-sm position-relative overflow-hidden" 
         style="background: linear-gradient(rgba(7, 72, 74, 0.8), rgba(7, 72, 74, 0.8)), url('images/about-bg.jpg') no-repeat center center/cover;">
        <h1 class="fw-bold display-4 text-warning mb-2">About MediCare !</h1>
        <p class="lead text-light">Your Trusted Partner in Modern Healthcare & Channeling</p>
        <hr class="w-25 mx-auto border-warning border-2 opacity-100">
    </div>



    <!-- Main Intro -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <h3 class="fw-bold text-secondary mb-3">Who We Are ?</h3>
            <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                MediCare Channelling System is a modern digital system that allows patients to book appointments with doctors easily and securely. Our primary goal is to simplify traditional channeling methods and connect the patient, doctor, and hospital administration into a single network.
            </p>
            <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                We are constantly committed to bringing medical services closer to the public in a more efficient and transparent manner, with the advancement of technology.
            </p>
        </div>



        <div class="col-md-6 text-center">
            <!-- Add image for icon-->
            <div class="p-2 bg-light rounded shadow-sm">
                <img src="images/2.jpg" alt="MediCare Hospital" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>



    <!-- Vision & Mission Cards -->
    <div class="row text-center mt-4">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm p-4 text-white" style="background-color: #07484a;">
                <div class="card-body">
                    <i class="fa-solid fa-eye fa-3x mb-3 text-warning"></i>
                    <h4 class="fw-bold card-title">Our Vision </h4>
                    <p class="card-text opacity-90 mt-2">
                        To become the leading and most trusted digital healthcare provider in Sri Lanka, providing quality medical services to every citizen instantly.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm p-4 text-white" style="background-color: #0d5c56;">
                <div class="card-body">
                    <i class="fa-solid fa-bullseye fa-3x mb-3 text-warning"></i>
                    <h4 class="fw-bold card-title">Our Mission </h4>
                    <p class="card-text opacity-90 mt-2">
                        Simplify the process of booking medical appointments using modern technology, save patient time, and maintain hospital management with maximum efficiency.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>