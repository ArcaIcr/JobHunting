<?php
// pages/dashboard/admin/adminvacancy.php

// 1. Restrict to admin users
require_once '../../../lib/auth.php';
requireAdminLogin();

// 2. Connect to DB and fetch vacancy data
include_once '../../../config/config.php';
require_once '../../../lib/models/Vacancy.php';
$vacancies = Vacancy::getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vacancy Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Google Fonts for a modern look -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <!-- External Admin CSS -->
  <link rel="stylesheet" href="../../../assets/css/admin.css">
  <!-- Additional Page-Specific Styles -->
  
</head>
<body>
  <!-- Integrated Sidebar -->
  <aside class="sidebar">
    <?php include __DIR__ . '/../../../components/a-sidebar.php'; ?>
  </aside>

  <main class="main-content">
    <header class="header">
      <h2>Vacancy Management</h2>
      <div class="search-box">
        <input type="text" id="search" placeholder="Search vacancies...">
      </div>
    </header>
    
    <div class="content">
      <a href="adminvacancy_add.php" class="add-btn">+ Add Job Vacancy</a>
      
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Company</th>
              <th>Title</th>
              <th>Needed</th>
              <th>Salary</th>
              <th>Duration</th>
              <th>Qual./Exp.</th>
              <th>Description</th>
              <th>Pref. Sex</th>
              <th>Sector</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="vacancyList">
            <?php foreach ($vacancies as $vac): ?>
              <tr>
                <td data-label="Company"><?php echo htmlspecialchars($vac['company_name']); ?></td>
                <td data-label="Title"><?php echo htmlspecialchars($vac['title']); ?></td>
                <td data-label="Needed"><?php echo htmlspecialchars($vac['employees_needed']); ?></td>
                <td data-label="Salary"><?php echo htmlspecialchars($vac['salary']); ?></td>
                <td data-label="Duration"><?php echo htmlspecialchars($vac['duration']); ?></td>
                <td data-label="Qual./Exp."><?php echo htmlspecialchars($vac['qualification']); ?></td>
                <td data-label="Description"><?php echo htmlspecialchars($vac['description']); ?></td>
                <td data-label="Pref. Sex"><?php echo htmlspecialchars($vac['preferred_sex']); ?></td>
                <td data-label="Sector"><?php echo htmlspecialchars($vac['sector']); ?></td>
                <td data-label="Action">
                  <a href="adminvacancy_edit.php?id=<?php echo $vac['id']; ?>">
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                  </a>
                  <!-- The delete button now uses a data-id attribute and a special class -->
                  <a href="javascript:void(0);" class="delete-btn" data-id="<?php echo $vac['id']; ?>">
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($vacancies)): ?>
              <tr>
                <td colspan="10" style="text-align: center; padding: 20px;">
                  No vacancies found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <span class="modal-close">&times;</span>
      <h3>Confirm Deletion</h3>
      <p>Are you sure you want to delete this vacancy?</p>
      <div class="modal-actions">
        <button id="confirmDelete" class="action-btn delete">Yes, Delete</button>
        <button id="cancelDelete" class="action-btn" style="background: var(--accent-bg);">Cancel</button>
      </div>
    </div>
  </div>
  
  <!-- JavaScript for Modal Confirmation and Search -->
  <script>
    // Client-side search functionality
    document.getElementById("search").addEventListener("input", function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("#vacancyList tr");
      rows.forEach(row => {
        const jobTitle = row.cells[1].textContent.toLowerCase();
        row.style.display = jobTitle.includes(filter) ? "" : "none";
      });
    });

    // Modal deletion functionality
    document.addEventListener("DOMContentLoaded", function() {
      const modal = document.getElementById("deleteModal");
      const confirmDeleteBtn = document.getElementById("confirmDelete");
      const cancelDeleteBtn = document.getElementById("cancelDelete");
      const modalCloseBtn = document.querySelector(".modal-close");
      let vacancyIdToDelete = null;
      
      // Open modal when any delete button is clicked
      document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", function() {
          vacancyIdToDelete = this.getAttribute("data-id");
          modal.style.display = "block";
        });
      });
      
      // Confirm deletion: Redirect to the delete endpoint
      confirmDeleteBtn.addEventListener("click", function() {
        window.location.href = "adminvacancy_delete.php?id=" + vacancyIdToDelete;
      });
      
      // Function to close modal
      function closeModal() {
        modal.style.display = "none";
        vacancyIdToDelete = null;
      }
      
      cancelDeleteBtn.addEventListener("click", closeModal);
      modalCloseBtn.addEventListener("click", closeModal);
      
      // Close modal if user clicks outside modal content
      window.addEventListener("click", function(e) {
        if (e.target === modal) {
          closeModal();
        }
      });
    });
  </script>
</body>
</html>
