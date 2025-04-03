<?php
// pages/dashboard/jobseeker/recommended_jobs.php
session_start();
// require_once '../../../lib/auth.php';
// requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recommended Jobs</title>
  <!-- Link to the new CSS file -->
  <link rel="stylesheet" href="/assets/css/recommended.css">
</head>
<body>
  <!-- Optional: include your existing header or top nav -->
  <!-- ?php include '../../../components/header.php'; ? -->


  <!-- Top Navigation Bar -->
  <header class="top-nav">
    <div class="logo">
      <h2>Luckjob</h2>
    </div>
    <nav class="top-links">
      <a href="#">Find Jobs</a>
      <a href="#">Company</a>
      <a href="#">History</a>
      <a href="#">FAQ</a>
      <a href="#">New York, NY</a>
    </nav>
    <div class="user-actions">
      <input type="range" min="0" max="100" value="50">
      <img src="/assets/images/user.jpg" alt="User" class="user-avatar">
    </div>
  </header>

  <!-- Main Container -->
  <div class="main-container">
    <!-- Left Sidebar for Filters -->
    <aside class="filter-sidebar">
      <div class="filter-card">
        <h3>Filters</h3>
        <div class="filter-group">
          <label>Position</label>
          <select>
            <option>All</option>
            <option>UI/UX Designer</option>
            <option>Front-end Dev</option>
            <option>Back-end Dev</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Experience</label>
          <select>
            <option>All</option>
            <option>Entry-level</option>
            <option>Mid-level</option>
            <option>Senior</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Type</label>
          <select>
            <option>Full-time</option>
            <option>Part-time</option>
            <option>Contract</option>
          </select>
        </div>
      </div>
      <button class="filter-btn">Apply Filters</button>
    </aside>

    <!-- Main Content: Recommended Jobs -->
    <section class="recommended-jobs">
      <div class="section-header">
        <h2>Recommended Jobs</h2>
        <span class="job-count">286</span>
      </div>
      <div class="jobs-grid">
        <!-- Job Card 1 -->
        <div class="job-card">
          <div class="job-card-header">
            <img src="/assets/images/company1.png" alt="Company Logo">
            <div>
              <h3>Senior UI/UX Designer</h3>
              <p>Adobe - San Jose, CA</p>
            </div>
          </div>
          <div class="job-card-body">
            <p>$120k - $130k / year</p>
            <span class="job-type">Full-time</span>
          </div>
          <div class="job-card-footer">
            <button class="details-btn">Details</button>
            <button class="apply-btn">Apply Now</button>
          </div>
        </div>
        <!-- Job Card 2 -->
        <div class="job-card">
          <div class="job-card-header">
            <img src="/assets/images/company2.png" alt="Company Logo">
            <div>
              <h3>UX Designer</h3>
              <p>Twitter - Remote</p>
            </div>
          </div>
          <div class="job-card-body">
            <p>$5k - $7k / month</p>
            <span class="job-type part-time">Part-time</span>
          </div>
          <div class="job-card-footer">
            <button class="details-btn">Details</button>
            <button class="apply-btn">Apply Now</button>
          </div>
        </div>
        <!-- Job Card 3 -->
        <div class="job-card">
          <div class="job-card-header">
            <img src="/assets/images/company3.png" alt="Company Logo">
            <div>
              <h3>Graphic Designer</h3>
              <p>Apple - Cupertino, CA</p>
            </div>
          </div>
          <div class="job-card-body">
            <p>$80k - $95k / year</p>
            <span class="job-type">Full-time</span>
          </div>
          <div class="job-card-footer">
            <button class="details-btn">Details</button>
            <button class="apply-btn">Apply Now</button>
          </div>
        </div>
        <!-- Add more job cards as needed -->
      </div>
    </section>
  </div>

  <!-- Optional: include your existing footer -->
  <!-- ?php include '../../../components/footer.php'; ? -->
</body>
</html>
