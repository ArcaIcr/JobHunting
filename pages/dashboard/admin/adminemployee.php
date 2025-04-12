<?php
// pages/dashboard/admin/adminemployee.php

// Include database connection and required functions
require_once __DIR__ . '/../../../lib/db.php';
$pdo = getPDO();

// Initialize message variable (for status or errors)
$message = "";

// ----- 1. Handle Form Submissions (Create or Update) -----
if (isset($_POST['save_employee'])) {
    // Retrieve form input safely
    $empId      = $_POST['empId'] ?? '';
    $empNo      = $_POST['empNo'] ?? '';
    $empName    = $_POST['empName'] ?? '';
    $empAddress = $_POST['empAddress'] ?? '';
    $empSex     = $_POST['empSex'] ?? '';
    $empAge     = $_POST['empAge'] ?? '';
    $empContact = $_POST['empContact'] ?? '';
    $empPosition= $_POST['empPosition'] ?? '';

    try {
        if ($empId == "") {
            // Insert new employee
            $sql = "INSERT INTO employees (emp_no, name, address, sex, age, contact_no, position)
                    VALUES (:empNo, :name, :address, :sex, :age, :contact_no, :position)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':empNo'      => $empNo,
                ':name'       => $empName,
                ':address'    => $empAddress,
                ':sex'        => $empSex,
                ':age'        => $empAge,
                ':contact_no' => $empContact,
                ':position'   => $empPosition
            ]);
            $message = "Employee added successfully!";
        } else {
            // Update existing employee
            $sql = "UPDATE employees 
                    SET emp_no = :empNo,
                        name = :name,
                        address = :address,
                        sex = :sex,
                        age = :age,
                        contact_no = :contact_no,
                        position = :position
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':empNo'      => $empNo,
                ':name'       => $empName,
                ':address'    => $empAddress,
                ':sex'        => $empSex,
                ':age'        => $empAge,
                ':contact_no' => $empContact,
                ':position'   => $empPosition,
                ':id'         => $empId
            ]);
            $message = "Employee updated successfully!";
        }
    } catch (PDOException $e) {
        $message = "Error saving employee: " . $e->getMessage();
    }
}

// ----- 2. Handle Delete Requests -----
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    try {
        $sql = "DELETE FROM employees WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $deleteId]);
        $message = "Employee deleted successfully!";
    } catch (PDOException $e) {
        $message = "Error deleting employee: " . $e->getMessage();
    }
}

// ----- 3. Fetch Employees (with optional searching) -----
$employees = [];
$searchValue = $_GET['search'] ?? '';
try {
    if (!empty($searchValue)) {
        $sql = "SELECT * FROM employees 
                WHERE emp_no      LIKE :search
                   OR name        LIKE :search
                   OR address     LIKE :search
                   OR sex         LIKE :search
                   OR contact_no  LIKE :search
                   OR position    LIKE :search";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':search' => "%$searchValue%"]);
    } else {
        $sql = "SELECT * FROM employees";
        $stmt = $pdo->query($sql);
    }
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error fetching employees: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <!-- Link to centralized admin.css for common styling -->
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <!-- Page-specific styles for the modal -->
    <style>
      /* Modal Container: Centered using Flex */
      .modal {
          display: none;
          position: fixed;
          z-index: 1000;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.4);
          justify-content: center;
          align-items: center;
      }
      /* Modal Content Box */
      .modal-content {
          background: #fff;
          padding: 20px;
          border-radius: 8px;
          width: 90%;
          max-width: 500px;
          position: relative;
      }
      .modal-content .close {
          position: absolute;
          top: 10px;
          right: 15px;
          font-size: 24px;
          cursor: pointer;
      }
      .modal-content h2 {
          margin-top: 0;
          margin-bottom: 15px;
          font-size: 1.5rem;
          text-align: center;
      }
      /* Two-column form styling within the modal */
      .modal-content form {
          display: flex;
          flex-wrap: wrap;
          gap: 20px;
      }
      /* Form Group: Each label+input pair */
      .form-group {
          flex: 1 1 calc(50% - 20px);
          min-width: 220px;
      }
      .form-group label {
          display: block;
          margin-bottom: 5px;
          font-weight: 500;
      }
      .form-group input[type="text"],
      .form-group input[type="number"],
      .form-group textarea {
          width: 100%;
          padding: 8px;
          border: 1px solid #ccc;
          border-radius: 4px;
          margin-bottom: 10px;
          transition: border-color 0.3s ease;
      }
      .form-group input:focus,
      .form-group textarea:focus {
          border-color: #3498db;
          outline: none;
      }
      /* Save Button: Full width, centered */
      .modal-content form button[type="submit"] {
          flex: 1 1 100%;
          max-width: 120px;
          margin: 0 auto;
          display: block;
          background-color: #3498db;
          color: #fff;
          border: none;
          padding: 10px 18px;
          border-radius: 4px;
          font-size: 16px;
          cursor: pointer;
          transition: background 0.3s ease;
      }
      .modal-content form button[type="submit"]:hover {
          background-color: #2980b9;
      }
    </style>
