function createProfile() {
    var form_data = $('#createProfileForm').serialize();
    var errorsHtml;
    $.ajax(
            {
                url: '/create-profile',
                type: "POST",
                data: form_data,
                success: function (data) {
                    $("#createProfileButton").prop('disabled', true);
                    errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully.</li></ul></div>';
                    $('#createForm-errors').html(errorsHtml);
                    $('div.alert').delay(1000).slideUp(300);

                },
                error: function (data) {
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                        $.each(errors, function (key, value) {
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

    $('form').parsley().destroy();
    $('form').parsley();

}

function officeDetail() {
    var form_data = $('#officeDetailForm').serialize();
    var errorsHtml;
    $.ajax(
            {
                url: '/office-details',
                type: "POST",
                data: form_data,
                success: function (data) {
                    $("#officeDetailButton").remove();
                    $("#removeButton").append('<a id="countinueLink" href="/subscription-detail" class="btn btn-primary pd-l-40 pd-r-40">Continue</a>')
                    errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully.</li></ul></div>';
                    if (data == 1) {
                        errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully. But you will be not able to put job on this address.</li></ul></div>';
                    }
                    $('#officeDetail-errors').html(errorsHtml);
                    $('div.alert').delay(1000).slideUp(300);
                },
                error: function (data) {
                    console.log(data);
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                        $.each(errors, function (key, value) {
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
    $.ajax(
            {
                url: '/office-details',
                type: "POST",
                data: form_data,
                success: function (data) {
                    $("#officeDetailButton1").remove();
                    $("#countinueLink").remove();
                    $("#removeButton1").append('<a id="countinueLink1" href="/subscription-detail" class="btn btn-primary pd-l-40 pd-r-40">Continue</a>')
                    errorsHtml = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><ul><li>Saved Successfully.</li></ul></div>';
                    $('#officeDetail-errors1').html(errorsHtml);
                    $('div.alert').delay(1000).slideUp(300);
                },
                error: function (data) {
                    console.log(data);
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                        $.each(errors, function (key, value) {
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
    $.ajax(
            {
                url: '/office-details',
                type: "POST",
                data: form_data,
                success: function (data) {
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
                error: function (data) {
                    console.log(data);
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><ul>';
                        $.each(errors, function (key, value) {
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
    $.ajax(
            {
                url: '/get-location/' + zip,
                type: "GET",
                success: function (data) {
                    console.log(data);
                    if (data == 0) {
                        msg = 'Please enter a valid address.';
                        $('#location-msg' + indexField).html(msg);
                    } else if (data == 2) {
                        msg = 'Job cannot be currently created for this location. We will soon be available in your area.';
                        $('#location-msg' + indexField).html(msg);
                    }
                },
                error: function (data) {
                    msg = 'Please enter a valid address.';
                    $('#location-msg' + indexField).html(msg);
                }
            });


}
