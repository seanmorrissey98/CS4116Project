$(function () {
    $("#send-message").submit((e) => {
        e.preventDefault();
        newMessage();
    });

    $("#new-message-send-icon").on('click', () => {
        newMessage();
    });

    function newMessage() {
        let newMsg = $("#new-message").val();

        console.log(newMsg);
        console.log(currChat);

        if (newMsg === '') return;
        if (currChat === null) return;

        $.post('newMessage.php', {'message': newMsg, 'chat_id': currChat.chat_id, 'user_id_receiver': currChat.user_id_receiver}, response => {

            let message = $.parseJSON(response);
            console.log(message);

            $("#message-section").append(message);
            $("#new-message").val('');
        });
    }
});

let currChat;

function expandChat(chat) {
    currChat = chat;

    // Fire off the request to /form.php
    $.post('chat.php', {'chat_id': chat.chat_id}, response => {

        let messages = $.parseJSON(response);

        $("#message-section").html(messages);
    });
}