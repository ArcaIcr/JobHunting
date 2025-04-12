<?php
// pages/dashboard/admin/adminvacancy_edit.php

require_once '../../../lib/auth.php';
requireAdminLogin();

include_once '../../../config/config.php';
require_once '../../../lib/models/Vacancy.php';

$error = '';
$id = $_GET['id'] ?? 0;
$vacancy = Vacancy::getById($id);
if (!$vacancy) {
    header("Location: adminvacancy.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $company_name     = trim($_POST['company_name'] ?? '');
    $title            = trim($_POST['title'] ?? '');
    $employees_needed = intval($_POST['employees_needed'] ?? 0);
    $salary           = floatval($_POST['salary'] ?? 0);
    $duration         = trim($_POST['duration'] ?? '');
    $qualification    = trim($_POST['qualification'] ?? '');
    $description      = trim($_POST['description'] ?? '');
    $preferred_sex    = trim($_POST['preferred_sex'] ?? '');
    $sector           = trim($_POST['sector'] ?? '');
    
    if ($company_name == '' || $title == '' || $employees_needed <= 0 || $salary <= 0) {
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
        if (Vacancy::update($id, $data)) {
            header("Location: adminvacancy.php");
            exit;
        } else {
            $error = "Failed to update vacancy. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Vacancy</title>
  <link rel="stylesheet" href="../../../assets/css/admin.css">
</head>
<body>
  <?php include __DIR__ . '/../../../components/a-sidebar.php'; ?>
  <div class="main-content">
    <header>
      <h2>Edit Vacancy</h2>
    </header>
    <div class="content">
      <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form method="post" action="">
        <label>Company Name</label><br>
        <input type="text" name="company_name" value="<?php echo htmlspecialchars($vacancy['company_name']); ?>" required><br><br>

        <label>Job Title</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($vacancy['title']); ?>" required><br><br>

        <label>Number of Employees Required</label><br>
        <input type="number" name="employees_needed" value="<?php echo htmlspecialchars($vacancy['employees_needed']); ?>" required><br><br>

        <label>Salary</label><br>
        <input type="text" name="salary" value="<?php echo htmlspecialchars($vacancy['salary']); ?>" required><br><br>

        <label>Duration</label><br>
        <input type="text" name="duration" value="<?php echo htmlspecialchars($vacancy['duration']); ?>"><br><br>

        <label>Qualification/Experience</label><br>
        <textarea name="qualification"><?php echo htmlspecialchars($vacancy['qualification']); ?></textarea><br><br>

        <label>Job Description</label><br>
        <textarea name="description"><?php echo htmlspecialchars($vacancy['description']); ?></textarea><br><br>

        <label>Preferred Sex</label><br>
        <input type="text" name="preferred_sex" value="<?php echo htmlspecialchars($vacancy['preferred_sex']); ?>"><br><br>

        <label>Sector</label><br>
        <input type="text" name="sector" value="<?php echo htmlspecialchars($vacancy['sector']); ?>"><br><br>

        <button type="submit">Update Vacancy</button>
      </form>
    </div>
  </div>
</body>
</html>
