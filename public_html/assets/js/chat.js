function myChat(client_id) {
    this.server = {};
    this.client_id = client_id;
    this.started = false;
    this.messages = {server:[], client:[]};
    this.chatWindow = $("#chat-window");
    this.getArchive_url = "/assets/chat/getArchive.php";
    this.check_url = "/assets/chat/check.php";
    this.send_url = "/assets/chat/send.php";

}

myChat.prototype = {
    constructor: myChat(),
    start: function () {
        this.started = true;
        this.getArchive();
    },
    getArchive: function(callback) {
        var self = this;
        $.ajax({
            url: this.getArchive_url,
            data:{client_id:this.client_id},
            type:"post",
            success: function (res) {
                res = JSON.parse(res);
                if (res) {
                    self.render(res);
                }
                self.check();
            },
            error: function (res) {
                console.log(res);
                self.check();
            }
        });
    },
    check: function () {
        var self = this;
        $.ajax({
            url: this.check_url,
            data:{client_id:this.client_id},
            type:"post",
            success: function (res) {
                res = JSON.parse(res);
                if (res) {
                    self.answer(res);
                }
                setTimeout(function () {
                    self.check();
                }, 1000);
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    send: function (message) {
        var self = this;
        $.ajax({
            url: this.send_url,
            type: "post",
            data: {client_id:self.client_id, message:message},
            success: function (res) {
                res = JSON.parse(res);
                self.addMessage(res.message);
            },
            error: function (res) {
                console.log(res);
            }
        });
    },
    setOnline: function (online) {
        this.server.online = online;
    },
    answer: function (data) {
        if(data.message) {
            this.addMessage(data.message);
        }

        if (data.status === "1") {
            this.setOnline(true);
        } else {
            this.setOnline(false);
        }

    },
    addMessage: function (message) {
        
        if(this.messages[message.who] && this.messages[message.who].length) {
            for(var i in this.messages[message.who]) {
                if(this.messages[message.who][i].id === message.id) return;
            }
        } else {
            this.messages[message.who] = [];
        }
        this.messages[message.who].push(message);
        this.chatWindow.append(this.newMessage(message));
    },
    newMessage: function (message) {
        return "<p class='" + message.who + "' data-id='" + message.id + "'>" + message.text + "</p>";
    },
    render: function (data) {
        console.log(data);
        for (var i in data.messages) {
            this.addMessage(data.messages[i]);
        }
        
    }
};