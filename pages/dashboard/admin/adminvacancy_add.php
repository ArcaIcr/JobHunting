<?php
// pages/dashboard/admin/adminvacancy_add.php

require_once '../../../lib/auth.php';
requireAdminLogin();

include_once '../../../config/config.php';
require_once '../../../lib/models/Vacancy.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data.
    $company_name     = trim($_POST['company_name'] ?? '');
    $title            = trim($_POST['title'] ?? '');
    $employees_needed = intval($_POST['employees_needed'] ?? 0);
    $salary           = floatval($_POST['salary'] ?? 0);
    $duration         = trim($_POST['duration'] ?? '');
    $qualification    = trim($_POST['qualification'] ?? '');
    $description      = trim($_POST['description'] ?? '');
    $preferred_sex    = trim($_POST['preferred_sex'] ?? '');
    $sector           = trim($_POST['sector'] ?? '');
    
    // Check required fields.
    if ($company_name === '' || $title === '' || $employees_needed <= 0 || $salary <= 0) {
        $error = "Please fill in all required fields correctly.";
    } else {
        $data = [
            'company_name'    => $company_name,
            'title'           => $title,
            'employees_needed'=> $employees_needed,
            'salary'          => $salary,
            'duration'        => $duration,
            'qualification'   => $qualification,
            'description'     => $description,
            'preferred_sex'   => $preferred_sex,
            'sector'          => $sector
        ];
        if (Vacancy::insert($data)) {
            header("Location: adminvacancy.php");
            exit;
        } else {
            $error = "Failed to add vacancy. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Vacancy</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Use external admin.css for consistent styling -->
  <link rel="stylesheet" href="../../../assets/css/admin.css">
</head>
<body>
  <!-- Integrated Sidebar -->
  <aside class="sidebar">
    <?php include __DIR__ . '/../../../components/a-sidebar.php'; ?>
  </aside>

  <div class="main-content">
    <header class="header">
      <h2>Add Vacancy</h2>
    </header>
    <!-- Use the centralized class for the form container -->
    <div class="vacancy-add-content">
      <?php if ($error): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form method="post" action="">
        <label for="company_name">Company Name *</label>
        <input type="text" id="company_name" name="company_name" required>

        <label for="title">Job Title *</label>
        <input type="text" id="title" name="title" required>

        <label for="employees_needed">Number of Employees Required *</label>
        <input type="number" id="employees_needed" name="employees_needed" required>

        <label for="salary">Salary *</label>
        <input type="text" id="salary" name="salary" required>

        <label for="duration">Duration</label>
        <input type="text" id="duration" name="duration">

        <label for="qualification">Qualification/Work Experience</label>
        <textarea id="qualification" name="qualification"></textarea>

        <label for="description">Job Description</label>
        <textarea id="description" name="description"></textarea>

        <label for="preferred_sex">Preferred Sex</label>
        <input type="text" id="preferred_sex" name="preferred_sex">

        <label for="sector">Sector</label>
        <input type="text" id="sector" name="sector">

        <button type="submit">Add Vacancy</button>
      </form>
    </div>
  </div>
</body>
</html>
