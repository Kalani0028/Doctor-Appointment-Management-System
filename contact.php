<?php 
session_start();
include 'includes/header.php'; 


// Success Message for feedbacks
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $msg = "Your message has been successfully received! One of our representatives will contact you very soon.";
}
?>




<!-- Full Background Image Section -->
<div class="py-5 position-relative" 
     style="background: linear-gradient(rgba(7, 72, 74, 0.85), rgba(7, 72, 74, 0.85)), url('images/contact.jpg') no-repeat center center/cover; min-height: 85vh;">
    

    <div class="container my-3">
        <!-- Header Section -->
        <div class="text-center mb-5 text-white">
            <h1 class="fw-bold display-4 text-warning">Contact Us</h1>
            <p class="lead text-light">We are here to help you 24/7. Get in touch with us.</p>
            <hr class="w-25 mx-auto border-warning border-2 opacity-100">
        </div>



        <div class="row">
            <!-- Contact Details  -->
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="card border-0 shadow-lg p-4 bg-white h-100 rounded-3">
                    <h4 class="fw-bold mb-4" style="color: #07484a;">Contact Information</h4>
                    
                    <div class="d-flex align-items-start mb-4">
                        <i class="fa-solid fa-location-dot fa-lg me-3 mt-1" style="color: #07484a;"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Our Address</h6>
                            <p class="text-muted mb-0">MediCare Hospital, Beliatta Road, Tangalle, Sri Lanka.</p>
                        </div>
                    </div>



                    <div class="d-flex align-items-start mb-4">
                        <i class="fa-solid fa-phone fa-lg me-3 mt-1" style="color: #07484a;"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Phone Numbers</h6>
                            <p class="text-muted mb-0">+94 11 234 5678<br>+94 11 987 6543</p>
                        </div>
                    </div>



                    <div class="d-flex align-items-start mb-4">
                        <i class="fa-solid fa-envelope fa-lg me-3 mt-1" style="color: #07484a;"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Email Support</h6>
                            <p class="text-muted mb-0">info@medicare.lk<br>support@medicare.lk</p>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Message Contact Form -->
            <div class="col-md-7">
                <div class="card border-0 shadow-lg p-4 bg-white h-100 rounded-3">
                    <h4 class="fw-bold mb-3" style="color: #07484a;">Send Us a Message</h4>
                    <p class="text-muted small mb-4">Please report any issues with the system or services below.</p>

                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>




                    <form action="contact.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Your Name</label>
                            <input type="text" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email Address</label>
                            <input type="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Subject</label>
                            <input type="text" class="form-control" placeholder="What is this about?" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Message / Inquiry</label>
                            <textarea class="form-control" rows="4" placeholder="Type your message here..." required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn text-white px-4 fw-bold" style="background-color: #07484a;">
                                <i class="fa-solid fa-paper-plane me-2 text-warning"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>