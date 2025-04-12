<?php
// adminmanageuser.php

require_once __DIR__ . '/../../../lib/models/user_model.php';

$message = "";

// Process POST requests for Add or Edit actions.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action   = $_POST['action'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $role     = trim($_POST['role'] ?? '');
    $password = trim($_POST['password'] ?? '');  // New password field

    if ($action === 'add') {
        // In "add" mode, require a password.
        if (empty($password)) {
            $message = "Password is required for adding a new user.";
        } else {
            if (createUser($username, $email, $role, $password)) {
                $message = "User added successfully!";
            } else {
                $message = "Error adding user.";
            }
        }
    } elseif ($action === 'edit') {
        $userId = $_POST['userId'] ?? '';
        if ($userId && updateUser($userId, $username, $email, $role)) {
            $message = "User updated successfully!";
            // If a new password is provided during edit, update it.
            if (!empty($password)) {
                if (updateUserPassword($userId, $password)) {
                    $message .= " Password updated successfully!";
                } else {
                    $message .= " However, error updating password.";
                }
            }
        } else {
            $message = "Error updating user.";
        }
    }
}

// Process GET request for Delete action.
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    if (deleteUser($deleteId)) {
        $message = "User deleted successfully!";
    } else {
        $message = "Error deleting user.";
    }
}

// Retrieve user data from the database.
$users = getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS CDN -->
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
    .container.main-content {
      flex: 1;
      padding: 20px;
      margin: 20px auto;
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
      <li><a href="adminhome.php">Cooperation</a></li>
      <li><a href="adminvacancy.php">Vacancy</a></li>
      <li><a href="adminemployee.php">Employee</a></li>
      <li><a href="adminapplicants.php">Applicants</a></li>
      <li><a href="adminmanageuser.php" class="active">Manage Users</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="container mt-5 main-content">
    <h2>List of Users</h2>
    <?php if (!empty($message)): ?>
      <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" onclick="openAddUserModal()">Add User</button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)): ?>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?php echo htmlspecialchars($user['id']); ?></td>
              <td><?php echo htmlspecialchars($user['username']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td><?php echo htmlspecialchars($user['role']); ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="openEditUserModal(
                  '<?php echo $user['id']; ?>',
                  '<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>',
                  '<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>',
                  '<?php echo htmlspecialchars($user['role'], ENT_QUOTES); ?>'
                )">Edit</button>
                <a href="adminmanageuser.php?delete_id=<?php echo $user['id']; ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Are you sure you want to delete this user?');">
                   Delete
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal for Add/Edit User -->
  <div class="modal" id="userModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Form submission posts back to this same file -->
        <form method="POST" action="adminmanageuser.php">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add User</h5>
            <button type="button" class="btn-close" onclick="closeModal()"></button>
          </div>
          <div class="modal-body">
            <!-- Hidden fields -->
            <input type="hidden" name="userId" id="userId">
            <input type="hidden" name="action" id="action" value="add">
            
            <div class="mb-3">
              <label for="username" class="form-label">Username:</label>
              <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role:</label>
              <input type="text" name="role" id="role" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password:</label>
              <!-- For "add" mode this field is required; for editing, leave blank to keep current password -->
              <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password (leave blank to keep unchanged)">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openAddUserModal() {
      document.getElementById('modalTitle').innerText = 'Add User';
      document.getElementById('action').value = 'add';
      document.getElementById('userId').value = '';
      document.getElementById('username').value = '';
      document.getElementById('email').value = '';
      document.getElementById('role').value = '';
      document.getElementById('password').value = ''; // Ensure password field is empty
      document.getElementById('userModal').style.display = 'flex';
    }

    function openEditUserModal(id, username, email, role) {
      document.getElementById('modalTitle').innerText = 'Edit User';
      document.getElementById('action').value = 'edit';
      document.getElementById('userId').value = id;
      document.getElementById('username').value = username;
      document.getElementById('email').value = email;
      document.getElementById('role').value = role;
      // Leave password field blank – if user wants to update, they can enter a new one.
      document.getElementById('password').value = '';
      document.getElementById('userModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('userModal').style.display = 'none';
    }
  </script>
</body>
</html>
