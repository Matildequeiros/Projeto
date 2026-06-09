<?php

session_start();

session_unset();

session_destroy();

header('Location: /PROJETO/Frontend/Public/login.php');

exit;