<?php
session_start();
include '../config/db.php';



if (!isset($_SESSION['patient_id']) && !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'] ?? $_SESSION['user_id'];
$appointment_id = $_GET['id'] ?? 0;



// Get  Appointment details
$stmt = $conn->prepare("
    SELECT a.*, p.name AS patient_name, p.phone AS patient_phone, d.name AS doctor_name, d.specialization, d.fees 
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE a.id = ? AND a.patient_id = ?
");
$stmt->execute([$appointment_id, $patient_id]);
$receipt = $stmt->fetch();




if (!$receipt) {
    echo "<h3 style='text-align:center; margin-top:50px;'>Receipt not found!</h3>";
    exit();
}



$trans_no = str_pad($receipt['id'], 10, "60000", STR_PAD_LEFT);
$fee = $receipt['fee'] > 0 ? $receipt['fee'] : ($receipt['fees'] ?? 100.00);
$payment_method = !empty($receipt['payment_method']) ? strtoupper($receipt['payment_method']) : 'CARD';
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?php echo $trans_no; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #2b3036;
            font-family: 'Courier New', Courier, monospace;
        }
        .receipt-card {
            width: 360px;
            background: #ffffff;
            margin: 40px auto;
            padding: 25px 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: #000;
        }
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .double-line {
            border-top: 3px double #000;
            margin: 8px 0;
        }
        @media print {
            body {
                background: none;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none;
                margin: 0 auto;
                width: 100%;
            }
        }
    </style>
</head>



<body>

<div class="container">
    <!-- Buttons -->
    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-success fw-bold me-2" style="background-color: #07484a; border:none;">
            <i class="fa-solid fa-print me-1"></i> Print / Download PDF
        </button>
        <a href="book_appointment.php" class="btn btn-light fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>




    <!-- Receipt Layout -->
    <div class="receipt-card">
        <div class="text-center fw-bold fs-6">
            <?php echo ($payment_method === 'CARD') ? 'CARD PAYMENT RECEIPT' : 'PAYMENT RECEIPT'; ?>
        </div>
        <div class="text-center small text-uppercase font-monospace fw-bold mt-1">
            STATUS: <span class="badge bg-dark text-white">PAID ONLINE</span>
        </div>

        <div class="dashed-line"></div>

        <div class="d-flex justify-content-between small fw-bold">
            <span>DATE: <?php echo date('m/d/Y', strtotime($receipt['appointment_date'])); ?></span>
            <span>TIME: <?php echo date('h:i:s A', strtotime($receipt['appointment_time'])); ?></span>
        </div>

        <div class="d-flex justify-content-between small fw-bold mt-1">
            <span>TRANS #</span>
            <span><?php echo $trans_no; ?></span>
        </div>

        <div class="dashed-line"></div>

        <div class="small fw-bold">
            PAID BY:<br>
            <span class="fw-normal"><?php echo htmlspecialchars($receipt['patient_name']); ?></span>
        </div>

        <div class="small fw-bold mt-2">
            FOR DOCTOR:<br>
            <span class="fw-normal">Dr. <?php echo htmlspecialchars($receipt['doctor_name']); ?> (<?php echo htmlspecialchars($receipt['specialization']); ?>)</span>
        </div>

        <div class="dashed-line"></div>

        <div class="d-flex justify-content-between fw-bold small">
            <span>DOCTOR FEE</span>
            <span>Rs. <?php echo number_format($fee, 2); ?></span>
        </div>

        <div class="double-line"></div>

        <div class="d-flex justify-content-between fw-bold fs-6">
            <span>TOTAL PAID</span>
            <span>Rs. <?php echo number_format($fee, 2); ?></span>
        </div>

        <div class="d-flex justify-content-between small mt-2">
            <span>PAYMENT METHOD</span>
            <span>CREDIT/DEBIT CARD</span>
        </div>
        <div class="d-flex justify-content-between small">
            <span>TRANSACTION</span>
            <span>SUCCESSFUL</span>
        </div>

        <div class="dashed-line"></div>




        <div class="text-center small fw-bold mt-3">
            *** ELECTRONICALLY VERIFIED ***
        </div>
        <div class="text-center small text-muted mt-1" style="font-size: 11px;">
            Thank you for booking with us!
        </div>

        <div class="dashed-line mt-3"></div>
    </div>
</div>

</body>
</html>