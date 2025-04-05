<?php
// pages/dashboard/jobseeker/saved_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include the saved jobs model
require_once '../../../lib/models/saved_jobs_model.php';

// Include application model to check application status
require_once '../../../lib/models/application_model.php';

$jobseekerId = $_SESSION['loggedInUser']['id'];
$savedJobs = getSavedJobsForJobseeker($jobseekerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Saved Jobs</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
</head>
<body>
  <!-- Top Bar -->
  <?php include '../../../components/d-header.php'; ?>

  <!-- Main Wrapper: Sidebar + Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Saved Jobs</h1>
      <?php if (!empty($savedJobs)): ?>
        <ul class="saved-jobs-list">
          <?php foreach ($savedJobs as $job): ?>
            <?php 
              // Check if the jobseeker has already applied for this job
              $application = getApplicationForJob($jobseekerId, $job['job_id']);
              $appStatus = $application ? $application['status'] : '';
            ?>
            <li 
              data-job-id="<?php echo htmlspecialchars($job['job_id']); ?>" 
              data-job-title="<?php echo htmlspecialchars($job['job_title']); ?>"
              data-company="<?php echo htmlspecialchars($job['company_name']); ?>"
              data-location="<?php echo htmlspecialchars($job['location']); ?>"
              data-posted="<?php echo htmlspecialchars($job['posted_at']); ?>"
              data-description="<?php echo htmlspecialchars($job['description'] ?? 'No description available.'); ?>"
              data-app-status="<?php echo htmlspecialchars($appStatus); ?>"
              onclick="openJobModal(this)">
              <a href="javascript:void(0);">
                <?php echo htmlspecialchars($job['job_title']); ?> at <?php echo htmlspecialchars($job['company_name']); ?>
              </a>
              <span>Saved on: <?php echo htmlspecialchars($job['saved_at']); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>You have no saved jobs.</p>
      <?php endif; ?>
    </main>
  </div>

  <!-- Modal for Job Details -->
  <div id="jobModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeJobModal()">&times;</span>
      <h2 id="modalJobTitle"></h2>
      <p><strong>Company:</strong> <span id="modalCompany"></span></p>
      <p><strong>Location:</strong> <span id="modalLocation"></span></p>
      <p><strong>Date Posted:</strong> <span id="modalPosted"></span></p>
      <p><strong>Description:</strong></p>
      <p id="modalDescription"></p>
      <div id="modalActionContainer">
        <!-- The Apply button or status label will be injected here -->
        <a id="modalApplyLink" href="" class="btn-apply">Apply Now</a>
      </div>
    </div>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
    
    // Open modal and populate it with job details from data attributes
    function openJobModal(element) {
      const jobId = element.getAttribute('data-job-id');
      const jobTitle = element.getAttribute('data-job-title');
      const company = element.getAttribute('data-company');
      const location = element.getAttribute('data-location');
      const posted = element.getAttribute('data-posted');
      const description = element.getAttribute('data-description');
      const appStatus = element.getAttribute('data-app-status');
      
      document.getElementById('modalJobTitle').textContent = jobTitle;
      document.getElementById('modalCompany').textContent = company;
      document.getElementById('modalLocation').textContent = location;
      document.getElementById('modalPosted').textContent = posted;
      document.getElementById('modalDescription').textContent = description;
      
      // Determine what to show in the Action Container
      const actionContainer = document.getElementById('modalActionContainer');
      actionContainer.innerHTML = ""; // Clear previous content
      
      if (appStatus && appStatus.trim() !== "") {
          // If an application exists, display its status instead of an Apply button.
          let statusLabel = document.createElement("span");
          statusLabel.textContent = appStatus; // e.g., "Rejected", "Interview Scheduled", "Under Review"
          statusLabel.className = "btn-disabled";
          actionContainer.appendChild(statusLabel);
      } else {
          // If no application exists, show the Apply button.
          let applyLink = document.createElement("a");
          applyLink.href = "apply.php?job_id=" + jobId;
          applyLink.textContent = "Apply Now";
          applyLink.className = "btn-apply";
          actionContainer.appendChild(applyLink);
      }
      
      // Show the modal
      document.getElementById('jobModal').style.display = "block";
    }
    
    function closeJobModal() {
      document.getElementById('jobModal').style.display = "none";
    }
    
    // Close modal if user clicks outside the modal content
    window.onclick = function(event) {
      const modal = document.getElementById('jobModal');
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
