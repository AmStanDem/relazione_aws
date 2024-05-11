<?php
session_destroy();
session_unset();
$_SESSION[] = '';
echo '<script> location.replace("index.php"); </script>';