<?php

include_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/assets/chat/Chat.php';

class ClientChat extends Chat {

    private $client_id;

    public function __construct($client_id) {
        parent::__construct();
        $this->client_id = $client_id;
    }

    public function getMessage($time) {
        $fname = filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/chat/archive/server" . $this->client_id;
        if (!file_exists($fname)) {
            $fp = fopen($fname, "w");
            fwrite($fp, SEP . HELLO_MESSAGE . SEP_TIME . time());
            fclose($fp);
        }
        $response = $this->getArchive($fname, "server", $time);
        return json_encode($response);
    }
    public function getStory() {
        $fname = "client" . $this->client_id;
        $client = $this->getArchive($fname, "client");

        $fname = "server" . $this->client_id;
        $server = $this->getArchive($fname, "server");
        $messages = array_merge($client,$server);
        usort($messages, array($this, 'comp'));
        return json_encode(["messages" => $messages]);
    }
    
}
