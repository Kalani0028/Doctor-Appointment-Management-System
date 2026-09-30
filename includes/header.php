<?php


// Session start 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// Create Base URL
$project_folder = "hospital_db"; 
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/" . trim($project_folder, '/') . "/";
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>public/css/style.css">
</head>
<body class="bg-light">



<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #07484a;">
  <div class="container">


    <a class="navbar-brand fw-bold text-white fs-4" href="<?php echo $base_url; ?>index.php">
        <i class="fa-solid fa-hospital me-2 text-warning"></i>MediCare Channelling Center
    </a>


    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>


    <div class="collapse navbar-collapse" id="navbarNav">

      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2">
          <a class="nav-link text-white fw-semibold px-3" href="<?php echo $base_url; ?>index.php">Home</a>
        </li>
        <li class="nav-item me-2">
          <a class="nav-link text-white fw-semibold px-3" href="<?php echo $base_url; ?>about.php">About</a>
        </li>
        <li class="nav-item me-2">
          <a class="nav-link text-white fw-semibold px-3" href="<?php echo $base_url; ?>contact.php">Contact</a>
        </li>

        
        <?php if(isset($_SESSION['role'])): ?>
            <li class="nav-item">
              <a class="btn fw-bold px-4 ms-2 shadow-sm rounded-pill" style="background-color: #f7be38; color: #000;" href="<?php echo $base_url . $_SESSION['role']; ?>/dashboard.php">
                <i class="fa-solid fa-gauge me-1"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="btn btn-outline-light fw-bold px-3 ms-2 rounded-pill" href="<?php echo $base_url; ?>logout.php">Logout</a>
            </li>

        <?php else: ?>
            <li class="nav-item">
              <a class="btn fw-bold px-4 ms-2 shadow-sm rounded-pill" style="background-color: #f7be38; color: #000;" href="<?php echo $base_url; ?>login.php">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Login
              </a>
            </li>

        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">