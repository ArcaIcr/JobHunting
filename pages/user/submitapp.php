<?php
// submitapp.php

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $firstname   = trim($_POST['firstname'] ?? '');
    $lastname    = trim($_POST['lastname'] ?? '');
    $middlename  = trim($_POST['middlename'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $sex         = trim($_POST['sex'] ?? '');
    $month       = trim($_POST['month'] ?? '');
    $day         = trim($_POST['day'] ?? '');
    $year        = trim($_POST['year'] ?? '');
    $contact     = trim($_POST['contact'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $resume      = $_FILES['resume'] ?? null;
    
    // Check if all required fields are filled out
    if ($firstname && $lastname && $address && $sex && $month && $day && $year && $contact && $email && $resume) {
        // Handle file upload
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $resumeFileName = basename($resume['name']);
        $targetFilePath = $uploadDir . $resumeFileName;
        
        if (move_uploaded_file($resume['tmp_name'], $targetFilePath)) {
            $uploadStatus = "File uploaded successfully.";
        } else {
            $uploadStatus = "File upload failed.";
        }
        
        // Here, you can add database insertion logic if desired.
        // For now, we just create a success message.
        $message = "Form submitted successfully! Name: $firstname $lastname, Email: $email, Contact: $contact. " . $uploadStatus;
    } else {
        $message = "Please fill out all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Job Application Form</title>
        <style>
            *:root {
                --red: #FF0000;
            }
            body {
                margin: 0;
                padding: 0;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background: url(backg.jpg) no-repeat;
                background-size: 100% 100%;
            }
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                outline: none;
                border: none;
                text-decoration: none;
                text-transform: capitalize;
                transition: .2s linear;
            }
            html {
                font-size: 40.5%;
                scroll-behavior: smooth;
                scroll-padding-top: 6rem;
                overflow-x: hidden;
            }
            section {
                padding: 2rem 9%;
            }
            .btn {
                display: inline-block;
                margin-top: 1rem;
                border-radius: 5rem;
                background: #333;
                color: #ffff;
                padding: .9rem 3.5rem;
                cursor: pointer;
                font-size: 1.7rem;
            }
            .btn:hover {
                background: var(--red);
            }
            header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background-color: #fff;
                padding: 1rem 9%;
                display: flex;
                align-items: center;
                justify-content: space-between;
                z-index: 50;
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
            }
            header .logo {
                font-size: 3rem;
                color: #333;
                font-weight: bolder;
            }
            header .logo span {
                color: var(--red);
            }
            header .navbar a {
                font-size: 2rem;
                padding: 0 1.5rem;
                color: #666;
            }
            header .navbar a:hover {
                color: var(--red);
            }
            .container {
                max-width: 1000px;
                margin: 50px auto;
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                display: flex;
                gap: 30px;
            }
            .form-section {
                flex: 1;
            }
            h2 {
                text-align: center;
                background: #00aaff;
                color: white;
                padding: 15px;
                border-radius: 10px 10px 0 0;
            }
            label {
                font-size: 18px;
                display: block;
                margin: 10px 0 5px;
            }
            input, select, textarea {
                width: 100%;
                padding: 10px;
                font-size: 16px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }
            .job-details {
                flex: 1;
                padding: 20px;
                background: #f9f9f9;
                border-radius: 10px;
            }
            .job-details h3 {
                color: #0077cc;
            }
            .resume-section {
                margin-top: 20px;
                padding: 20px;
                background: #f9f9f9;
                border-radius: 10px;
            }
            .buttons {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .btn-back {
                background: #009688;
                color: white;
            }
            .btn-submit {
                background: #0077cc;
                color: white;
            }
        </style>
    </head>
    <body>
        
     <?php include '../../components/header.php'; ?>
        <h2 class="text-center">Submit Application</h2>
        
        <!-- Display PHP message -->
        <?php if ($message): ?>
            <p style="text-align:center; color: green;"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <form method="post" action="submitapp.php" enctype="multipart/form-data">
            <div class="container">
                <div class="form-section">
                    <h3>PERSONAL INFO</h3>
                    <label>Firstname:</label>
                    <input type="text" name="firstname" id="firstname" placeholder="Firstname" required>
                    
                    <label>Lastname:</label>
                    <input type="text" name="lastname" id="lastname" placeholder="Lastname" required>
                    
                    <label>Middle Name:</label>
                    <input type="text" name="middlename" id="middlename" placeholder="Middle Name">
                    
                    <label>Address:</label>
                    <textarea name="address" id="address" placeholder="Address" required></textarea>
                    
                    <label>Sex:</label>
                    <select name="sex" id="sex" required>
                        <option value="">Select</option>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                        <option value="Other">Other</option>
                    </select>
                    
                    <label>Date of Birth:</label>
                    <select name="month" id="month" required>
                        <option value="">Month</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                    <select name="day" id="day" required>
                        <option value="">Day</option>
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="year" id="year" required>
                        <option value="">Year</option>
                        <?php for ($i = date("Y"); $i >= 1900; $i--): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    
                    <label>Contact No.:</label>
                    <input type="text" name="contact" id="contact" placeholder="Contact No." required>
                    
                    <label>Email Address:</label>
                    <input type="email" name="email" id="email" placeholder="Email Address" required>
                </div>
                <div class="job-details">
                    <h3>JOB DETAILS</h3>
                    <div id="jobInfo">
                        <?php
                        // Example job details (this could be dynamic in a real application)
                        echo "
                        <h3>Accounting</h3>
                        <p><strong>Required No. of Employees:</strong> 1</p>
                        <p><strong>Salary:</strong> 1,500.00</p>
                        <p><strong>Duration of Employment:</strong> May 20</p>
                        <p><strong>Qualification/Work Experience:</strong> Two years Experience</p>
                        <p><strong>Job Description:</strong> We are looking for a bachelor of science in Accountancy</p>
                        <p><strong>Employer:</strong> URC</p>
                        <p><strong>Location:</strong> Bry Camugao</p>
                        ";
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="container resume-section">
                <h3>Attach your Resume here.</h3>
                <label>Attachment File:</label>
                <input type="file" name="resume" id="resume" required>
            </div>
            
            <div class="container buttons">
                <button type="button" class="btn btn-back" onclick="history.back()">Back</button>
                <button type="submit" class="btn btn-submit">Submit</button>
            </div>
        </form>
    </body>
</html>
