$('.ddlCars').multiselect({
    numberDisplayed: 3,
});
$(document).ready(function() {


    $("#fade-quote-carousel").carousel({
        interval: false,
        wrap: false
    });
    var checkitem = function() {
        var $this;
        $this = $("#fade-quote-carousel");
        if ($("#fade-quote-carousel .carousel-inner .item:first").hasClass("active")) {
            $this.children(".left").hide();
            $('.close').text('Skip')
            $this.children(".right").show();
        } else if ($("#fade-quote-carousel .carousel-inner .item:last").hasClass("active")) {
            $this.children(".right").hide();
            $('.close').text('Done')
            $this.children(".left").show();
        } else {
            $('.close').text('Skip')
            $this.children(".carousel-control").show();
        }
    };

    checkitem();

    $("#fade-quote-carousel").on("slid.bs.carousel", "", checkitem);


    /*code for linked picker - curtainup and curtaindown*/
    var $startTime1 = $('.datetimepicker1');
    var $endTime1 = $('.datetimepicker2');

    $startTime1.datetimepicker({
        format: 'hh:mm A',
        'allowInputToggle': true,


        minDate: moment().startOf('day'),
        maxDate: moment().endOf('day')
    });

    $endTime1.datetimepicker({
        format: 'hh:mm A',
        'allowInputToggle': true,


        minDate: moment().startOf('day'),

        maxDate: moment().endOf('day')
    });



    /*End of timepicker*/


    $('.datetimepicker1').on("dp.change", function(e) {

        var date = $(this).data('date');

        $(this).parents(".row").find('.datetimepicker2').data('DateTimePicker').minDate(e.date);
        console.log(date);
    });
    $('.datetimepicker2').on("dp.change", function(e) {
        var date = $(this).data('date');
        $(this).parents(".row").find('.datetimepicker1').data('DateTimePicker').maxDate(e.date);
        console.log(date);
    });



});

var placeSearch, autocomplete, autocomplete1, autocomplete2, officeName;
var componentForm = {
    postal_code: 'short_name'
};

var autocomplete = {};
var autocompletesWraps = ['autocomplete', 'autocomplete1', 'autocomplete2'];

function initializeMap() {

    $.each(autocompletesWraps, function(index, name) {
        if ($('#' + name).length == 0) {
            return;
        }

        autocomplete[name] = new google.maps.places.SearchBox($('#' + name)[0], { types: ['geocode'] });
        autocomplete[name].addListener('places_changed', function() {
            var allPlace = autocomplete[name].getPlaces();
            console.log(name);
            var indexField = name.split('autocomplete')[1];
            allPlace.forEach(function(place) {

                $('#postal_code' + indexField).val('');
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        document.getElementById(addressType + indexField).value = val;
                    }
                }

                document.getElementById('full_address' + indexField).value = place.formatted_address;
                document.getElementById('lat' + indexField).value = place.geometry.location.lat();
                document.getElementById('lng' + indexField).value = place.geometry.location.lng();
                $('#' + name)[0].value = place.formatted_address;

                checkLocation($('#postal_code' + indexField).val(), indexField);
            });
        });
    });
}

$(window).load(function() {
    initializeMap();
});


function getOfficeName() {
    officeName = new google.maps.places.SearchBox(
        (document.getElementById('officeName')), { types: ['geocode'] });
    officeName.addListener('places_changed', fillOfficeAddress);
}

function fillOfficeAddress() {
    var addy = $('#officeName').val();
    var offName = addy.substr(0, addy.indexOf(','));
    document.getElementById('officeName').value = offName;
}
