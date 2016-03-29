<?php

class ServerChat {

    private $archDir;
    private $activeChats;
    private $archives;
    private $lastAccess;

    public function __construct() {
        $this->archDir = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive";
        $this->archives = [];
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

    public function getNew($time) {
        foreach ($this->lastAccess as $id => $la) {
            if ($la > $time) {
                
                $clientFile = "client" . $id;
                $serverFile = "server" . $id;

                $client = $this->getArchive($clientFile, "client", $time);
                $server = $this->getArchive($serverFile, "server", $time);

                $messages = array_merge($client, $server);
                usort($messages, array($this, 'comp'));
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

    public function getArchive($filename, $who, $fromTime = 0) {

        $fname = $this->archDir . "/" . $filename;
        $response = [];
        if (file_exists($fname)) {
            $content = file_get_contents($fname);
            $messages = explode(SEP, $content);
            foreach ($messages as $mess) {
                if (!$mess) {
                    continue;
                }
                $mess = explode(SEP_TIME, $mess);
                $time = $mess[1];
                if ($fromTime && $time < $fromTime) {
                    continue;
                }
                $message = $mess[0];

                $response[] = ["id" => $time, "time" => $time, "text" => $message, "who" => $who];
            }
        }



        return $response;
    }

}
