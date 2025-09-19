<?php
require_once "includes/auth.php";
require_admin();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $db->deleteStudent($id);
    
    if ($result) {
        header("Location: dashboard_admin.php?deleted=success");
    } else {
        header("Location: dashboard_admin.php?deleted=error");
    }
    exit;
} else {
    header("Location: dashboard_admin.php");
    exit;
}
