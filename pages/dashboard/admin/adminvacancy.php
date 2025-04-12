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
  <!-- Optional: FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Optional: Google Fonts for a modern look -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <style>
    /* RESET */
    * {
      margin: 0; 
      padding: 0; 
      box-sizing: border-box;
    }
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f6f9;
      color: #333;
      min-height: 100vh;
      display: flex;
    }

    /* SIDEBAR (If you have a-sidebar.php, you might remove or override these.) */
    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      color: #fff;
      padding: 20px;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 15px;
    }
    .sidebar ul {
      list-style: none;
    }
    .sidebar ul li {
      margin: 10px 0;
    }
    .sidebar ul li a {
      color: #fff;
      text-decoration: none;
      display: block;
    }
    .sidebar ul li:hover {
      background-color: #34495e;
    }

    /* MAIN CONTENT WRAPPER */
    .main-content {
      flex: 1;
      padding: 20px;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #ccc;
    }
    header h2 {
      font-size: 24px;
      font-weight: 500;
      color: #2c3e50;
    }
    .search-box {
      position: relative;
    }
    .search-box input {
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 6px 14px;
      outline: none;
      font-size: 14px;
      width: 220px;
    }

    .content {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* ACTION BUTTON (Add Vacancy) */
    .add-btn {
      display: inline-block;
      text-decoration: none;
      background-color: #3498db;
      color: #fff;
      padding: 8px 16px;
      border-radius: 4px;
      font-weight: 500;
      margin-bottom: 20px;
    }
    .add-btn:hover {
      background-color: #2980b9;
    }

    /* TABLE STYLES */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    thead {
      background-color: #2c3e50;
      color: #fff;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tbody tr:hover {
      background-color: #f1f1f1;
    }

    /* ACTION BUTTONS */
    .edit, .delete {
      border: none;
      padding: 6px 10px;
      margin: 0 2px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      color: #fff;
    }
    .edit {
      background-color: #27ae60;
    }
    .delete {
      background-color: #e74c3c;
    }
    .edit:hover, .delete:hover {
      opacity: 0.8;
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        display: flex;
        justify-content: space-around;
      }
      .main-content {
        margin: 0;
        padding: 10px;
      }
      header h2 {
        font-size: 18px;
      }
      .search-box input {
        width: 150px;
      }
      table, th, td {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
  <!-- If you have a separate a-sidebar.php, you can remove this and include it instead -->
  <div class="sidebar">
    <h2>ADMIN</h2>
    <ul>
      <li><a href="adminhome.php">Cooperation</a></li>
      <li><a href="adminvacancy.php" class="active">Vacancy</a></li>
      <li><a href="adminemployee.php">Employee</a></li>
      <li><a href="adminapplicants.php">Applicants</a></li>
      <li><a href="adminmanageuser.php">Manage Users</a></li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <h2>Vacancy Management</h2>
      <div class="search-box">
        <input type="text" id="search" placeholder="Search vacancies...">
      </div>
    </header>
    
    <div class="content">
      <a href="adminvacancy_add.php" class="add-btn">+ Add Job Vacancy</a>
      <table>
        <thead>
          <tr>
            <th>Company Name</th>
            <th>Job Title</th>
            <th>Employees</th>
            <th>Salary</th>
            <th>Duration</th>
            <th>Qualification/Experience</th>
            <th>Description</th>
            <th>Preferred Sex</th>
            <th>Sector</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="vacancyList">
          <?php foreach ($vacancies as $vac): ?>
            <tr>
              <td><?php echo htmlspecialchars($vac['company_name']); ?></td>
              <td><?php echo htmlspecialchars($vac['title']); ?></td>
              <td><?php echo htmlspecialchars($vac['employees_needed']); ?></td>
              <td><?php echo htmlspecialchars($vac['salary']); ?></td>
              <td><?php echo htmlspecialchars($vac['duration']); ?></td>
              <td><?php echo htmlspecialchars($vac['qualification']); ?></td>
              <td><?php echo htmlspecialchars($vac['description']); ?></td>
              <td><?php echo htmlspecialchars($vac['preferred_sex']); ?></td>
              <td><?php echo htmlspecialchars($vac['sector']); ?></td>
              <td>
                <a href="adminvacancy_edit.php?id=<?php echo $vac['id']; ?>">
                  <button class="edit"><i class="fas fa-edit"></i></button>
                </a>
                <a href="adminvacancy_delete.php?id=<?php echo $vac['id']; ?>" onclick="return confirm('Are you sure you want to delete this vacancy?');">
                  <button class="delete"><i class="fas fa-trash"></i></button>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Simple client-side search
    const searchInput = document.getElementById("search");
    searchInput.addEventListener("input", function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("#vacancyList tr");
      rows.forEach(row => {
        const jobTitle = row.cells[1].textContent.toLowerCase(); 
        row.style.display = jobTitle.includes(filter) ? "" : "none";
      });
    });
  </script>
</body>
</html>
