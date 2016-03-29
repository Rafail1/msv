<?php session_start(); ?>
<html>
    <head>
        <title>Chat</title>
        <meta charset="utf8">
        <script src="/assets/js/libs/jquery-1.11.0.min.js"></script>
        <script src="/assets/js/chat.js"></script>
    </head>
    <body class="client">
        <div class="chat" id="<?php echo $_COOKIE["PHPSESSID"] ? $_COOKIE["PHPSESSID"] : session_id(); ?>">
            <div class="chat-window">

            </div>
            <textarea class="client-window"></textarea>
            <button class="send-message">Send</button>
        </div>
        <script>
            var chat = new myChat('<?php echo $_COOKIE["PHPSESSID"] ? $_COOKIE["PHPSESSID"] : session_id(); ?>');
            chat.start();
            $(".send-message").on("click", function () {
                chat.send($(".client-window").val());
                $(".client-window").val("");
            })
        </script>
    </body>
</html>

