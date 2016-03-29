<?php
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/defines.php';
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/server/class.php';
$ServerChat = new ServerChat();
$response = $ServerChat->getNew(filter_input(INPUT_POST, "time"));
echo json_encode($response);