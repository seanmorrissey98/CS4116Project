let currChat;
let latest_timestamp;

let retrieving_new_messages = false;

$(function () {
    $("#send-message").submit((e) => {
        e.preventDefault();
        newMessage();
    });

    $("#new-message-send-icon").on('click', () => {
        newMessage();
    });

    let messageBody = $('#message-section');
    messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;

    window.setInterval(function () {
        if (!retrieving_new_messages)
            getNewMessages();
    }, 1000);

    updateScroll();
});

function expandChat(chat) {
    currChat = chat;
    $("#header-name").text(chat.first_name);

    // Fire off the request to /form.php
    $.post('chat.php', {'chat_id': chat.chat_id}, response => {

        let messages = $.parseJSON(response);

        if (messages !== '') {
            $("#message-section").html(messages);
            latest_timestamp = $("#message-section").children().last().attr('data-timestamp');
            updateScroll();
        } else {
            $("#message-section").html('<p id="empty-chat-message">Say Hi to ' + chat.first_name + '!</p>');
        }
    });
}

function newMessage() {
    let newMsg = $("#new-message").val();

    $("#empty-chat-message").remove();

    if (newMsg === '') return;
    if (currChat === null) return;

    $.post('newMessage.php', {'message': newMsg, 'chat_id': currChat.chat_id, 'user_id_receiver': currChat.user_id_receiver}, response => {

        let message = $.parseJSON(response);

        $("#message-section").append(message);
        $("#new-message").val('');

        updateScroll();
    });
}

function getNewMessages() {
    if (latest_timestamp === undefined)
        latest_timestamp = currChat.latest_message_timestamp;

    retrieving_new_messages = true;

    $.post('chat.php', {'chat_id': currChat.chat_id, 'timestamp': latest_timestamp}, response => {

        retrieving_new_messages = false;

        let messages = $.parseJSON(response);

        if (messages !== '') {
            $("#message-section").append(messages);
            latest_timestamp = $("#message-section").children().last().attr('data-timestamp');
            updateScroll();
        }
    });
}

function updateScroll() {
    let element = document.getElementById("message-section");
    element.scrollTop = element.scrollHeight;
}
