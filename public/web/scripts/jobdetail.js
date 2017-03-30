$(document).ready(function() {
    $('.modalClick').hide();
    var socket = io(socketUrl);
    socket.on('connect', function () {
        console.log(socket);
        socket.on('disconnect', function() {
            $('.modalClick').hide();
            console.info('Socket disconnect');
        });
        socket.emit('init', {userId : userId, userName : officeName,userType : 2},function(response){
            $('.modalClick').show();
        });
        $('.modalClick').off('click');
        $('.modalClick').on('click',function(e){
            $('#seekerId').val($(this).data('seekerid'));
            $('#chatMsg').val('');
        });
        $('#sendChat').off('click');
        $('#sendChat').on('click',function(e){
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
        socket.off('receiveMessage');
        socket.on('receiveMessage', function (data) {

            console.log(data);
        });
        socket.on('logoutPreviousSession',function(response){
            $('#logoutMessageBox').modal('show');
            setTimeout(function(){ window.location.href='logout'; }, 3000);
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
    
