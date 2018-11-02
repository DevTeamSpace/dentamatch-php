function createProfile() {
    console.log('In');
    var form_data = $('#createProfileForm').serialize();
    var errorsHtml;
    $.ajax({
        url: 'create-profile',
        type: "POST",
        data: form_data,
        success: function(data) {
            $("#createProfileButton").remove();
            errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully.</li></ul></div>';
            $('#createForm-errors').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
            setTimeout(
                function () {
                    window.location.href = 'subscription-detail';
                }, 300
            );
            
            //$("#removeButton").append('<a id="countinueLink" href="/subscription-detail" class="btn btn-primary pd-l-40 pd-r-40">Continue</a>');
        },
        error: function(data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
            } else if (data.status === 1) {
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>' + data.msg + '</div>';
            }
            $('#createForm-errors').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);

        }
    });


    $('form').parsley();

}

function officeDetail() {
    var form_data = $('#officeDetailForm').serialize();
    var errorsHtml;
    $.ajax({
        url: '/office-details',
        type: "POST",
        data: form_data,
        success: function(response) {
            $('#officeDetailForm').find('.deleteCard').attr('data-officeId',response.data.id);
            $("#officeDetailButton").remove();
            $("#removeButton").append('<a id="countinueLink" href="/subscription-detail" class="btn btn-primary pd-l-40 pd-r-40">Continue</a>')
            errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully.</li></ul></div>';
            if (response == 1) {
                errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully. But you will be not able to put job on this address.</li></ul></div>';
            }
            $('#officeDetail-errors').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        },
        error: function(data) {
            console.log(data);
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
            } else if (data.status === 1) {
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>' + data.msg + '</div>';
            }
            $('#officeDetail-errors').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        }
    });
    $('form').parsley().destroy();
    $('form').parsley();

}

function officeDetail1() {
    var form_data = $('#officeDetailForm1').serialize();
    var errorsHtml;
    $.ajax({
        url: '/office-details',
        type: "POST",
        data: form_data,
        success: function(response) {
            $('#officeDetailForm1').find('.deleteCard').attr('data-officeId',response.data.id);
            $("#officeDetailButton1").remove();
            $("#countinueLink").remove();
            $("#removeButton1").append('<a id="countinueLink1" href="/subscription-detail" class="btn btn-primary pd-l-40 pd-r-40">Continue</a>')
            errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully.</li></ul></div>';
            $('#officeDetail-errors1').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        },
        error: function(data) {
            console.log(data);
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
            } else if (data.status === 1) {
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>' + data.msg + '</div>';
            }
            $('#officeDetail-errors1').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        }
    });
    $('form').parsley().destroy();
    $('form').parsley();
}

function officeDetail2() {
    var form_data = $('#officeDetailForm2').serialize();
    var errorsHtml;
    $.ajax({
        url: '/office-details',
        type: "POST",
        data: form_data,
        success: function(response) {
            $('#officeDetailForm2').find('.deleteCard').attr('data-officeId',response.data.id);
            $("#officeDetailButton2").remove();
            $("#countinueLink1").remove();
            $("#removeButton2").append('<a id="countinueLink2" href="/subscription-detail" class="btn btn-primary pd-l-40 pd-r-40">Continue</a>')
            errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully.</li></ul></div>';
            if (data == 1) {
                errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully. But you will be not able to put job on tihs address.</li></ul></div>';
            }
            $('#officeDetail-errors2').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        },
        error: function(data) {
            console.log(data);
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
            } else if (data.status === 1) {
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>' + data.msg + '</div>';
            }
            $('#officeDetail-errors2').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        }
    });
    $('form').parsley().destroy();
    $('form').parsley();
}

function checkLocation(zip, indexField) {
    var msg = "";
    $('#location-msg' + indexField).html('');
    if(zip==''){
        msg = 'Please enter a valid address.';
        $('#location-msg' + indexField).html(msg);
    }else{
        $.ajax({
            url: '/get-location/' + zip,
            type: "GET",
            success: function(data) {
                console.log(data);
                if (data == 0) {
                    msg = 'Please enter a valid address.';
                    $('#location-msg' + indexField).html(msg);
                }else if (data == 2) {
                    msg = 'We do not currently support this location, but we hope to be available there soon!';
                    $('#location-msg' + indexField).html(msg);
                } 
            },
            error: function(data) {
               msg = 'Please enter a valid address.';
               $('#location-msg' + indexField).html(msg);
            }
        });
    }
}

function editofficedetail() {
    var form_data = $('#editofficedetailform').serialize();
    var errorsHtml;
    $.ajax({
        url: '/office-details',
        type: "POST",
        data: form_data,
        success: function(data) {
            if (data === 1) {
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>Already job applied on this address.</div>';
                $('#editForm-errors').html(errorsHtml);
            } else {
                location.reload();
            }
        },
        error: function(data) {
            console.log(data);
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
            } else if (data.status === 1) {
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>' + data.msg + '</div>';
            }
            $('#editForm-errors').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        }
    });
}

function addofficedetail() {
    var form_data = $('#addofficedetailform').serialize();
    var errorsHtml;
    $.ajax({
        url: '/office-details',
        type: "POST",
        data: form_data,
        success: function(data) {
            location.reload();
        },
        error: function(data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                $.each(errors, function(key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
            } else if (data.status === 1) {
                errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>' + data.msg + '</div>';
            }
            $('#createForm-errors').html(errorsHtml);
            $('div.alert').delay(1000).slideUp(300);
        }
    });
}

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
