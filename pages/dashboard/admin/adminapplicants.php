<?php
// pages/dashboard/admin/adminapplicants.php

require_once __DIR__ . '/../../../lib/db.php';
$pdo = getPDO();

$message = "";

// ===== Handle the "Add New Application" form submission =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_application'])) {
    // Retrieve and sanitize form data
    $applicant_id = trim($_POST['applicant_id'] ?? '');
    $vacancy_id   = trim($_POST['vacancy_id'] ?? '');
    $company_id   = trim($_POST['company_id'] ?? '');
    $applied_date = trim($_POST['applied_date'] ?? '');
    $status       = trim($_POST['status'] ?? 'Pending');
    $remarks      = trim($_POST['remarks'] ?? '');

    try {
        $sql = "INSERT INTO job_applications 
                  (applicant_id, vacancy_id, company_id, applied_date, status, remarks)
                VALUES 
                  (:applicant_id, :vacancy_id, :company_id, :applied_date, :status, :remarks)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':applicant_id' => $applicant_id,
            ':vacancy_id'   => $vacancy_id,
            ':company_id'   => $company_id,
            ':applied_date' => $applied_date,
            ':status'       => $status,
            ':remarks'      => $remarks
        ]);
        $message = "Application added successfully!";
    } catch (PDOException $e) {
        $message = "Error adding application: " . $e->getMessage();
    }
}

// ===== Handle deletion of an application =====
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM job_applications WHERE id = :id");
        $stmt->execute([':id' => $delete_id]);
        $message = "Application deleted successfully!";
    } catch (PDOException $e) {
        $message = "Error deleting application: " . $e->getMessage();
    }
}

// ===== Handle searching =====
$searchValue = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "SELECT ja.id AS application_id,
               a.name AS applicant_name,
               v.title AS vacancy_title,
               c.name AS company_name,
               ja.applied_date,
               ja.status,
               ja.remarks
        FROM job_applications ja
        LEFT JOIN applicants a ON ja.applicant_id = a.id
        LEFT JOIN vacancies v ON ja.vacancy_id = v.id
        LEFT JOIN companies c ON ja.company_id = c.id";
if ($searchValue != "") {
    $sql .= " WHERE a.name LIKE :search OR v.title LIKE :search 
              OR c.name LIKE :search OR ja.status LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search' => '%' . $searchValue . '%']);
} else {
    $stmt = $pdo->query($sql);
}
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Job Applications</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Centralized CSS for admin layout -->
  <link rel="stylesheet" href="../../../assets/css/admin.css">
  <!-- Page-specific styles; you can eventually merge these into admin.css -->
 
</head>
<body>
  <!-- Fixed Sidebar -->
  <aside class="sidebar">
    <?php include __DIR__ . '/../../../components/a-sidebar.php'; ?>
  </aside>
  
  <!-- Main Content Container -->
  <div class="container">
    <h2>Job Applications</h2>
    <?php if ($message): ?>
      <div class="flash-message" style="color:green; margin-bottom:10px;">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <!-- Search Form -->
    <div class="search-container">
      <form method="GET" action="adminapplicants.php">
        <label for="search">Search: </label>
        <input type="text" name="search" id="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchValue); ?>">
        <button type="submit">Search</button>
      </form>
    </div>

    <!-- Add New Application Form -->
    <h3>Add New Application</h3>
    <form method="POST" action="adminapplicants.php">
      <label for="applicant_id">Applicant:</label>
      <select name="applicant_id" id="applicant_id" required>
        <?php 
          $stmtApp = $pdo->query("SELECT id, name FROM applicants");
          $applicantsList = $stmtApp->fetchAll(PDO::FETCH_ASSOC);
          foreach ($applicantsList as $applicant) {
              echo '<option value="' . $applicant['id'] . '">' . htmlspecialchars($applicant['name']) . '</option>';
          }
        ?>
      </select><br>

      <label for="vacancy_id">Vacancy:</label>
      <select name="vacancy_id" id="vacancy_id">
        <?php 
          $stmtVac = $pdo->query("SELECT id, title FROM vacancies");
          $vacanciesList = $stmtVac->fetchAll(PDO::FETCH_ASSOC);
          foreach ($vacanciesList as $vacancy) {
              echo '<option value="' . $vacancy['id'] . '">' . htmlspecialchars($vacancy['title']) . '</option>';
          }
        ?>
      </select><br>

      <label for="company_id">Company:</label>
      <select name="company_id" id="company_id">
        <?php 
          $stmtComp = $pdo->query("SELECT id, name FROM companies");
          $companiesList = $stmtComp->fetchAll(PDO::FETCH_ASSOC);
          foreach ($companiesList as $company) {
              echo '<option value="' . $company['id'] . '">' . htmlspecialchars($company['name']) . '</option>';
          }
        ?>
      </select><br>

      <label for="applied_date">Applied Date:</label>
      <input type="date" name="applied_date" id="applied_date" required><br>

      <label for="status">Status:</label>
      <select name="status" id="status">
        <option value="Pending">Pending</option>
        <option value="Reviewed">Reviewed</option>
        <option value="Accepted">Accepted</option>
        <option value="Rejected">Rejected</option>
      </select><br>

      <label for="remarks">Remarks:</label>
      <textarea name="remarks" id="remarks" rows="3"></textarea><br>

      <button type="submit" name="add_application" class="add-btn">Add Application</button>
    </form>

    <!-- Applications Table -->
    <h3>Application List</h3>
    <table>
      <thead>
        <tr>
          <th>Applicant</th>
          <th>Vacancy/Job Title</th>
          <th>Company</th>
          <th>Applied Date</th>
          <th>Status</th>
          <th>Remarks</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($applications): ?>
          <?php foreach ($applications as $app): ?>
          <tr>
            <td><?php echo htmlspecialchars($app['applicant_name']); ?></td>
            <td><?php echo htmlspecialchars($app['vacancy_title'] ?: 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($app['company_name'] ?: 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($app['applied_date']); ?></td>
            <td><?php echo htmlspecialchars($app['status']); ?></td>
            <td><?php echo htmlspecialchars($app['remarks']); ?></td>
            <td>
              <a href="edit_application.php?id=<?php echo $app['application_id']; ?>" class="view-btn">Edit</a> |
              <a href="adminapplicants.php?delete_id=<?php echo $app['application_id']; ?>" class="remove-btn" onclick="return confirm('Are you sure you want to delete this application?');">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">No applications found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
