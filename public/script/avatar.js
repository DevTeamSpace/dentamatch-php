jQuery.event.props.push('dataTransfer');
var filesUploaded = inProcessFiles = 0;
// IIFE to prevent globals
(function () {
    var s;
    function avatarObject(element) {
        var ele = element
        this.settings = {
            fileInput: element
        },
        this.init = function () {
            s = this.settings;
            this.bindUIActions();
            return ele
        },
                this.bindUIActions = function () {
                    var list = this;
                    var timer;

                    $(document).delegate(s.fileInput, 'change', function (event) {
                        event.preventDefault();
                        list.handleDrop(event.target.files);
                    });
                },
                this.showDroppableArea = function () {
                    s.bod.addClass("droppable");
                },
                this.hideDroppableArea = function () {
                    s.bod.removeClass("droppable");
                },
                this.handleDrop = function (files) {
                    var list = this;
                    filesUploaded = filesUploaded+files.length;
                    // Multiple files can be dropped. Lets only deal with the "first" one.
                    for (var l = 0; l < files.length; l++) {
                        var file = files[l];
                        if (parseInt(file.size) <= 5242880 * 5) {
                            if (file.type.match('image.*')) {
                                uploadImage(file);
//                                list.resizeImage(file, function (data) {
//                                    list.placeImage(file, data, ele);
//                                });

                            } else {

                                //alert("That file wasn't an image.");

                            }
                        }else{
                            $('#loaderMsg').html("<p class='white text-center'>Image size exceeded the limit 25 Mb.</p>");
                            $('#imgUploadMsg').html("<p class='white text-center'>Image size exceeded the limit 25 Mb.</p>");
                        }
                    }

                },
                this.resizeImage = function (file, callback) {
                    var list = this;
                    var fileTracker = new FileReader;
                    fileTracker.onload = function (event) {
                        var data = event.target.result;
                        var options = {
                            canvas: true
                        };

                        loadImage.parseMetaData(file, function (data) {
                            var size = parseFloat(file.size / 1024).toFixed(2);
                            if ((file.type.match('image/png') || file.type.match('image/jpeg') || file.type.match('image/jpg')) && size <= 10240) {
                                // Get the correct orientation setting from the EXIF Data
                                if (data.exif) {
                                    options.orientation = data.exif.get('Orientation');
                                }
                                // Load the image from disk and inject it into the DOM with the correct orientation
                                loadImage(
                                        file,
                                        function (canvas) {
                                            var imgDataURL = canvas.toDataURL("image/jpeg");
                                            list.placeImage(file, imgDataURL, ele);
                                        },
                                        options
                                        );
                            }
                        });
                    }
                    fileTracker.readAsDataURL(file);

                    fileTracker.onabort = function () {
                        // alert("The upload was aborted.");
                    }
                    fileTracker.onerror = function () {
                        //alert("An error occured while reading the file.");
                    }

                },
                this.placeImage = function (file, data, ele) {
                    console.log(file);
                    uploadImage(file);
                    var imgCtn = '<div class="col-xs-6">' +
                            '<div class="item-image mr-b10">' +
                            '<span class="glyphicon glyphicon-remove upload-close"></span>' +
                            '<img alt="" src="' + data + '">' +
                            '</div>' +
                            '</div>';
                    //$(ele).parents("#galleryUpload").find("#upload-preview").append(imgCtn);

                }
        function uploadImage(file) {
            $('#loaderGallery').removeClass('hidden');
            $('#loaderMsg').html(inProcessFiles+' uploaded out of '+filesUploaded+'</br>');
            var eventId = $('#eventId').val();
            var enhancedRequestId = $('#enhancedRequestId').val();
            var formData = new FormData();
            formData.append('fileToUpload', file);
            formData.append('eventId', eventId);
            formData.append('enhancedRequestId', enhancedRequestId);
            $.ajax({
                type: 'POST',
                url: public_path + 'event/enhanced/image',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                error: function (response) {
                    $('#loaderMsg').prepend("<p class='white text-center'>Error in uploading image</p>");
                    if(filesUploaded==inProcessFiles){
                            $('#imgUploadMsg').html("<p class='white text-center'>Error in uploading image</p>");
                            $('#loaderGallery').addClass('hidden');
                            filesUploaded=inProcessFiles=0;
                    }
                },
                success: function (response) {
                    var html = '';
                    if (response.success == '1') {
                        inProcessFiles++;
                        $('#loaderMsg').html(inProcessFiles+' uploaded out of '+filesUploaded+'</br>');
                        $('#imgUploadMsg').html(inProcessFiles+' uploaded out of '+filesUploaded);
                        html += '<div class="col-xs-6" id="' + response.result['eventImageId'] + '"><div class="item-image mr-b10">';
                        html += '<span class="glyphicon glyphicon-remove upload-close" data-eventimageid="' + response.result['eventImageId'] + '"></span>';
                        html += '<img src="' + response.result['eventImagethumbUrl'] + '" class="img-responsive">';
                        html += '</div></div>'
                        $('#upload-preview').prepend(html);
                        if(filesUploaded==inProcessFiles){
                            $('#imgUploadMsg').html('');
                            $('#loaderGallery').addClass('hidden');
                            filesUploaded=inProcessFiles=0;
                            window.location.reload();
                        }
                    }
                }
            });
        }
    }
    $(document).on('click','#enhancementCompleted',function(e){
        $('#imgUploadMsg').html('');
        e.preventDefault();
        e.stopImmediatePropagation();
        var enhancedRequestId = $('#enhancedRequestId').val();
        console.log(enhancedRequestId);
        if(enhancedRequestId){
            $('#enhancementCompleted').closest('form').hide();
            $.ajax({
                type:'POST',
                url: public_path+'event/enhancedImage/completed',
                data : {enhancedRequestId:enhancedRequestId},
                success:function(response){
                    if(response.success=='1'){
                        $('#imgUploadMsg').html('Uploaded successfully');
                        window.location.reload();
                    }else{
                        $('#enhancementCompleted').closest('form').show();
                        $('#imgUploadMsg').html(response.message);//.addClass('red');
                    }
                }
            });
        }
    });
    $(document).on('click','.upload-close',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var eventImageId = $(this).data('eventimageid');
        console.log(eventImageId);
        if(eventImageId){
            $.ajax({
                type:'DELETE',
                url: public_path+'event/enhancedImage/delete/'+eventImageId,
                error:function(response){
                    console.log(response);
                },
                success:function(response){
                    if(response.success=='1'){
                        $('#'+eventImageId).remove();
                    }
                }
            });
        }
    });
    var packageInfo = new avatarObject("#uploader");
    packageInfo.init();
})();