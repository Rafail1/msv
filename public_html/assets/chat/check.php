<?php

$sep = "|||";
$sepTime = "||";
$helloMess = "Здравствуйте";
$fname = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive/server" . filter_input(INPUT_POST, "client_id");
if (!file_exists($fname)) {
    $fp = fopen($fname, "w");
    fwrite($fp, $sep . $helloMess . $sepTime . time());
    fclose($fp);
    $response = ["status" => "1"];
} else {
    $response = getLastMessage($fname);
}

echo json_encode($response);

function getLastMessage($fname) {
    $sep = "|||";
    $sepTime = "||";
    if (file_exists($fname)) {
        $content = file_get_contents($fname);
        $messages = explode($sep, $content);
        $lm = $messages[count($messages) - 1];
        $mess = explode($sepTime, $lm);
        $time = $mess[1];
        $message = $mess[0];
        $response = ["message" => ["id" => $time, "time" => $time, "text" => $message, "who" => "server"], "status" => "1"];
    } else {
        $response = ["status" => "1"];
    }
    return $response;
}
