<?php
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/defines.php';
if (filter_input(INPUT_POST, "message") && filter_input(INPUT_POST, "client_id")) {
    $fname = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive/client" . filter_input(INPUT_POST, "client_id");
    if (file_exists($fname)) {
        $content = file_get_contents($fname);
        $messages = explode(SEP, $content);
        $lm = $messages[count($messages) - 1];
        $mess = explode(SEP_TIME, $lm);
        $id = $mess[1];
        $message = $mess[0];
    } else {
        $id = 0;
    }

    $fp = @fopen($fname, "a+");
    fwrite($fp, SEP . filter_input(INPUT_POST, "message").SEP_TIME.time());
    fclose($fp);
    echo json_encode(["message" => ["text" => filter_input(INPUT_POST, "message"), "id" => $id], "status" => "1"]);
}