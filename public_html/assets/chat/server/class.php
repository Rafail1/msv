<?php

include_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/Chat.php';

class ServerChat extends Chat {

    private $activeChats;
    private $lastAccess;

    public function __construct() {
        parent::__construct();
        $this->setActiveChats();
    }

    public function setActiveChats() {
        $iterator = new \DirectoryIterator($this->archDir);
        $chats = [];
        foreach ($iterator as $info) {
            if (!$info->isFile()) {
                continue;
            }
            if (strpos($info->getFilename(), "server") === 0) {
                $who = "server";
            } else {
                $who = "client";
            }

            $fid = str_replace($who, "", $info->getFilename());
            if (!$this->lastAccess[$fid] || $this->lastAccess[$fid] < $info->getMTime()) {
                $this->lastAccess[$fid] = $info->getMTime();
            }
            if (!in_array($fid, $chats)) {
                $chats[] = $fid;
            }
        }
        $this->activeChats = $chats;
    }

    public function getChat($id, $time = false) {
        $clientFile = "client" . $id;
        $serverFile = "server" . $id;

        $client = $this->getArchive($clientFile, "client", $time);
        $server = $this->getArchive($serverFile, "server", $time);

        $messages = array_merge($client, $server);
        usort($messages, array($this, 'comp'));
        
        return $messages;
    }

    public function getNew($time) {
        foreach ($this->lastAccess as $id => $la) {
            if ($la > $time) {
                $messages = $this->getChat($id, $time);

                $response[$id] = $messages;
            }
        }
        return $response;
    }

    public function getActiveChats() {
        return $this->activeChats;
    }

    public function getArchives() {
        return $this->archives;
    }

    public function setChats() {
        foreach ($this->activeChats as $id) {
            $messages = $this->getChat($id);
            $this->archives[$id] = $messages;
        }
    }

}
