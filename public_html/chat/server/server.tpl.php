<html>
    <head>
        <title>Chat</title>
        <meta charset="utf8">
        <script src="/assets/js/libs/jquery-1.11.0.min.js"></script>
        <script src="/assets/js/serverChat.js"></script>
    </head>
    <body class="server">

        <?php foreach($chats as $id => $chat) { ?>
        <div id="<?php echo $id; ?>" class="chat">
            <div class="chat-window">
                
            </div>
            <textarea class="client-window"></textarea>
            <button class="send-message">Send</button>
        </div>
        <?php } ?>
        <script>
            var chats = <?php echo json_encode($chats); ?>;
            var sChat = new serverChat();
            sChat.start();
        </script>
    </body>
</html>