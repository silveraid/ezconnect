<?php

function filter($pattern) {

    // Controlling the minimal number of character before
    // makeing any effort to find the entry
    $len = strlen($pattern);

    if ($len < 3)
        return;

    $db = json_decode(file_get_contents("../db"), true);

    print_r($db); 

}

filter("aaa");


?>
