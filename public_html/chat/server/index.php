<?php

class ServerChat {

    private $archDir;
    private $sep;
    private $sepTime;
    private $activeChats;
    private $archives;

    public function __construct() {
        $this->archDir = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive";

        $this->sep = "|||";
        $this->sepTime = "||";
        $this->archives = [];
        $this->setActiveChats();
        $this->setChats();
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
            if (!in_array($fid, $chats)) {
                $chats[] = $fid;
            }
        }
        $this->activeChats = $chats;
    }

    public function getActiveChats() {
        return $this->activeChats;
    }

    public function getArchives() {
        return $this->archives;
    }

    public function comp($a, $b) {
        if ($a["time"] > $b["time"]) {
            return 1;
        } elseif ($a["time"] < $b["time"]) {
            return - 1;
        }
        return 0;
    }

    public function setChats() {
        foreach ($this->activeChats as $id) {
            $clientFile = "client" . $id;
            $serverFile = "server" . $id;
            
            $client = $this->getArchive($clientFile, "client");
            $server = $this->getArchive($serverFile, "server");
            
            $messages = array_merge($client, $server);
            usort($messages, array($this, 'comp'));
            $this->archives[$id] = $messages; 
                    
        }
    }

    public function getArchive($filename, $who) {

        $fname = $this->archDir . "/" . $filename;
        if (!file_exists($fname)) {
            $response = [];
        } else {
            $content = file_get_contents($fname);
            $messages = explode($this->sep, $content);
            foreach ($messages as $mess) {
                if (!$mess) {
                    continue;
                }
                $mess = explode($this->sepTime, $mess);
                $time = $mess[1];
                $message = $mess[0];

                $response[] = ["id" => $time, "time" => $time, "text" => $message, "who" => $who];
            }
        }



        return $response;
    }

}

$ServerChat = new ServerChat();
$chats = $ServerChat->getArchives();
include 'server.tpl.php';
