<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$success_msg = "";
$error_msg = "";



if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
} elseif (isset($_SESSION['user_id'])) {
    $patient_id = $_SESSION['user_id'];
} else {
    header("Location: ../login.php");
    exit();
}

$patient_stmt = $conn->prepare("SELECT name, email, phone FROM patients WHERE id = ?");
$patient_stmt->execute([$patient_id]);
$patient_data = $patient_stmt->fetch();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $problem = trim($_POST['problem']);
    $payment_method = $_POST['payment_method'] ?? 'Cash';
    $status = "Pending";

    $doc_stmt = $conn->prepare("SELECT fees FROM doctors WHERE id = ?");
    $doc_stmt->execute([$doctor_id]);
    $doc_data = $doc_stmt->fetch();
    $fee = $doc_data['fees'] ?? 0;




    try {
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, problem, payment_method, fee, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$patient_id, $doctor_id, $appointment_date, $appointment_time, $problem, $payment_method, $fee, $status])) {
            $last_id = $conn->lastInsertId();


            
            // A receipt is sent only for card payments.
            if ($payment_method === 'Card') {
                header("Location: print_receipt.php?id=" . $last_id);
                exit();
            } else {

                // If Cash Payment , Show Success message 
                $success_msg = "Appointment booked successfully! Please pay cash when visiting the clinic.";
            }
        } else {
            $error_msg = "An error occurred while booking the appointment. Please try again.";
        }
    } catch (PDOException $e) {
        $error_msg = "Error: " . $e->getMessage();
    }
}



$query = $conn->query("SELECT id, name, specialization, fees FROM doctors ORDER BY name ASC");
$doctors = $query->fetchAll();
?>

<style>
    .payment-card {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 15px;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        background-color: #fafafa;
    }
    .payment-card:hover {
        border-color: #07484a;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>




<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item text-white text-center py-3 fw-bold" style="background-color: #07484a;">
                        Patient Navigation
                    </div>
                    <a href="dashboard.php" class="list-group-item list-group-item-action py-3">
                        <i class="fa-solid fa-gauge me-2" style="color: #07484a;"></i> My Dashboard
                    </a>
                    <a href="book_appointment.php" class="list-group-item list-group-item-action active py-3 border-0 text-white" style="background-color: #07484a;">
                        <i class="fa-solid fa-calendar-plus me-2"></i> Book Appointment
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




    <div class="col-md-9">
        <div class="card shadow-sm border-0 p-4 bg-white mb-4 rounded-4">
            <h3 class="fw-bold mb-2" style="color: #07484a;">Book New Appointment</h3>
            <p class="text-muted small">Choose a doctor and book a date and time that is convenient for you.</p>
            <hr class="mb-4">

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 p-3" role="alert">
                    <i class="fa-solid fa-circle-check me-2 fs-5"></i> <?php echo $success_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2 fs-5"></i> <?php echo $error_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>



            <form action="book_appointment.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Your Name</label>
                        <input type="text" name="name" class="form-control py-2" style="border-radius: 8px;" value="<?php echo htmlspecialchars($patient_data['name'] ?? ''); ?>" required readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Your Email</label>
                        <input type="email" name="email" class="form-control py-2" style="border-radius: 8px;" value="<?php echo htmlspecialchars($patient_data['email'] ?? ''); ?>" required readonly>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Your Mobile</label>
                        <input type="text" name="phone" class="form-control py-2" style="border-radius: 8px;" value="<?php echo htmlspecialchars($patient_data['phone'] ?? ''); ?>" required readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Choose Doctor</label>
                        <select name="doctor_id" class="form-select py-2" style="border-radius: 8px;" required>
                            <option value="">-- Choose Doctor --</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo $doctor['id']; ?>">
                                    Dr. <?php echo htmlspecialchars($doctor['name']); ?> (<?php echo htmlspecialchars($doctor['specialization']); ?>) - Rs. <?php echo number_format($doctor['fees'], 2); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Appointment Date</label>
                        <input type="date" name="appointment_date" class="form-control py-2" style="border-radius: 8px;" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Preferred Time</label>
                        <input type="time" name="appointment_time" class="form-control py-2" style="border-radius: 8px;" required>
                    </div>
                </div>




                <!-- Payment Selection -->
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary mb-2">Select Payment Method</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="payment-card d-flex align-items-center w-100 h-100" for="payCash">
                                <i class="fa-solid fa-money-bill-wave text-success fs-2 me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Cash on Clinic</div>
                                    <div class="text-muted small">Pay cash directly when visiting</div>
                                </div>
                                <input class="form-check-input ms-2 fs-5" type="radio" name="payment_method" id="payCash" value="Cash" checked onclick="toggleCardDetails(false)">
                            </label>
                        </div>



                        <div class="col-md-6">
                            <label class="payment-card d-flex align-items-center w-100 h-100" for="payCard">
                                <i class="fa-regular fa-credit-card text-primary fs-2 me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Credit / Debit Card</div>
                                    <div class="text-muted small">Pay online & get instant receipt</div>
                                </div>
                                <input class="form-check-input ms-2 fs-5" type="radio" name="payment_method" id="payCard" value="Card" onclick="toggleCardDetails(true)">
                            </label>
                        </div>
                    </div>
                </div>




                <!-- Card Details Section  -->
                <div id="cardDetailsSection" class="p-3 border rounded-3 bg-light mb-3 d-none">
                    <h6 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-lock me-2"></i>Enter Card Details</h6>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Card Number</label>
                        <input type="text" id="card_number" class="form-control" placeholder="1234 5678 9101 1121" maxlength="19">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold">Expiry Date</label>
                            <input type="text" id="card_expiry" class="form-control" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold">CVV</label>
                            <input type="password" id="card_cvv" class="form-control" placeholder="123" maxlength="4">
                        </div>
                    </div>
                </div>



                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Describe your problem</label>
                    <textarea name="problem" class="form-control" rows="3" style="border-radius: 8px;" placeholder="Describe your problem..."></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" id="submitBtn" class="btn w-100 py-2.5 fw-bold text-white shadow-sm" style="background-color: #07484a; border-radius: 8px;">
                        <i class="fa-solid fa-calendar-check me-2"></i> Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>





<script>
function toggleCardDetails(show) {
    const cardSection = document.getElementById('cardDetailsSection');
    const submitBtn = document.getElementById('submitBtn');
    const cardInputs = cardSection.querySelectorAll('input');

    if (show) {
        cardSection.classList.remove('d-none');
        submitBtn.innerHTML = '<i class="fa-solid fa-credit-card me-2"></i> Pay & Generate Receipt';
        cardInputs.forEach(input => input.required = true);
    } else {
        cardSection.classList.add('d-none');
        submitBtn.innerHTML = '<i class="fa-solid fa-calendar-check me-2"></i> Book Appointment';
        cardInputs.forEach(input => {
            input.required = false;
            input.value = '';
        });
    }
}
</script>

<?php include '../includes/footer.php'; ?>