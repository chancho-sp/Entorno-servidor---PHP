<?php
    $pass = 'traidor345';
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    echo $hash;
?>