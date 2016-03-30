<?php
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/defines.php';
include filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/server/class.php';


$ServerChat = new ServerChat();
$ServerChat->setChats();
$chats = $ServerChat->getArchives();
include 'server.tpl.php';
