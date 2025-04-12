<?php
// adminmanageuser.php

// 1. Include our user model
require_once __DIR__ . '/../../../lib/models/user_model.php';

// 2. Fetch all users from the database
$users = getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
    }
    .sidebar {
      width: 200px;
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
    .main-content {
      flex: 1;
      padding: 20px;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      justify-content: center;
      align-items: center;
    }
    .modal-dialog {
      background: white;
      padding: 20px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
      <h2>ADMIN</h2>
      <ul>
          <li><a href="adminhome.php" class="active">Cooperation</a></li>
          <li><a href="adminvacancy.php">Vacancy</a></li>
          <li><a href="adminemployee.php">Employee</a></li>
          <li><a href="adminapplicants.php">Applicants <span class="badge"></span></a></li>
          <li><a href="adminmanageuser.php">Manage Users</a></li>
      </ul>
  </div>

  <!-- Main Content -->
  <div class="container mt-5 main-content">
      <h2>List of Users</h2>
      <!-- Button that triggers the modal for adding a new user (client-side only for now) -->
      <button class="btn btn-primary mb-3" onclick="openAddUserModal()">Add User</button>

      <!-- Table of Users Retrieved from the Database -->
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Action (Client-Side)</th>
              </tr>
          </thead>
          <tbody id="userTable">
              <?php if (!empty($users)): ?>
                  <?php foreach ($users as $user): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($user['id']); ?></td>
                          <td><?php echo htmlspecialchars($user['username']); ?></td>
                          <td><?php echo htmlspecialchars($user['email']); ?></td>
                          <td><?php echo htmlspecialchars($user['role']); ?></td>
                          <td>
                              <!-- The Edit/Delete buttons are still purely client-side. -->
                              <button class="btn btn-warning btn-sm" onclick="editUser(this)">Edit</button>
                              <button class="btn btn-danger btn-sm" onclick="deleteUser(this)">Delete</button>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="5">No users found.</td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>
  </div>

  <!-- Add/Edit User Modal -->
  <div class="modal" id="userModal" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="modalTitle">Add User</h5>
                  <button type="button" class="btn-close" onclick="closeModal()"></button>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="editIndex">
                  <label>ID:</label>
                  <input type="text" id="accountID" class="form-control" required>
                  <label>Account Name:</label>
                  <input type="text" id="accountName" class="form-control" required>
                  <label>Username:</label>
                  <input type="text" id="username" class="form-control" required>
                  <label>Role:</label>
                  <input type="text" id="role" class="form-control" required>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                  <button class="btn btn-success" onclick="saveUser()">Save</button>
              </div>
          </div>
      </div>
  </div>

  <script>
      function openAddUserModal() {
          document.getElementById("modalTitle").innerText = "Add User";
          document.getElementById("editIndex").value = "";
          document.getElementById("accountID").value = "";
          document.getElementById("accountName").value = "";
          document.getElementById("username").value = "";
          document.getElementById("role").value = "";
          document.getElementById("userModal").style.display = "flex";
      }

      function closeModal() {
          document.getElementById("userModal").style.display = "none";
      }

      // Client-side only for now
      function saveUser() {
          let accountID = document.getElementById("accountID").value;
          let accountName = document.getElementById("accountName").value;
          let username = document.getElementById("username").value;
          let role = document.getElementById("role").value;
          let editIndex = document.getElementById("editIndex").value;
          let table = document.getElementById("userTable");

          if (editIndex === "") {
              let row = table.insertRow();
              row.innerHTML = `<td>${accountID}</td>
                               <td>${username}</td>
                               <td>[Email Placeholder]</td>
                               <td>${role}</td>
                               <td><button class='btn btn-warning btn-sm' onclick='editUser(this)'>Edit</button>
                                    <button class='btn btn-danger btn-sm' onclick='deleteUser(this)'>Delete</button></td>`;
          } else {
              let row = table.rows[editIndex];
              row.cells[0].innerText = accountID;
              row.cells[1].innerText = username;
              // row.cells[2].innerText = ???; // Email logic, if needed
              row.cells[3].innerText = role;
          }
          closeModal();
      }

      function editUser(btn) {
          let row = btn.parentElement.parentElement;
          document.getElementById("modalTitle").innerText = "Edit User";
          document.getElementById("editIndex").value = row.rowIndex - 1;
          // Fill the modal fields based on table cells
          document.getElementById("accountID").value = row.cells[0].innerText;
          document.getElementById("username").value = row.cells[1].innerText;
          // row.cells[2] is the email
          document.getElementById("role").value = row.cells[3].innerText;
          document.getElementById("userModal").style.display = "flex";
      }

      function deleteUser(btn) {
          let row = btn.parentElement.parentElement;
          row.remove();
      }
  </script>
</body>
</html>
