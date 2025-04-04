<?php
// components/header.php
require_once __DIR__ . '/../lib/models/user_model.php';

$userId = $_SESSION['loggedInUser']['id'] ?? null;
$topBarUser = $userId ? getUserById($userId) : null;
?>
<header class="dashboard-top-bar">
  <div class="left-group">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
      <i class="fas fa-bars"></i>
    </button>
    <h2>TrabahoNasipit</h2>
  </div>
  <div class="search-bar">
    <input type="text" placeholder="Search...">
  </div>
  <div class="user-profile">
    <img src="/assets/images/avatars/<?php echo htmlspecialchars($topBarUser['avatar'] ?? 'default.png'); ?>" alt="User">
    <span><?php echo htmlspecialchars($topBarUser['username'] ?? ''); ?></span>
  </div>
</header>
