<?php
// pages/dashboard/admin/adminmanageuser.php

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
  <!-- Remove Bootstrap if you want to rely solely on custom CSS -->
  <link rel="stylesheet" href="../../../assets/css/admin.css">
  <!-- Page-specific styles -->
  <style>
    /* Main content offset by the fixed sidebar (250px) */
    .container.main-content {
      margin-left: 250px;
      padding: 20px;
      max-width: 1200px;
      margin-top: 20px;
      background: #f8f9fa;
    }
    /* Content Wrapper styles */
    .content-wrapper {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    /* Table styling */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
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
      color: #fff;
    }
    /* Button styles */
    .add-btn {
      background-color: #3498db;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 4px;
      cursor: pointer;
      margin-bottom: 15px;
      display: inline-block;
    }
    .add-btn:hover {
      background-color: #2980b9;
    }
    .btn-warning, .btn-danger {
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      color: #fff;
      text-decoration: none;
      font-size: 0.9rem;
      margin-right: 5px;
    }
    .btn-warning { background-color: #f1c40f; }
    .btn-warning:hover { background-color: #d4ac0d; }
    .btn-danger { background-color: #e74c3c; }
    .btn-danger:hover { background-color: #c0392b; }
    /* Modal styling for Add/Edit and Delete confirmation */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.4);
      justify-content: center;
      align-items: center;
    }
    .modal-dialog {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
      position: relative;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
    }
    .modal-title {
      font-size: 1.3rem;
      margin: 0;
    }
    .close-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      line-height: 1;
      cursor: pointer;
      color: #333;
    }
    .close-btn:hover {
      color: #000;
    }
    .modal-body {
      margin-top: 10px;
    }
    .modal-footer {
      margin-top: 15px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    /* Form control styling */
    .form-control {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .form-control:focus {
      border-color: #3498db;
      outline: none;
    }
  </style>
</head>
<body>
  <!-- Fixed Sidebar -->
  <aside class="sidebar">
    <?php include __DIR__ . '/../../../components/a-sidebar.php'; ?>
  </aside>

  <!-- Main Content -->
  <div class="container main-content">
    <div class="content-wrapper">
      <h2>List of Users</h2>
      <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <button class="add-btn" onclick="openAddUserModal()">Add User</button>
      <table>
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
                  <button class="btn-warning" onclick="openEditUserModal(
                    '<?php echo $user['id']; ?>',
                    '<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($user['role'], ENT_QUOTES); ?>'
                  )">Edit</button>
                  <button class="btn-danger" onclick="openDeleteUserModal('<?php echo $user['id']; ?>')">
                    Delete
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">No users found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal for Add/Edit User -->
  <div class="modal" id="userModal">
    <div class="modal-dialog">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add User</h5>
        <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
      </div>
      <form method="POST" action="adminmanageuser.php">
        <div class="modal-body">
          <input type="hidden" name="userId" id="userId">
          <input type="hidden" name="action" id="action" value="add">
          <label for="username">Username:</label>
          <input type="text" name="username" id="username" class="form-control" required>
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" class="form-control" required>
          <label for="role">Role:</label>
          <input type="text" name="role" id="role" class="form-control" required>
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password (leave blank to keep unchanged)">
        </div>
        <div class="modal-footer">
          <button type="button" class="add-btn" style="background:#7f8c8d;" onclick="closeModal()">Cancel</button>
          <button type="submit" class="add-btn">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal for Delete Confirmation -->
  <div class="modal" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="close-btn" onclick="closeDeleteModal()">&times;</button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="add-btn" style="background:#7f8c8d;" onclick="closeDeleteModal()">Cancel</button>
        <button type="button" class="add-btn" onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>

  <script>
    let deleteUserId = null;
    
    function openAddUserModal() {
      document.getElementById('modalTitle').innerText = 'Add User';
      document.getElementById('action').value = 'add';
      document.getElementById('userId').value = '';
      document.getElementById('username').value = '';
      document.getElementById('email').value = '';
      document.getElementById('role').value = '';
      document.getElementById('password').value = '';
      document.getElementById('userModal').style.display = 'flex';
    }

    function openEditUserModal(id, username, email, role) {
      document.getElementById('modalTitle').innerText = 'Edit User';
      document.getElementById('action').value = 'edit';
      document.getElementById('userId').value = id;
      document.getElementById('username').value = username;
      document.getElementById('email').value = email;
      document.getElementById('role').value = role;
      document.getElementById('password').value = '';  // leave blank on edit
      document.getElementById('userModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('userModal').style.display = 'none';
    }

    // Delete modal functions
    function openDeleteUserModal(id) {
      deleteUserId = id;
      document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').style.display = 'none';
      deleteUserId = null;
    }

    function confirmDelete() {
      if (deleteUserId !== null) {
        window.location.href = "adminmanageuser.php?delete_id=" + deleteUserId;
      }
    }
  </script>
</body>
</html>
