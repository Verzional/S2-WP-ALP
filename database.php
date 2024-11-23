<?php
session_start();
function connect()
{
    $host = "127.0.0.1";
    $user = "root";
    $pwd = "";
    $database = "uc_cravings";

    $conn = mysqli_connect($host, $user, $pwd, $database) or die("Error in Connecting");
    return $conn;
}

function close($conn)
{
    mysqli_close($conn);
}

?>