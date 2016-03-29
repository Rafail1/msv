function serverChat() {
    this.check_url = "/assets/chat/server/check.php";
}

serverChat.prototype = {
    constructor: serverChat(),
    start: function () {
        this.time = parseInt(new Date().getTime() / 1000);
        this.started = true;
        this.check();
    },
    setOnline : function() {
        console.log("setOnline");
    },
    check: function () {
        var self = this;
        $.ajax({
            url: this.check_url,
            data:{time:this.time},
            type:"post",
            success: function (res) {
                res = JSON.parse(res);
                if (res) {
                    this.time = parseInt(new Date().getTime() / 1000);
                    this.answer(res);
                }
                setTimeout(function () {
                    self.check();
                }, 10000);
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