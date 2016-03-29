<?php
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/defines.php';

$fname = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive/server" . filter_input(INPUT_POST, "client_id");
if (!file_exists($fname)) {
    $fp = fopen($fname, "w");
    fwrite($fp, SEP . HELLO_MESSAGE . SEP_TIME . time());
    fclose($fp);
    $response = ["status" => "1"];
} else {
    $response = getLastMessage($fname);
}

echo json_encode($response);

function getLastMessage($fname) {
   
    if (file_exists($fname)) {
        $content = file_get_contents($fname);
        $messages = explode(SEP, $content);
        $lm = $messages[count($messages) - 1];
        $mess = explode(SEP_TIME, $lm);
        $time = $mess[1];
        $message = $mess[0];
        $response = ["message" => ["id" => $time, "time" => $time, "text" => $message, "who" => "server"], "status" => "1"];
    } else {
        $response = ["status" => "1"];
    }
    return $response;
}
