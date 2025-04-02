<?php
// jobdetail.php
include 'config/config.php';

// Initialize job variable
$job = null;

// Check if a job ID was passed via GET
if (isset($_GET['id'])) {
    $jobId = intval($_GET['id']); // Ensure it's an integer

    // Prepare a statement to fetch the job details from the jobs table
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Job Detail</title>
        <style>
            /* Basic resets and styling */
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
                background: url('/assets/images/backg.jpg') no-repeat;
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
            .job-container {
                margin-top: 120px; /* Prevent header overlap */
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
            button {
                background: #ff7e5f;
                color: white;
                border: none;
                padding: 10px;
                cursor: pointer;
                margin-top: 10px;
            }
            button:hover {
                background: #feb47b;
            }
        </style>
    </head>
    <body>
        <header>
            <a href="#" class="logo">trabahosanasipit <span>.</span></a>
            <nav class="navbar">
                <a href="home.php">Home</a>
                <a href="findjob.php">Job Search</a>
                <a href="cooperation.php">Cooperation</a>
                <a href="contact.php">Contact</a>
                <a href="login.php">Login</a>
                <a href="signup.php">Register</a>
            </nav>
        </header>
        <section class="job-container">
            <h2>JOB DETAIL</h2>
            <?php if ($job): ?>
                <table>
                    <tr>
                        <th>JOB NAME</th>
                        <th>COOPERATION</th>
                        <th>LOCATION</th>
                        <th>DATE POSTED</th>
                    </tr>
                    <tr>
                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                        <td><?php echo htmlspecialchars($job['cooperation']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo htmlspecialchars($job['posted_at']); ?></td>
                    </tr>
                </table>
                <button id="apply-btn">APPLY NOW</button>
            <?php else: ?>
                <p>Job not found.</p>
            <?php endif; ?>
        </section>
        <script>
            document.getElementById("apply-btn")?.addEventListener("click", function() {
                alert("Application Submitted!");
            });
        </script>
    </body>
</html>
