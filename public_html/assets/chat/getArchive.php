<?php

function getArchive($fname, $who) {
    $sep = "|||";
    $sepTime = "||";
    if (file_exists($fname)) {
        $content = file_get_contents($fname);
        $messages = explode($sep, $content);
        foreach ($messages as $k => $mess) {
            if (!$mess) {
                continue;
            }
            $mess = explode($sepTime, $mess);
            $time = $mess[1];
            $message = $mess[0];

            $response[] = ["id" => $time, "time" => $time, "text" => $message, "who" => $who];
        }
    } else {
        $response = [];
    }
    return $response;
}
function comp($a, $b) {
    if($a["time"] > $b["time"]) {
        return 1;
    } elseif($a["time"] < $b["time"]) {
        return - 1;
    }
    return 0;
}
$fname = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive/client" . filter_input(INPUT_POST, "client_id");
$client = getArchive($fname, "client");

$fname = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive/server" . filter_input(INPUT_POST, "client_id");
$server = getArchive($fname, "server");
$messages = array_merge($client,$server);
usort($messages, "comp");
echo json_encode(["messages" => $messages]);