</head>
<body>
    <!-- Fixed Sidebar (included via admin.css and a-sidebar.php) -->
    <aside class="sidebar">
        <?php include __DIR__ . '/../../../components/a-sidebar.php'; ?>
    </aside>

    <!-- Main Content Container -->
    <div class="main-content">
        <header class="header">
            <h2>Employees</h2>
            <div class="search-box">
                <form method="GET" action="adminemployee.php">
                    <input type="text" name="search" placeholder="Search by emp no, name, address, etc." value="<?php echo htmlspecialchars($searchValue); ?>">
                    <button type="submit" class="btn">Search</button>
                </form>
            </div>
        </header>

        <?php if (!empty($message)): ?>
            <div class="flash-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="content">
            <button class="add-btn" onclick="showAddEmployeeForm()">Add New Employee</button>
            
            <!-- Employee Table -->
            <div class="table-responsive">
              <table>
                  <thead>
                      <tr>
                          <th>Employee No</th>
                          <th>Name</th>
                          <th>Address</th>
                          <th>Sex</th>
                          <th>Age</th>
                          <th>Contact No</th>
                          <th>Position</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($employees as $emp): ?>
                      <tr>
                          <td data-label="Employee No"><?php echo htmlspecialchars($emp['emp_no']); ?></td>
                          <td data-label="Name"><?php echo htmlspecialchars($emp['name']); ?></td>
                          <td data-label="Address"><?php echo htmlspecialchars($emp['address']); ?></td>
                          <td data-label="Sex"><?php echo htmlspecialchars($emp['sex']); ?></td>
                          <td data-label="Age"><?php echo htmlspecialchars($emp['age']); ?></td>
                          <td data-label="Contact No"><?php echo htmlspecialchars($emp['contact_no']); ?></td>
                          <td data-label="Position"><?php echo htmlspecialchars($emp['position']); ?></td>
                          <td data-label="Action">
                              <button class="action-btn edit" onclick="showEditEmployeeForm(
                                  '<?php echo $emp['id']; ?>',
                                  '<?php echo $emp['emp_no']; ?>',
                                  '<?php echo addslashes($emp['name']); ?>',
                                  '<?php echo addslashes($emp['address']); ?>',
                                  '<?php echo $emp['sex']; ?>',
                                  '<?php echo $emp['age']; ?>',
                                  '<?php echo addslashes($emp['contact_no']); ?>',
                                  '<?php echo addslashes($emp['position']); ?>'
                              )"><i class="fas fa-edit"></i></button>
                              <a href="adminemployee.php?delete_id=<?php echo $emp['id']; ?>" onclick="return confirm('Are you sure you want to delete this employee?');">
                                  <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                              </a>
                          </td>
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
            </div>
        </div>
    </div>

    <!-- Employee Form Modal (used for both Add and Edit) -->
    <div id="employeeForm" class="modal">
      <div class="modal-content">
          <span class="close" onclick="closeForm()">&times;</span>
          <h2 id="formTitle">Add Employee</h2>
          <form method="POST" action="adminemployee.php">
              <input type="hidden" id="empId" name="empId">
              
              <div class="form-group">
                <label for="empNo">Employee No</label>
                <input type="text" id="empNo" name="empNo" placeholder="Employee No" required>
              </div>
              
              <div class="form-group">
                <label for="empName">Name</label>
                <input type="text" id="empName" name="empName" placeholder="Name" required>
              </div>
              
              <div class="form-group">
                <label for="empAddress">Address</label>
                <input type="text" id="empAddress" name="empAddress" placeholder="Address">
              </div>
              
              <div class="form-group">
                <label for="empSex">Sex</label>
                <input type="text" id="empSex" name="empSex" placeholder="Sex">
              </div>
              
              <div class="form-group">
                <label for="empAge">Age</label>
                <input type="number" id="empAge" name="empAge" placeholder="Age">
              </div>
              
              <div class="form-group">
                <label for="empContact">Contact No</label>
                <input type="text" id="empContact" name="empContact" placeholder="Contact No">
              </div>
              
              <div class="form-group">
                <label for="empPosition">Position</label>
                <input type="text" id="empPosition" name="empPosition" placeholder="Position">
              </div>
              
              <!-- Save Button -->
              <button type="submit" name="save_employee" class="add-btn">Save</button>
          </form>
      </div>
    </div>

    <!-- JavaScript for Modal Handling -->
    <script>
      function showAddEmployeeForm() {
          document.getElementById('employeeForm').style.display = 'flex';
          document.getElementById('formTitle').innerText = 'Add Employee';
          // Reset form fields
          document.getElementById('empId').value = '';
          document.getElementById('empNo').value = '';
          document.getElementById('empName').value = '';
          document.getElementById('empAddress').value = '';
          document.getElementById('empSex').value = '';
          document.getElementById('empAge').value = '';
          document.getElementById('empContact').value = '';
          document.getElementById('empPosition').value = '';
      }
      
      function showEditEmployeeForm(id, empNo, name, address, sex, age, contact, position) {
          document.getElementById('employeeForm').style.display = 'flex';
          document.getElementById('formTitle').innerText = 'Edit Employee';
          document.getElementById('empId').value = id;
          document.getElementById('empNo').value = empNo;
          document.getElementById('empName').value = name;
          document.getElementById('empAddress').value = address;
          document.getElementById('empSex').value = sex;
          document.getElementById('empAge').value = age;
          document.getElementById('empContact').value = contact;
          document.getElementById('empPosition').value = position;
      }
      
      function closeForm() {
          document.getElementById('employeeForm').style.display = 'none';
      }
    </script>
</body>
</html>
