<?php
require_once('includes/paths.php');
session_start();
session_destroy();
redirect('index.php');
exit;
?>
