<?php
session_start();
$pd = $_SESSION["paddleHeight"];
$bs = $_SESSION["ballSpeed"];

$response = [
    "status"=>200,
    "data"=>[
        "paddleHeight"=>$pd,
        "paddleWidth" => 50,
        "ballSize"=>10,
        "ballSpeed"=>$bs,
        "paddleSpeed"=>3
    ]
    ];
echo json_encode($response);
?>
