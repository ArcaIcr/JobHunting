<!-- components/a-sidebar.php -->
<?php $currentFile = basename($_SERVER['PHP_SELF']); ?>
<div class="sidebar-nav">
  <h2>ADMIN</h2>
  <ul>
    <li>
      <a href="index.php" class="<?php echo ($currentFile === 'index.php') ? 'active' : ''; ?>">
        Cooperation
      </a>
    </li>
    <li>
      <a href="adminvacancy.php" class="<?php echo ($currentFile === 'adminvacancy.php') ? 'active' : ''; ?>">
        Vacancy
      </a>
    </li>
    <li>
      <a href="adminemployee.php" class="<?php echo ($currentFile === 'adminemployee.php') ? 'active' : ''; ?>">
        Employee
      </a>
    </li>
    <li>
      <a href="adminapplicants.php" class="<?php echo ($currentFile === 'adminapplicants.php') ? 'active' : ''; ?>">
        Applicants
      </a>
    </li>
    <li>
      <a href="adminmanageuser.php" class="<?php echo ($currentFile === 'adminmanageuser.php') ? 'active' : ''; ?>">
        Manage Users
      </a>
    </li>
  </ul>
  
  <!-- Keep only the Logout link at the bottom -->
  <div class="sidebar-logout">
    <a href="../../../components/a-logout.php" class="logout-link">
      <i class="fa fa-sign-out-alt"></i> Logout
    </a>
  </div>
</div>
