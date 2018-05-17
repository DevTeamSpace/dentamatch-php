$(document).ready(function() {
    $('.comment').keyup(function(e) {
        if (e.keyCode == 13) {
            var chatComment = $(this).val().trim();
            chatComment = chatComment.replace(/\n|\r/g, "");
            if (chatComment != '') {
                $(this).val(chatComment);
                $('.chatSend').click();
            } else {
                console.log(chatComment);
                $(this).val('');
            }
        }
    });
    //var definition = { "smile": { "title": "Smile", "codes": [":)", ":=)", ":-)"] }, "sad-smile": { "title": "Sad Smile", "codes": [":(", ":=(", ":-("] }, "big-smile": { "title": "Big Smile", "codes": [":D", ":=D", ":-D", ":d", ":=d", ":-d"] }, "cool": { "title": "Cool", "codes": ["8)", "8=)", "8-)", "B)", "B=)", "B-)", "(cool)"] }, "wink": { "title": "Wink", "codes": [":o", ":=o", ":-o", ":O", ":=O", ":-O"] }, "crying": { "title": "Crying", "codes": [";(", ";-(", ";=("] }, "sweating": { "title": "Sweating", "codes": ["(sweat)", "(:|"] }, "speechless": { "title": "Speechless", "codes": [":|", ":=|", ":-|"] }, "kiss": { "title": "Kiss", "codes": [":*", ":=*", ":-*"] }, "tongue-out": { "title": "Tongue Out", "codes": [":P", ":=P", ":-P", ":p", ":=p", ":-p"] }, "blush": { "title": "Blush", "codes": ["(blush)", ":$", ":-$", ":=$", ":\">"] }, "wondering": { "title": "Wondering", "codes": [":^)"] }, "sleepy": { "title": "Sleepy", "codes": ["|-)", "I-)", "I=)", "(snooze)"] }, "dull": { "title": "Dull", "codes": ["|(", "|-(", "|=("] }, "in-love": { "title": "In love", "codes": ["(inlove)"] }, "evil-grin": { "title": "Evil grin", "codes": ["]:)", ">:)", "(grin)"] }, "talking": { "title": "Talking", "codes": ["(talk)"] }, "yawn": { "title": "Yawn", "codes": ["(yawn)", "|-()"] }, "puke": { "title": "Puke", "codes": ["(puke)", ":&", ":-&", ":=&"] }, "doh!": { "title": "Doh!", "codes": ["(doh)"] }, "angry": { "title": "Angry", "codes": [":@", ":-@", ":=@", "x(", "x-(", "x=(", "X(", "X-(", "X=("] }, "it-wasnt-me": { "title": "It wasn't me", "codes": ["(wasntme)"] }, "party": { "title": "Party!!!", "codes": ["(party)"] }, "worried": { "title": "Worried", "codes": [":S", ":-S", ":=S", ":s", ":-s", ":=s"] }, "mmm": { "title": "Mmm...", "codes": ["(mm)"] }, "nerd": { "title": "Nerd", "codes": ["8-|", "B-|", "8|", "B|", "8=|", "B=|", "(nerd)"] }, "lips-sealed": { "title": "Lips Sealed", "codes": [":x", ":-x", ":X", ":-X", ":#", ":-#", ":=x", ":=X", ":=#"] }, "hi": { "title": "Hi", "codes": ["(hi)"] }, "call": { "title": "Call", "codes": ["(call)"] }, "devil": { "title": "Devil", "codes": ["(devil)"] }, "angel": { "title": "Angel", "codes": ["(angel)"] }, "envy": { "title": "Envy", "codes": ["(envy)"] }, "wait": { "title": "Wait", "codes": ["(wait)"] }, "bear": { "title": "Bear", "codes": ["(bear)", "(hug)"] }, "make-up": { "title": "Make-up", "codes": ["(makeup)", "(kate)"] }, "covered-laugh": { "title": "Covered Laugh", "codes": ["(giggle)", "(chuckle)"] }, "clapping-hands": { "title": "Clapping Hands", "codes": ["(clap)"] }, "thinking": { "title": "Thinking", "codes": ["(think)", ":?", ":-?", ":=?"] }, "bow": { "title": "Bow", "codes": ["(bow)"] }, "rofl": { "title": "Rolling on the floor laughing", "codes": ["(rofl)"] }, "whew": { "title": "Whew", "codes": ["(whew)"] }, "happy": { "title": "Happy", "codes": ["(happy)"] }, "smirking": { "title": "Smirking", "codes": ["(smirk)"] }, "nodding": { "title": "Nodding", "codes": ["(nod)"] }, "shaking": { "title": "Shaking", "codes": ["(shake)"] }, "punch": { "title": "Punch", "codes": ["(punch)"] }, "emo": { "title": "Emo", "codes": ["(emo)"] }, "yes": { "title": "Yes", "codes": ["(y)", "(Y)", "(ok)"] }, "no": { "title": "No", "codes": ["(n)", "(N)"] }, "handshake": { "title": "Shaking Hands", "codes": ["(handshake)"] }, "skype": { "title": "Skype", "codes": ["(skype)", "(ss)"] }, "heart": { "title": "Heart", "codes": ["(h)", "<3", "(H)", "(l)", "(L)"] }, "broken-heart": { "title": "Broken heart", "codes": ["(u)", "(U)"] }, "mail": { "title": "Mail", "codes": ["(e)", "(m)"] }, "flower": { "title": "Flower", "codes": ["(f)", "(F)"] }, "rain": { "title": "Rain", "codes": ["(rain)", "(london)", "(st)"] }, "sun": { "title": "Sun", "codes": ["(sun)"] }, "time": { "title": "Time", "codes": ["(o)", "(O)", "(time)"] }, "music": { "title": "Music", "codes": ["(music)"] }, "movie": { "title": "Movie", "codes": ["(~)", "(film)", "(movie)"] }, "phone": { "title": "Phone", "codes": ["(mp)", "(ph)"] }, "coffee": { "title": "Coffee", "codes": ["(coffee)"] }, "pizza": { "title": "Pizza", "codes": ["(pizza)", "(pi)"] }, "cash": { "title": "Cash", "codes": ["(cash)", "(mo)", "($)"] }, "muscle": { "title": "Muscle", "codes": ["(muscle)", "(flex)"] }, "cake": { "title": "Cake", "codes": ["(^)", "(cake)"] }, "beer": { "title": "Beer", "codes": ["(beer)"] }, "drink": { "title": "Drink", "codes": ["(d)", "(D)"] }, "dance": { "title": "Dance", "codes": ["(dance)", "\o/", "\:D/", "\:d/"] }, "ninja": { "title": "Ninja", "codes": ["(ninja)"] }, "star": { "title": "Star", "codes": ["(*)"] }, "mooning": { "title": "Mooning", "codes": ["(mooning)"] }, "finger": { "title": "Finger", "codes": ["(finger)"] }, "bandit": { "title": "Bandit", "codes": ["(bandit)"] }, "drunk": { "title": "Drunk", "codes": ["(drunk)"] }, "smoking": { "title": "Smoking", "codes": ["(smoking)", "(smoke)", "(ci)"] }, "toivo": { "title": "Toivo", "codes": ["(toivo)"] }, "rock": { "title": "Rock", "codes": ["(rock)"] }, "headbang": { "title": "Headbang", "codes": ["(headbang)", "(banghead)"] }, "bug": { "title": "Bug", "codes": ["(bug)"] }, "fubar": { "title": "Fubar", "codes": ["(fubar)"] }, "poolparty": { "title": "Poolparty", "codes": ["(poolparty)"] }, "swearing": { "title": "Swearing", "codes": ["(swear)"] }, "tmi": { "title": "TMI", "codes": ["(tmi)"] }, "heidy": { "title": "Heidy", "codes": ["(heidy)"] }, "myspace": { "title": "MySpace", "codes": ["(MySpace)"] }, "malthe": { "title": "Malthe", "codes": ["(malthe)"] }, "tauri": { "title": "Tauri", "codes": ["(tauri)"] }, "priidu": { "title": "Priidu", "codes": ["(priidu)"] } };
    //$.emoticons.define(definition);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var loadPage = false;
    $('.modalClick').hide();
    var socket = io(socketUrl,{reconnection:true,reconnectionAttempts:'Infinity'});
    socket.on('connect', function() {
        console.info('Socket ');
        socket.send("Hello World");
        socket.on('disconnect', function() {
            $('.modalClick').hide();
            console.info('Socket disconnect');
            loadPage = true;
        });

        var currentSel = '';
        function initSocket(){
            console.info('Socket Init');
            socket.emit('init', { userId: fromId, userName: userName, userType: 2 }, function(response) {
                if(loadPage==true){
                    loadPage=false;
                    $('.leftSeekerPanelRow').each(function(index,obj){
                        var lastMsgId = obj.attr('data-last');
                        var userId = obj.attr('data-user');
                        loadLeftMessages(lastMsgId,userId);
                    });
                    
                }
                $('.modalClick').show();
            });
        }
        initSocket();
        function loadLeftMessages(lastMsgId,appendToId){
            socket.emit('getLeftMessages', {messageId: lastMsgId, fromId: fromId, toId: appendToId }, function(response) {
                var appendHtml = '';
                $.each(response, function(index, msgObj) {
                    appendHtml = writeHtmlBlock(msgObj) + appendHtml;
                });
                $('#user-chat-content_'+appendToId).append(appendHtml);
                if(appendToId==currentSel){
                    chatScroll();
                }else {
                    var badgeObj = $('#li_' + appendToId).find('.badge');
                    var badgeCount = parseInt(badgeObj.html(), 10) + response.length;
                    if(badgeCount>0){
                        badgeObj.removeClass('hide');
                        badgeObj.html(badgeCount);
                    }
                }
            });
        }
        
        
        $('.modalClick').off('click');
        $('.modalClick').on('click',function(e){
            $('#seekerId').val($(this).data('seekerid'));
            $('#chatMsg').val('');
        });
        socket.on('logoutPreviousSession',function(response){
            $('#logoutMessageBox').modal('show');
            setTimeout(function(){ window.location.href='/logout'; }, 3000);
        });
        $('.leftSeekerPanelRow').click(function(e) {
            var dataLoaded = $(this).attr('data-loaded');
            var toId = $(this).data('user');
            var obj = $('#li_' + toId).find('.badge');
            var unreadCount = obj.html();
            if (unreadCount > 0) {
                socket.emit('updateReadCount', { fromId: toId, toId: fromId }, function(response) {
                    console.log(response);
                });
                obj.html(0);
                obj.addClass('hide');
            }
            currentSel = toId;
            if (dataLoaded == '0') {
                loadMessages(1);
            } else {
                chatScroll();
            }
        });
        //$('.leftSeekerPanelRow:eq(0)').trigger('click');
        //$('.leftSeekerPanelRow:eq(0), .tab-pane:eq(0)').addClass('active');
        //$('.chatScroller').mCustomScrollbar();

        socket.emit('unreadCount', { fromId: fromId }, function(unreadObj) {
            console.log(unreadObj);
            $.each(unreadObj, function(index, unread) {
                var obj = $('#li_' + unread.fromId).find('.badge');
                obj.addClass('hide');
                if (unread.unreadCount > 0) {
                    obj.html(unread.unreadCount);
                    obj.removeClass('hide');
                }
            });
        });

        $('.chatSend').click(function(e) {
            var chatMsg = $(this).closest('.msgDiv').find('textarea').val().trim();
            //chatMsg = $.emoticons.replace(chatMsg);

            var seekerId = $(this).data('seekerid');
            var data = { fromId: fromId, toId: seekerId, message: chatMsg };

            if(chatMsg!=''){
                socket.emit('sendMessage', data, function(msgObj){
                    if(!msgObj.blocked){
                        var appendHtml = writeHtmlBlock(msgObj);
                        $('#user-chat-content_'+seekerId).append(appendHtml);
                        chatScroll();
                    }
                });
                $(this).closest('.msgDiv').find('textarea').val('');
            }
        });

        $(".content").mCustomScrollbar({
            callbacks: {
                onTotalScrollBack: function() {
                    var page = $('#pr_' + currentSel).data('page');
                    var dataToBeLoaded = $('#li_' + currentSel).attr('data-loaded');
                    if (dataToBeLoaded == "1") {
                        loadMessages(page + 1);
                        console.log("Scrolled back to the beginning of content.");
                    }
                }
            }
        });


        function chatScroll() {
            var scrollTo = $('#li_' + currentSel).attr('data-last');
            var divScrollTo = '#block_' + currentSel + '_' + scrollTo
            $('#chat-scroller' + currentSel).mCustomScrollbar("update");
            $('#chat-scroller' + currentSel).mCustomScrollbar("scrollTo", divScrollTo, { scrollInertia: 0 });
        }

        function pad(n) {
            return (n < 10) ? '0' + n : n;
        }

        function writeHtmlBlock(msgObj) {
            var datetime = new Date(msgObj.sentTime);
            var hours = datetime.getHours();
            var minutes = datetime.getMinutes();
            var amOrPm = ' am';
            if (hours > 12) {
                hours -= 12;
                amOrPm = ' pm'
            } else if (hours === 0) {
                hours = 12;
            }
            var todisplay = pad(hours) + ':' + pad(minutes) + amOrPm;
            var leftRightClass = 'pull-right';
            var chatColor = 'chat-ltblue';
            if (msgObj.fromId != fromId) {
                leftRightClass = 'pull-left';
                chatColor = 'chat-ltgrey';
            }
            var html = '<div id="block_' + currentSel + '_' + msgObj.messageId + '" class="message-rpt ' + leftRightClass + '">';
            html += '<div class="wd50 ' + leftRightClass + '">';
            html += '<div class="media ' + leftRightClass + '">';
            html += '<div class="media-body ">';
            html += '<div class="chat-msg-desc ' + chatColor + '">' + msgObj.message;
            html += '</div><span class="' + leftRightClass + '">' + todisplay + '</span>';
            html += '</div></div></div></div>';
            $('#li_' + currentSel).attr('data-last', msgObj.messageId);
            return html;
        }

        function loadMessages(datapage) {
            socket.emit('getHistory', { fromId: fromId, toId: currentSel, pageNo: datapage, source: 1 });
        }
        function blockUnblockSeeker(blockStatus){
            socket.emit('blockUnblock',{ fromId:fromId, toId:currentSel, blockStatus:blockStatus },function(callback){
                console.log(callback);
            });
        }

        socket.on('getMessages', function(msgDateArr) {
            var obj = $('#pr_' + currentSel);
            var page = obj.data('page');
            $('#li_' + currentSel).attr('data-loaded', 1);
            console.log(msgDateArr);
            if ($.isEmptyObject(msgDateArr)) {
                $('#li_' + currentSel).attr('data-loaded', 2);
            } else {
                obj.data('page', page + 1);
                console.log(page + 1);
                obj.attr('data-page', page + 1);

                var appendHtml = '';
                var currDateObj = new Date();
                var prevDateObj = new Date();
                prevDateObj.setDate(currDateObj.getDate() - 1);
                var currDate = currDateObj.getFullYear() + '_' + currDateObj.getMonth() + '_' + currDateObj.getDate();
                var prevDate = prevDateObj.getFullYear() + '_' + prevDateObj.getMonth() + '_' + prevDateObj.getDate();
                console.log(currDate);
                $.each(msgDateArr, function(dateKey, msgDate) {
                    var dateArr = dateKey.split('_');
                    var divId = currentSel + '_' + dateKey;
                    if ($('#' + divId)) {
                        $('#' + divId).remove();
                    }
                    var viewText = months[dateArr[1]] + ' ' + dateArr[2] + ', ' + dateArr[0];
                    if (dateKey == currDate) {
                        viewText = 'Today';
                    } else if (dateKey == prevDate) {
                        viewText = 'Yesterday';
                    }
                    appendHtml += '<div id="' + divId + '" class="chat-datewise">' + viewText + '</div>';
                    $.each(msgDate, function(index, msgObj) {
                        appendHtml += writeHtmlBlock(msgObj);
                    });
                });

                $('#user-chat-content_' + currentSel).prepend(appendHtml);
                chatScroll();
            }
            //console.log(msgDateArr);
        });
        
        $('#sendChat').off('click');
        $('#sendChat').on('click',function(e){
            e.preventDefault();
            var chatMsg = $('#chatMsg').val();
            var seekerId = $('#seekerId').val();
            console.log(seekerId);
            var data = {fromId:fromId, toId:seekerId, message:chatMsg, messageFrom:1};
            if(chatMsg!=''){
                socket.emit('sendMessage', data, function(msgObj){
                    console.log(msgObj);
                    $('.modal .close').click();
                });
            }
        });
        
        socket.removeAllListener('receiveMessage');
        socket.on('receiveMessage', function(data) {

            console.log(data);
            var appendHtml = writeHtmlBlock(data);
            var appendToId = data.toId;
            if (data.toId == fromId) {
                appendToId = data.fromId;
            }
            console.log(appendToId);
            var activeLi = $('#li_' + appendToId).hasClass('active');
            $('#user-chat-content_' + appendToId).append(appendHtml);
            if (activeLi) {
                chatScroll();
            } else {
                var badgeObj = $('#li_' + appendToId).find('.badge');
                var badgeCount = parseInt(badgeObj.html(), 10);
                badgeObj.removeClass('hide');
                badgeObj.html(badgeCount + 1);
            }
        });


        //$('.unblock-con').hide();
        $('.chat-action .btn').on('click', function() {
            var obj = $(this).closest('.tab-pane');
            $(this).attr('disabled', 'disabled');
            $(this).parents('ul').addClass('action-disabled');

            obj.find('.unblock-con').show();

            obj.find(".chatScroller").mCustomScrollbar("disable");
            obj.find(".comment").attr("disabled", "disabled");
            obj.find(".chatSend").attr("disabled", "disabled");
            obj.find('.chatSend').addClass('action-disabled');
            blockUnblockSeeker(1);
        });

        $('.chat-unblock').on('click', function() {
            var obj = $(this).closest('.tab-pane');
            console.log($(obj).find('.unblock-con'));
            obj.find('.unblock-con').eq(0).hide();
            obj.find(".chatScroller").mCustomScrollbar("update");
            obj.find(".comment").removeAttr("disabled");
            obj.find(".chatSend").removeAttr("disabled");
            obj.find(".chat-action .btn").removeAttr("disabled");

            obj.find('.chat-action ul.actions').removeClass('action-disabled');
            obj.find('.chatSend').removeClass('action-disabled');
            blockUnblockSeeker(0);
        });
    });
});
