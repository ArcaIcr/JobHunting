<?php
// pages/dashboard/admin/adminemployee.php

require_once __DIR__ . '/../../../lib/db.php';
require_once __DIR__ . '/../../../lib/models/employee_model.php';

$message = "";

// ----- 1. Handle Form Submissions (Create or Update) -----
if (isset($_POST['save_employee'])) {
    $empId = $_POST['empId'] ?? '';
    $data = [
        'empNo'       => $_POST['empNo'] ?? '',
        'empName'     => $_POST['empName'] ?? '',
        'empAddress'  => $_POST['empAddress'] ?? '',
        'empSex'      => $_POST['empSex'] ?? '',
        'empAge'      => $_POST['empAge'] ?? '',
        'empContact'  => $_POST['empContact'] ?? '',
        'empPosition' => $_POST['empPosition'] ?? ''
    ];
    try {
        if ($empId === "") {
            EmployeeModel::addEmployee($data);
            $message = "Employee added successfully!";
        } else {
            EmployeeModel::updateEmployee($empId, $data);
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
        EmployeeModel::deleteEmployee($deleteId);
        $message = "Employee deleted successfully!";
    } catch (PDOException $e) {
        $message = "Error deleting employee: " . $e->getMessage();
    }
}

// ----- 3. Fetch Employees (with optional searching) -----
$searchValue = $_GET['search'] ?? '';
try {
    $employees = EmployeeModel::getEmployees($searchValue);
} catch (PDOException $e) {
    $message = "Error fetching employees: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link to centralized admin.css for common styling -->
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <!-- Page-specific styles for the modal -->
    
</head>
<body>
    <!-- Fixed Sidebar -->
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

    <!-- Employee Form Modal (Add/Edit) -->
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
