<?php
session_start();
session_destroy();
header("Location: /cecert/index.php");
exit;
