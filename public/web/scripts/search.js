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
            var distance    =   $('#range_slider').val();
            var url         =   window.location.href;
            var mainUrl     =   url.split("?")[0]; 
            url = mainUrl+'?distance='+distance+'&avail_all='+availableAll;
            getArticles(url);
            window.history.pushState("", "", url);
        });

        /*-----------range slider--------*/
        $("#range_slider").slider({ 
            min: 1, 
            max: maxSliderRange, 
            value: $('#slider_val').val(), 
            tooltip_position:'bottom',
            formatter: function(value) {
                return   value + ' miles ' ;
            },
        });

        $("#range_slider").slider().on('slideStop', function(ev){
            $('.loader-box').show();
            var distance    =   $('#range_slider').val();
            var url         =   window.location.href;
            var mainUrl     =   url.split("?")[0]; 
            url = mainUrl+'?distance='+distance;

            if($('#avail_all').val()==1){
                url += '&avail_all=1';
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
            }).fail(function () {

            });
        }
    });