<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('logger.php');
$logger = new Logger("/tmp/ezconnect.log");

// Flag for debugging. If the value of this variable is true
// then devug log will go to /tmp/ezconnect.log
$debug = TRUE;


$res = array();


function search($pattern, &$res) {

    global $logger;

    // Controlling the minimal number of character before
    // makeing any effort to find the entry
    $len = strlen($pattern);

    if ($len < 3) {

        $logger->wr("Search string is not long enough! ($len)");
        return;
    }

    $db = json_decode(file_get_contents("../db"), true);

    foreach ($db as $x) {

        $id = $x["id"];
        $name = $x["name"];
        $ext = $x["ext"];
        $cell = $x["cell"];

        if (substr_compare($name, $pattern, 0, $len, true) == 0 ||
            substr_compare($ext, $pattern, 0) == 0 ||
            substr_compare($cell, $pattern, 0) == 0) {

            // $logger->wr("MATCH: $name / $ext / $cell");

            array_push($res, array("id"    => $id,
                                   "name"  => $name,
                                   "ext"   => $ext,
                                   "cell"  => $cell));
        } 
    }

    return;
}


// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Input validation
if (!isset($data['cmd'])) {

    $logger->wr("No \"cmd\" variable present in the post data!"); 
    exit(1);
}

$cmd = $data['cmd'];

if ($debug) { $logger->wr("CMD: $cmd"); }


switch($cmd) {

    case "search":

        if (!isset($data['pattern'])) {

            $logger->wr("FATAL: CMD is $cmd, but \"pattern\" is not defined!");
            exit(1);
        }

        if ($debug) { $logger->wr("PATTERN: " . $data['pattern']); }

        $logger->wr("Search function have been called ...");
        $res["options"] = array();
        search($data['pattern'], $res["options"]);

        break;

    default:
        $logger->wr("Invalid command: $cmd");
        break;
}


// Return the response encoded as a JSON string
// $logger->wr(print_r($res, true));
$json_res = json_encode($res);
$logger->wr($json_res);
echo $json_res


?>
