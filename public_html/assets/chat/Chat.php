<?php

class Chat {

    private $archDir;
    private $archives;

    public function __construct() {
        $this->archDir = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive";
        $this->archives = [];
    }

    public function comp($a, $b) {
        if ($a["time"] > $b["time"]) {
            return 1;
        } elseif ($a["time"] < $b["time"]) {
            return - 1;
        }
        return 0;
    }

    public function getArchive($filename, $who, $fromTime = 0) {

        $fname = $this->archDir . "/" . $filename;
        $response = [];
        if (!file_exists($fname)) {
            return $response;
        }
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
        
        return $response;
    }

}
