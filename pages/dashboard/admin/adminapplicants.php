<?php
// adminapplicants.php
// Include the shared database connection from your lib/db.php file.
require_once __DIR__ . '/../../../lib/db.php';
$pdo = getPDO();

// Initialize a status message variable
$message = "";

// ===== Handle the "Add New Application" form submission =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_application'])) {
    // Retrieve and sanitize form data (further sanitization may be added as needed)
    $applicant_id = $_POST['applicant_id'] ?? '';
    $vacancy_id   = $_POST['vacancy_id'] ?? '';    // Optional: if you have a vacancies table
    $company_id   = $_POST['company_id'] ?? '';      // Optional: if you have a companies table
    $applied_date = $_POST['applied_date'] ?? '';
    $status       = $_POST['status'] ?? 'Pending';
    $remarks      = $_POST['remarks'] ?? '';

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
               v.title AS vacancy_title,   -- from vacancies table
               c.name AS company_name,      -- from companies table
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
  <style>
    /* Basic reset and layout */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
    }
    .sidebar {
      width: 250px;
      background: #2c3e50;
      color: white;
      padding: 20px;
      height: 100vh;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      padding: 10px;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
    }
    .sidebar ul li:hover {
      background: #34495e;
    }
    .container {
      flex: 1;
      padding: 20px;
    }
    .container {
      width: 90%;
      margin: 20px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2, h3 {
      color: #333;
      margin-bottom: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    th {
      background: #007bff;
      color: white;
    }
    .action-btn {
      padding: 5px 10px;
      margin-right: 5px;
      border: none;
      cursor: pointer;
      border-radius: 4px;
      color: white;
    }
    .view-btn { background: #17a2b8; }
    .remove-btn { background: #dc3545; }
    .search-container {
      margin-bottom: 10px;
      text-align: right;
    }
    input[type="text"],
    input[type="date"],
    select,
    textarea {
      padding: 5px;
      width: 200px;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>ADMIN</h2>
    <ul>
      <li><a href="adminhome.php">Cooperation</a></li>
      <li><a href="adminvacancy.php">Vacancy</a></li>
      <li><a href="adminemployee.php">Employee</a></li>
      <li><a href="adminapplicants.php">Applicants</a></li>
      <!-- Removed Applications link from the sidebar -->
      <li><a href="adminmanageuser.php">Manage Users</a></li>
    </ul>
  </div>

  <!-- Main Content Container -->
  <div class="container">
    <h2>Job Applications</h2>
    <?php if ($message): ?>
      <div style="color:green; margin-bottom:10px;"><?php echo htmlspecialchars($message); ?></div>
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
      <!-- Populate the Applicant Select List -->
      <label for="applicant_id">Applicant:</label>
      <select name="applicant_id" id="applicant_id" required>
        <?php 
          // Get the list of applicants from your existing applicants table
          $stmtApp = $pdo->query("SELECT id, name FROM applicants");
          $applicantsList = $stmtApp->fetchAll(PDO::FETCH_ASSOC);
          foreach ($applicantsList as $applicant) {
              echo '<option value="' . $applicant['id'] . '">' . htmlspecialchars($applicant['name']) . '</option>';
          }
        ?>
      </select><br>

      <!-- Populate the Vacancy Select List (Optional) -->
      <label for="vacancy_id">Vacancy:</label>
      <select name="vacancy_id" id="vacancy_id">
        <?php 
          // Get the list of vacancies (if you have a vacancies table)
          $stmtVac = $pdo->query("SELECT id, title FROM vacancies");
          $vacanciesList = $stmtVac->fetchAll(PDO::FETCH_ASSOC);
          foreach ($vacanciesList as $vacancy) {
              echo '<option value="' . $vacancy['id'] . '">' . htmlspecialchars($vacancy['title']) . '</option>';
          }
        ?>
      </select><br>

      <!-- Populate the Company Select List (Optional) -->
      <label for="company_id">Company:</label>
      <select name="company_id" id="company_id">
        <?php 
          // Get the list of companies (if you have a companies table)
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

      <button type="submit" name="add_application">Add Application</button>
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
              <!-- Links for edit (if implemented) and delete -->
              <a href="edit_application.php?id=<?php echo $app['application_id']; ?>">Edit</a> |
              <a href="adminapplicants.php?delete_id=<?php echo $app['application_id']; ?>" onclick="return confirm('Are you sure you want to delete this application?');">Delete</a>
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
