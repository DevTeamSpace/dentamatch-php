$(document).ready(function() {
    var socket = io(socketUrl);
    socket.on('connect', function () {
        console.log(socket);

        socket.emit('init', {userId : userId, userName : officeName,userType : 2},function(response){

        });

        $('.modalClick').click(function(e){
            $('#seekerId').val($(this).data('seekerid'));
            $('#chatMsg').val('');
        });

        $('#sendChat').click(function(e){
            e.preventDefault();
            var chatMsg = $('#chatMsg').val();
            var seekerId = $('#seekerId').val();
            console.log(seekerId);
            var data = {fromId:userId, toId:seekerId, message:chatMsg, messageFrom:1};
            if(chatMsg!=''){
                socket.emit('sendMessage', data, function(msgObj){
                    console.log(msgObj);
                    $('.modal .close').click();
                });
            }
        });
        socket.on('receiveMessage', function (data) {

            console.log(data);
        });
    
    });
});

    $(function () {
        $('body').on('click', '.pagination a', function (e) {
            e.preventDefault();

            $('#load a').css('color', '#dfecf6');
            $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');
            getArticles(url);
            window.history.pushState("", "", url);
        });

        function getArticles(url) {
            $.ajax({
                url: url
            }).done(function (data) {
                $('#ajaxData').html(data);
            }).fail(function () {

            });
        }
    });
    
    function markFavourite(seekerId) {
        url = urlFav+'/'+seekerId;
        $.ajax({
            url: url
        }).done(function (data) {
            
            if(data.isFavourite=="Yes") {
                $("#fav_"+data.seekerId).removeClass('star-empty').addClass('star-fill');
            } else if(data.isFavourite=="No") {
                $("#fav_"+data.seekerId).removeClass('star-fill').addClass('star-empty');
            }

        }).fail(function () {
            alert("failed");
        });

    }
    
