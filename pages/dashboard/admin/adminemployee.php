<?php
// adminemployee.php

// Include the shared database connection from db.php
require_once __DIR__ . '/../../../lib/db.php';

// Get the PDO connection
$pdo = getPDO();

// Initialize a variable to hold messages or errors
$message = "";

// ----- 1. Handle Form Submissions (Create or Update) -----
if (isset($_POST['save_employee'])) {
    // Gather form input safely (you can add further sanitization or validation if needed)
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
    <title>Employee Management - PHP</title>
    <style>
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
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        button, .btn {
            background-color: #34495e;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }
        input[type=text], input[type=number] {
            width: 100%;
            margin: 5px 0;
            padding: 8px;
            box-sizing: border-box;
        }
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
            background-color: #34495e;
            color: white;
        }
        .message {
            margin-bottom: 10px;
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        /* Simple modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .close {
            float: right;
            cursor: pointer;
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
            <li><a href="adminmanageuser.php">Manage Users</a></li>
        </ul>
    </div>

    <!-- Main Content Container -->
    <div class="container">
        <h1>Employees</h1>
        
        <!-- Display status message -->
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="adminemployee.php">
            <input 
                type="text" 
                name="search" 
                placeholder="Search by emp no, name, address, etc." 
                value="<?php echo htmlspecialchars($searchValue); ?>"
            />
            <button type="submit" class="btn">Search</button>
        </form>

        <!-- Add Employee Button -->
        <button onclick="showAddEmployeeForm()">Add New Employee</button>
        
        <!-- Employee Table -->
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
                    <td><?php echo htmlspecialchars($emp['emp_no']); ?></td>
                    <td><?php echo htmlspecialchars($emp['name']); ?></td>
                    <td><?php echo htmlspecialchars($emp['address']); ?></td>
                    <td><?php echo htmlspecialchars($emp['sex']); ?></td>
                    <td><?php echo htmlspecialchars($emp['age']); ?></td>
                    <td><?php echo htmlspecialchars($emp['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($emp['position']); ?></td>
                    <td>
                        <button 
                            onclick="showEditEmployeeForm(
                                '<?php echo $emp['id']; ?>',
                                '<?php echo $emp['emp_no']; ?>',
                                '<?php echo addslashes($emp['name']); ?>',
                                '<?php echo addslashes($emp['address']); ?>',
                                '<?php echo $emp['sex']; ?>',
                                '<?php echo $emp['age']; ?>',
                                '<?php echo addslashes($emp['contact_no']); ?>',
                                '<?php echo addslashes($emp['position']); ?>'
                            )"
                        >
                            Edit
                        </button>
                        <a 
                            href="adminemployee.php?delete_id=<?php echo $emp['id']; ?>" 
                            onclick="return confirm('Are you sure you want to delete this employee?');"
                        >
                            <button>Delete</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Employee Form Modal -->
    <div id="employeeForm" class="modal">
        <div class="modal-content">
            <span onclick="closeForm()" class="close">&times;</span>
            <h2 id="formTitle">Add Employee</h2>
            
            <!-- Form for Create / Update Employee -->
            <form method="POST" action="adminemployee.php">
                <!-- Hidden input used for editing -->
                <input type="hidden" id="empId" name="empId">

                <label>Employee No</label>
                <input type="text" id="empNo" name="empNo" placeholder="Employee No" required>

                <label>Name</label>
                <input type="text" id="empName" name="empName" placeholder="Name" required>

                <label>Address</label>
                <input type="text" id="empAddress" name="empAddress" placeholder="Address">

                <label>Sex</label>
                <input type="text" id="empSex" name="empSex" placeholder="Sex">

                <label>Age</label>
                <input type="number" id="empAge" name="empAge" placeholder="Age">

                <label>Contact No</label>
                <input type="text" id="empContact" name="empContact" placeholder="Contact No">

                <label>Position</label>
                <input type="text" id="empPosition" name="empPosition" placeholder="Position">

                <!-- Submit Button -->
                <button type="submit" name="save_employee">Save</button>
            </form>
        </div>
    </div>

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

            // Populate the form with existing data
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
