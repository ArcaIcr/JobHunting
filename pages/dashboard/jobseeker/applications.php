<?php
// pages/dashboard/jobseeker/applications.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include the application model to fetch applications
require_once '../../../lib/models/application_model.php';

// Get the current jobseeker's ID from the session
$jobseekerId = $_SESSION['loggedInUser']['id'];

// Fetch applications dynamically (make sure the query also selects interview_date, interview_time, interview_location)
$applications = getApplicationsForJobseeker($jobseekerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Job Applications</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .applications-header {
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
      color: #fff;
      padding: 2rem;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 2rem;
    }
    .applications-header h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }
    .applications-header p {
      font-size: 1.2rem;
      margin: 0;
    }
    .applications-container {
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .applications-table {
      width: 100%;
      border-collapse: collapse;
    }
    .applications-table th,
    .applications-table td {
      padding: 1rem;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    .applications-table th {
      background-color: #f4f4f4;
    }
    /* Modal styling */
    .modal {
      display: none; 
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 90%;
      max-width: 500px;
      border-radius: 8px;
      position: relative;
    }
    .close-modal {
      position: absolute;
      right: 10px;
      top: 10px;
      font-size: 1.5rem;
      font-weight: bold;
      cursor: pointer;
      color: #aaa;
    }
    .close-modal:hover {
      color: #000;
    }
    .interview-details {
      margin-top: 1rem;
      line-height: 1.6;
    }
    .btn-view-details {
      background: #007BFF;
      color: #fff;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: background 0.3s ease;
    }
    .btn-view-details:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <!-- Shared Top Bar/Header -->
  <?php include '../../../components/d-header.php'; ?>

  <!-- Main Wrapper for Sidebar and Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="applications-header">
        <h1>My Job Applications</h1>
        <p>Track the status of the jobs you applied for</p>
      </div>
      <div class="applications-container">
        <?php if (!empty($applications)): ?>
          <table class="applications-table">
            <thead>
              <tr>
                <th>Job Title</th>
                <th>Company</th>
                <th>Date Applied</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($applications as $app): ?>
                <tr>
                  <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                  <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                  <td><?php echo htmlspecialchars($app['date_applied']); ?></td>
                  <td><?php echo htmlspecialchars($app['status']); ?></td>
                  <td>
                    <?php if ($app['status'] === 'Interview Scheduled'): ?>
                      <!-- We'll store interview info in data attributes and show a 'View Details' button -->
                      <button class="btn-view-details"
                        data-interview-date="<?php echo htmlspecialchars($app['interview_date'] ?? ''); ?>"
                        data-interview-time="<?php echo htmlspecialchars($app['interview_time'] ?? ''); ?>"
                        data-interview-location="<?php echo htmlspecialchars($app['interview_location'] ?? ''); ?>"
                        onclick="openInterviewModal(this)">
                        View Details
                      </button>
                    <?php elseif ($app['status'] === 'Rejected'): ?>
                      <span style="color: red;">Rejected</span>
                    <?php elseif ($app['status'] === 'Approved'): ?>
                      <span style="color: green;">Approved</span>
                    <?php else: ?>
                      <!-- For Under Review or other statuses -->
                      <span>--</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No applications found. Start applying for jobs now!</p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <!-- Modal for Interview Details -->
  <div id="interviewModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeInterviewModal()">&times;</span>
      <h2>Interview Details</h2>
      <div class="interview-details">
        <p><strong>Date:</strong> <span id="modalInterviewDate"></span></p>
        <p><strong>Time:</strong> <span id="modalInterviewTime"></span></p>
        <p><strong>Location:</strong> <span id="modalInterviewLocation"></span></p>
      </div>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }

    function openInterviewModal(button) {
      const date = button.getAttribute('data-interview-date');
      const time = button.getAttribute('data-interview-time');
      const location = button.getAttribute('data-interview-location');

      document.getElementById('modalInterviewDate').textContent = date;
      document.getElementById('modalInterviewTime').textContent = time;
      document.getElementById('modalInterviewLocation').textContent = location;

      document.getElementById('interviewModal').style.display = 'block';
    }

    function closeInterviewModal() {
      document.getElementById('interviewModal').style.display = 'none';
    }

    // Close the modal if user clicks outside of it
    window.onclick = function(event) {
      const modal = document.getElementById('interviewModal');
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    }
  </script>
</body>
</html>
