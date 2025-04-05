<?php
// pages/dashboard/employer/applications.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include the model file that contains getApplicationsForEmployer()
// Make sure this function returns fields including resume (if applicable)
require_once '../../../lib/models/jobs_model.php';

$employerId = $_SESSION['loggedInUser']['id'] ?? null;
if (!$employerId) {
    die("Employer ID not found in session.");
}

// Fetch all applications for jobs posted by this employer
$applications = getApplicationsForEmployer($employerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Applications</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Additional styling for the applications page */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    th, td {
      padding: 1rem;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #f4f4f4;
    }
    .btn-action {
      display: inline-block;
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      border-radius: 4px;
      text-decoration: none;
      color: #fff;
      font-size: 0.9rem;
      transition: background 0.3s ease;
    }
    .btn-approve {
      background-color: #28a745;
    }
    .btn-approve:hover {
      background-color: #218838;
    }
    .btn-reject {
      background-color: #dc3545;
    }
    .btn-reject:hover {
      background-color: #c82333;
    }
    .btn-interview {
      background-color: #ffc107;
      color: #212529;
    }
    .btn-interview:hover {
      background-color: #e0a800;
    }
    /* Modal styles */
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
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close:hover,
    .close:focus {
      color: black;
    }
  </style>
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Applications</h1>
      <?php if (!empty($applications)) : ?>
        <table>
          <thead>
            <tr>
              <th>Applicant Name</th>
              <th>Job Title</th>
              <th>Date Applied</th>
              <th>Status</th>
              <th>Resume</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($applications as $app) : ?>
              <tr>
                <td><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                <td><?php echo htmlspecialchars($app['date_applied']); ?></td>
                <td><?php echo htmlspecialchars($app['status']); ?></td>
                <td>
                  <?php if (!empty($app['resume'])): ?>
                    <a href="/assets/resumes/<?php echo htmlspecialchars($app['resume']); ?>" target="_blank">View Resume</a>
                  <?php else: ?>
                    N/A
                  <?php endif; ?>
                </td>
                <td>
                  <a href="process_application.php?app_id=<?php echo $app['id']; ?>&action=approve" class="btn-action btn-approve">Approve</a>
                  <a href="process_application.php?app_id=<?php echo $app['id']; ?>&action=reject" class="btn-action btn-reject">Reject</a>
                  <!-- Schedule Interview button now triggers a modal -->
                  <button onclick="openInterviewModal(<?php echo $app['id']; ?>)" class="btn-action btn-interview">Schedule Interview</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else : ?>
        <p>No applications found.</p>
      <?php endif; ?>
    </main>
  </div>

  <!-- Interview Scheduling Modal -->
  <div id="interviewModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Schedule Interview</h2>
      <form id="interviewForm" method="POST" action="process_application.php">
        <input type="hidden" name="app_id" id="modal_app_id" value="">
        <input type="hidden" name="action" value="interview">
        <div>
          <label for="interview_date">Interview Date:</label>
          <input type="date" id="interview_date" name="interview_date" required>
        </div>
        <div>
          <label for="interview_time">Interview Time:</label>
          <input type="time" id="interview_time" name="interview_time" required>
        </div>
        <div>
          <label for="interview_location">Interview Location:</label>
          <input type="text" id="interview_location" name="interview_location" required>
        </div>
        <button type="submit">Submit Interview Details</button>
      </form>
    </div>
  </div>

  <script>
    // Modal handling for scheduling interview
    var modal = document.getElementById("interviewModal");
    var span = document.getElementsByClassName("close")[0];

    // Open modal and set the application id
    function openInterviewModal(appId) {
      document.getElementById("modal_app_id").value = appId;
      modal.style.display = "block";
    }

    // Close the modal when the user clicks the (x)
    span.onclick = function() {
      modal.style.display = "none";
    }

    // Close the modal if user clicks outside the modal content
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
