<?php
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/defines.php';
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/client/class.php';

$clientChat = new ClientChat(filter_input(INPUT_POST, "client_id"));
echo $clientChat->getStory();
