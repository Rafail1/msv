<html>
    <head>
        <title>Chat</title>
        <meta charset="utf8">
        <script src="/assets/js/libs/jquery-1.11.0.min.js"></script>
        <script src="/assets/js/serverChat.js"></script>
    </head>
    <body>
        <?php foreach($chats as $id => $chat) { ?>
        <div id="chat<?php echo $id; ?>">
            <div id="chat-window<?php echo $id; ?>">

            </div>
            <textarea id="client-window<?php echo $id; ?>"></textarea>
            <button id="send-message<?php echo $id; ?>">Send</button>
        </div>
        <?php } ?>
    </body>
</html>

