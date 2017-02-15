$(document).ready(function() {
    
    

    $('#chat-btn').on('click', function(e) {
        emotions();

    });

    function emotions() {
        var comment = $('#comment').val()
        if (comment == "") {
            alert("Please write something in textarea.");
        } else {
            var textWithEmoticons = $.emoticons.replace(comment);
            $(".message-rpt .media-body").append("<div class='msg-rtbox'><div class='chat-msg-desc chat-ltblue'>" + textWithEmoticons + "</div><span class='pull-right'>11:30 am</span></div>");

            $('comment').val("");
        }
    }
});
