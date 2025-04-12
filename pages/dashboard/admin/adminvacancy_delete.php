<?php
// pages/dashboard/admin/adminvacancy_delete.php

require_once '../../../lib/auth.php';
requireAdminLogin();

include_once '../../../config/config.php';
require_once '../../../lib/models/Vacancy.php';

$id = $_GET['id'] ?? 0;
if ($id) {
    Vacancy::delete($id);
}
header("Location: adminvacancy.php");
exit;
?>
