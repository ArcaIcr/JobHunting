<?php
// pages/dashboard/admin/index.php

require_once '../../../lib/auth.php';
requireRole('admin');
requireAdminLogin();

if (getUserRole() !== 'admin') {
    header("Location: /pages/user/adminlogin.php");
    exit;
}

// Include the model file for companies
require_once __DIR__ . '/../../../lib/models/company_model.php';

// ----------------------------------------
// Handle form submissions (Add & Edit)
// ----------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'addCompany') {
        // Use addCompany() from the model
        addCompany($_POST['name'], $_POST['address'], $_POST['contact']);
        header("Location: index.php");
        exit;
    }
    if (isset($_POST['action']) && $_POST['action'] === 'editCompany') {
        // Use updateCompany() from the model
        updateCompany($_POST['id'], $_POST['name'], $_POST['address'], $_POST['contact']);
        header("Location: index.php");
        exit;
    }
}

// ----------------------------------------
// Handle Delete
// ----------------------------------------
if (isset($_GET['delete_id'])) {
    deleteCompany((int)$_GET['delete_id']);
    header("Location: index.php");
    exit;
}

// ----------------------------------------
// Check for Edit Mode
// ----------------------------------------
$editCompany = null;
if (isset($_GET['edit_id'])) {
    $editCompany = getCompanyById((int)$_GET['edit_id']);
}

// ----------------------------------------
// Fetch all companies for display
// ----------------------------------------
$companies = getAllCompanies();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Management</title>
  <!-- External CSS Files -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../../assets/css/admin.css">
</head>
<body>
  <?php include __DIR__ . '/../../../components/a-sidebar.php'; ?>
  
  <div class="main-content">
    <header>
      <h2>Cooperation</h2>
      <div class="user-info">
        <?php if (isset($_SESSION['loggedInUser']['email'])): ?>
          <span><?php echo htmlspecialchars($_SESSION['loggedInUser']['email']); ?></span>
        <?php endif; ?>
        <a class="logout-link" href="../../../components/a-logout.php">Logout</a>
      </div>
    </header>
    
    <section class="company-section">
      <h3>List of Cooperation</h3>
      
      <?php if ($editCompany): ?>
      <!-- Edit Form -->
      <div class="form-wrapper">
        <form method="POST">
          <input type="hidden" name="action" value="editCompany">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($editCompany['id']); ?>">
          <input type="text" name="name" value="<?php echo htmlspecialchars($editCompany['name']); ?>" placeholder="Company Name" required>
          <input type="text" name="address" value="<?php echo htmlspecialchars($editCompany['address']); ?>" placeholder="Address" required>
          <input type="text" name="contact" value="<?php echo htmlspecialchars($editCompany['contact']); ?>" placeholder="Contact No." required>
          <button type="submit" id="editCompany">Update Company</button>
          <a href="index.php" style="margin-left:10px;">Cancel</a>
        </form>
      </div>
      <?php else: ?>
      <!-- Add Form -->
      <div class="form-wrapper">
        <form method="POST">
          <input type="hidden" name="action" value="addCompany">
          <input type="text" name="name" placeholder="Company Name" required>
          <input type="text" name="address" placeholder="Address" required>
          <input type="text" name="contact" placeholder="Contact No." required>
          <button type="submit" id="addCompany">+ Add Cooperation</button>
        </form>
      </div>
      <?php endif; ?>
      
      <!-- Optional Client-Side Search -->
      <input type="text" id="search" placeholder="Search...">
      
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Contact No.</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="companyList">
          <?php if ($companies): ?>
            <?php foreach ($companies as $company): ?>
              <tr>
                <td><?php echo htmlspecialchars($company['name']); ?></td>
                <td><?php echo htmlspecialchars($company['address']); ?></td>
                <td><?php echo htmlspecialchars($company['contact']); ?></td>
                <td>
                  <a class="action-btn edit" href="index.php?edit_id=<?php echo (int)$company['id']; ?>">Edit</a>
                  <a class="action-btn delete" href="index.php?delete_id=<?php echo (int)$company['id']; ?>" onclick="return confirm('Are you sure you want to delete this company?');">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4">No companies found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </div>
  
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const searchInput = document.getElementById("search");
      const companyList = document.getElementById("companyList");
  
      searchInput.addEventListener("input", function () {
        let filter = searchInput.value.toLowerCase();
        let rows = companyList.getElementsByTagName("tr");
  
        for (let row of rows) {
          let nameCell = row.getElementsByTagName("td")[0];
          if (nameCell) {
            let name = nameCell.textContent.toLowerCase();
            row.style.display = name.includes(filter) ? "" : "none";
          }
        }
      });
    });
  </script>
</body>
</html>
