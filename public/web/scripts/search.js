    $(function () {
        $('body').on('click', '.pagination a', function (e) {
            e.preventDefault();

            $('#load a').css('color', '#dfecf6');
            $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');
            getArticles(url);
            window.history.pushState("", "", url);
        });

        $('#availAllBtn').click(function(){
            var availableAll = ($('#avail_all').val()==1 ? 0 : 1);
            $('.loader-box').show();
            var preferredLocationId = $('#preferredLocationIdSearch').val();
            var url         =   window.location.href;
            var mainUrl     =   url.split("?")[0]; 
            if(preferredLocationId!="") {
                url = mainUrl+'?preferredLocationId='+preferredLocationId+'&avail_all='+availableAll;
            } else {
                url = mainUrl+'?avail_all='+availableAll;
            }
            
            getArticles(url);
            window.history.pushState("", "", url);
        });
        
        $('#preferredLocationIdSearch').on('change', function() {
            $('.loader-box').show();
            var preferredLocationId = $('#preferredLocationIdSearch').val();
            var url         =   window.location.href;
            var mainUrl     =   url.split("?")[0]; 
            if(preferredLocationId!="") {
                url = mainUrl+'?preferredLocationId='+preferredLocationId;
            } else {
                url = mainUrl;
            }

            if($('#avail_all').val()==1 && preferredLocationId!=""){
                url += '&avail_all=1';
            } else if($('#avail_all').val()==1) {
                url += '?avail_all=1';
            }
            getArticles(url);
            window.history.pushState("", "", url);
        });
        /*-----------range slider--------*/


        function getArticles(url) {
            $.ajax({
                url: url
            }).done(function (data) {
                $('#ajaxData').html(data);
                $('.loader-box').hide();
                var totalResult = $('#resultCount').val();
                $('#resultFound').html(totalResult + ' Results Found');
                
            }).fail(function () {

            }).complete(function(){
            });
        }
    });