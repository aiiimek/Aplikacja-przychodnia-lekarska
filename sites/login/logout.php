<?php
session_unset();   
session_destroy(); 
header('Location: /Aplikacja-przychodnia-lekarska/sites/login/');
exit;
