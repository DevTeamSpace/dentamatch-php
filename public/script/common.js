$(document).ajaxError(function (e, request, settings, exception) {
        if (request.status == 401) {
            var redirectTo = confirm("Session expired, redirect to login");
            if (redirectTo == true) {
                //var url = '@Url.Action("LogOn", "Account", new {area = "", msg = "forbidden", returnUrl = HttpContext.Current.Request.RawUrl})' + window.location.hash;
                window.location = '/cms';
                return;
            }
        }
    });
$(function () {
    $('#photographer_list').DataTable({
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        processing: true,
        serverSide: true,
        responsive: true,
        "autoWidth": false,
        ajax: public_path+'user/listPhotographer',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'firstName', name: 'first_name',searchable:true},
            {data: 'lastName', name: 'last_name',searchable:true},
            {data: 'email', name: 'email',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'rejected', name: 'rejected',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#consumer_list').DataTable({
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        processing: true,
        serverSide: true,
        responsive: true,
        "autoWidth": false,
        ajax: public_path+'user/listConsumer',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'firstName', name: 'first_name',searchable:true},
            {data: 'lastName', name: 'last_name',searchable:true},
            {data: 'email', name: 'email',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#designer_list').DataTable({
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        processing: true,
        serverSide: true,
        responsive: true,
        "autoWidth": false,
        ajax: public_path+'user/listDesigner',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'firstName', name: 'first_name',searchable:true},
            {data: 'lastName', name: 'last_name',searchable:true},
            {data: 'email', name: 'email',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#eventType_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'eventType/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'eventTypeName', name: 'eventTypeName',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#location_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'location/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'zipcode', name: 'zipcode',searchable:true},
            {data: 'freeTrialPeriod', name: 'free_trial_period',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#affiliation_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'affiliation/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'affiliation_name', name: 'affiliation_name',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#coupon_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'coupon/list',
        ordering:false,
        //bFilter: true,
        columns: [
            {data: 'couponName', name: 'couponName',searchable :true},
            {data: 'couponCode', name: 'couponCode',searchable :false},
            {data: 'couponValue', name: 'couponValue',searchable :false},
            {data: 'discountType', name: 'discountType',searchable :false},
            {data: 'startDate', name: 'startDate',searchable :false},
            {data: 'endDate', name: 'endDate',searchable :false},
            {data: 'active', name: 'active',searchable :false},
            {data: 'action', name: 'action',searchable :false}
        ],
        initComplete: searchInit
    });
    
    function searchInit() {
        this.api().columns().every(function () {
            var column = this;
            var input = document.createElement("input");
            $(input).appendTo($(column.footer()).empty())
            .on('change', function () {
                column.search($(this).val()).draw();
            });
        });
    }
    
    $('#group_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: public_path+'group/list',
        ordering:false,
        paging:false,
        bFilter: false,
        columns: [
            {data: 'groupName', name: 'group_name'},
            {data: 'termConditions', name: 'termConditions'}
        ]

    });
    
    $('#enhanced_photo_request_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        autoWidth: false,
        ajax: public_path+'event/enhancementRequest',
        ordering:false,
        scrollX: true,
        //paging:false,
        //bFilter: false,
        columns: [
            {data: 'userName', name: 'userName',searchable:false},
            {data: 'eventType', name: 'eventType',searchable:false},
            {data: 'paidPhoto', name: 'paidPhoto',searchable:false},
            {data: 'freePhoto', name: 'freePhoto',searchable:false},
            {data: 'enhancedDesignerName', name: 'enhancedDesignerName',searchable:false},
            {data: 'submittedAt', name: 'submittedAt',searchable:false},
            {data: 'assignedAt', name: 'assignedAt',searchable:false},
            {data: 'completedAt', name: 'completedAt',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    var eventId = $('#eventId').val();
    $('#event_enhancement_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        autoWidth: false,
        ajax: public_path+'event/eventEnhancements/'+eventId,
        ordering:false,
        scrollX: true,
        columns: [
            {data: 'paidPhotoCount', name: 'paidPhoto',searchable:false},
            {data: 'freePhotoCount', name: 'freePhoto',searchable:false},
            {data: 'perPhotoCost', name: 'perphotoCost',searchable:false},
            {data: 'enhancementStatus', name: 'enhancement_status',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#event_extension_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        autoWidth: false,
        ajax: public_path+'event/eventExtensions/'+eventId,
        ordering:false,
        scrollX: true,
        columns: [
            {data: 'overTime', name: 'overtime',searchable:false},
            {data: 'overtimeCost', name: 'overtime_cost',searchable:false},
            {data: 'actualCost', name: 'actual_cost',searchable:false},
            {data: 'isApproved', name: 'is_approved',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#events_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        autoWidth: false,
        ajax: public_path+'event/list',
        ordering:false,
        scrollX: true,
        //paging:false,
        //bFilter: false,
        columns: [
            {data: 'consumerName', name: 'consumerName',searchable:true},
            {data: 'photographerName', name: 'photographerName',searchable:true},
            {data: 'eventType', name: 'event_name',searchable:true},
            {data: 'status', name: 'status',searchable:true},
            {data: 'startDate', name: 'startDate',searchable:false},
            {data: 'endDate', name: 'endDate',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#designer_enhanced_photo_request_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        autoWidth: false,
        ajax: public_path+'event/enhancementRequest',
        ordering:false,
        scrollX: true,
        //paging:false,
        //bFilter: false,
        columns: [
            {data: 'userName', name: 'userName',searchable:false},
            {data: 'eventType', name: 'eventType',searchable:false},
            {data: 'photos', name: 'photos',searchable:false},
//            {data: 'createdAt', name: 'createdAt',searchable:false},
            {data: 'submittedAt', name: 'submittedAt',searchable:false},
            {data: 'assignedAt', name: 'assignedAt',searchable:false},
            {data: 'completedAt', name: 'completedAt',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#app_feedback_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: public_path+'appFeedback/list',
        ordering:false,
        //paging:false,
        //bFilter: false,
        columns: [
            {data: 'userName', name: 'userName',searchable:true},
            {data: 'userType', name: 'userType',searchable:false},
            {data: 'message', name: 'message',searchable:false},
            {data: 'createdAt', name: 'createdAt',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#chat_message_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: public_path+'chatMessage/list',
        ordering:false,
        //paging:false,
        //bFilter: false,
        columns: [
            {data: 'fromUserName', name: 'fromUserName',searchable:true},
            {data: 'toUserName', name: 'toUserName',searchable:true},
            {data: 'message', name: 'message',searchable:false},
            {data: 'createdAt', name: 'createdAt',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#rating_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        autoWidth: false,
        ajax: public_path+'rating/list',
        ordering:false,
        scrollX: true,
        //paging:false,
        //bFilter: false,
        columns: [
            {data: 'fromUserName', name: 'fromUserName',searchable:true},
            {data: 'toUserName', name: 'toUserName',searchable:true},
            {data: 'eventFolderUrl', name: 'eventFolderUrl',searchable:false},
            {data: 'ratingScore', name: 'ratingScore',searchable:true},
            //{data: 'feedback', name: 'feedback',searchable:false},
            {data: 'createdAt', name: 'createdAt',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]

    });
    
    $('#expertise_request_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: public_path+'expertiseRequest/list',
        ordering:false,
        paging:true,
        //bFilter: false,
        columns: [
            {data: 'photographerName', name: 'photographerName',searchable:true},
            {data: 'currentLevel', name: 'currentLevel',searchable:false},
            {data: 'proposedLevel', name: 'proposedLevel',searchable:false},
            {data: 'createdAt', name: 'createdAt',searchable:false},
            {data: 'actionStatus', name: 'actionStatus',searchable:false}
        ]
    });
    
    $('#checkr_form_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'checkrForm/list',
        ordering:false,
        paging:true,
        //bFilter: false,
        columns: [
            {data: 'photographerName', name: 'photographerName',searchable:true},
            {data: 'pdfLink', name: 'pdfLink',searchable:false},
            {data: 'createdAt', name: 'createdAt',searchable:false},
//            {data: 'actionStatus', name: 'actionStatus',searchable:false}
        ]
    });
    
    $('#appMessage_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: public_path+'notify/list',
        ordering:false,
        //paging:false,
        //bFilter: false,
        columns: [
            {data: 'messageTo', name: 'messageTo',searchable:true},
            {data: 'message', name: 'message',searchable:false},
            {data: 'messageType', name: 'messageType',searchable:false},
            {data: 'messageSent', name: 'messageSent',searchable:false},
            {data: 'createdAt', name: 'createdAt',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    $('#payment_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: public_path+'payments/list',
        ordering:false,
        //paging:false,
        bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:false},
            {data: 'amount', name: 'amount',searchable:false},
            {data: 'paypalTransactionId', name: 'paypalTransactionId',searchable:false},
            {data: 'transactions', name: 'transactions',searchable:false},
            {data: 'updatedAt', name: 'updatedAt',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('.transactions').DataTable({
        ordering:false,
        paging:false,
        bFilter: false,
        dom: 'Bfrtip',
        bInfo : false
    });
    
    var tlist = $('#transaction_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: public_path+'payments/transactionList/0',
        ordering:false,
        //paging:false,
        bFilter: false,
        bStateSave: true,
        columns: [
            {data: 'transactionType', name: 'transactionType',searchable:false},
            {data: 'estimatedCost', name: 'estimatedCost',searchable:false},
            {data: 'actualCost', name: 'actualCost',searchable:false},
            {data: 'appownerCost', name: 'appownerCost',searchable:false},
            {data: 'photographerCost', name: 'photographerCost',searchable:false},
            {data: 'startDate', name: 'startDate',searchable:false},
            {data: 'transferStatus', name: 'transferStatus',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#listType').change(function(){
        var listType = $('#listType').val();
        tlist.ajax.url(public_path+'payments/transactionList/'+listType).load();
    });
    
    //$.fn.datepicker.defaults.format = "yyyy-mm-dd";
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    console.log(now);
    var start_date = $('#start_date').datepicker({
        format:"yyyy-mm-dd",
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    }).on('click', function(ev) {
        end_date.hide();
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() >= end_date.date.valueOf()) {
            var newDate = new Date(ev.date);
            newDate.setDate(newDate.getDate());
            end_date.setValue(newDate);
        }else{
            end_date.setValue(end_date.date);
        }
        start_date.hide();
        $('#end_date')[0].focus();
    }).data('datepicker');
    var end_date = $('#end_date').datepicker({
        format:"yyyy-mm-dd",
        onRender: function(date) {
            return date.valueOf() < start_date.date.valueOf() ? 'disabled' : '';
        }
    }).on('click', function(ev) {
        start_date.hide();
    }).on('changeDate', function(ev) {
      end_date.hide();
    }).data('datepicker');
    
    var startDate = $('#startDate').datepicker({
        format:"yyyy-mm-dd",
        onRender: function(date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('click', function(ev) {
        endDate.hide();
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() >= endDate.date.valueOf()) {
            var newDate = new Date(ev.date);
            newDate.setDate(newDate.getDate());
            endDate.setValue(newDate);
        }else{
            endDate.setValue(endDate.date);
        }
        startDate.hide();
        $('#endDate')[0].focus();
    }).data('datepicker');
    var endDate = $('#endDate').datepicker({
        format:"yyyy-mm-dd",
        onRender: function(date) {
            return (date.valueOf() < startDate.date.valueOf() || 
                    date.valueOf() > now.valueOf())?'disabled':'';
        }
    }).on('click', function(ev) {
        startDate.hide();
    }).on('changeDate', function(ev) {
      endDate.hide();
    }).data('datepicker');
    
    $('#forgotLink, #loginLink').click(function(){
        $('#ForgetError').html('').addClass('hidden');
        $('#loginDiv').toggle();
        $('#forgotDiv').toggle();
    });
    $('#resetPassword').click(function(){
        var adminEmail = $('#emailForgetpassword').val();
        if(adminEmail!=''){
            $.ajax({
                url:public_path+'../api/user/forgot-password',
                type:'PUT',
                data:{email:adminEmail},
                success:function(response){
                    if(response.Success=="1"){
                        $('#emailForgetpassword').val('');
                        if(response.Result['redirect']){
                            window.setTimeout( function(){
                                window.location = response.Result['redirect'];
                            }, 100 );
                        }
                    }else{
                        $('#ForgetError').html(response.Message).removeClass('hidden');
                    }
                }
            });
        }
    });
    $('#configForm').submit(function(){
        var error = false;
        $('.configData').each(function(index,value){
            if($(value).val()=='')
                error = true;
        });
        if(error == true){
            $('.alert-danger').html('All fields are mandatory').toggle();
            return false;
        }
    });
    $('#previewButton').click(function(){
        var previewLink = $(this).data('href');
        var transIds = $('#transactionIds').val();
        console.log(previewLink);
        if(transIds!=''){
            $('#previewForm').submit();
        }
    });
    $('input[type="checkbox"]').click(function(){
        console.log($(this).prop('checked'));
        var chk = $(this).prop('checked');
        var itemVal = $(this).val();
        var transactionIds = $('#transactionIds').val().split(',');
        console.log(transactionIds.length);
        if(transactionIds=='')
            transactionIds=[];
        
        console.log(transactionIds);
        
        if(chk==false)
            transactionIds.splice( $.inArray(itemVal, transactionIds), 1 );
        else
            transactionIds.push(itemVal);
        $('#transactionIds').val(transactionIds);
        console.log(transactionIds);
        
    });
    $('#reject_reason').click(function(){
        $('#msgSpan').removeClass('red').html('');
        var rejectReason = $('#rejectReasonText').val();
        var userId = $('#userId').val();
        if(rejectReason!=''){
            $.ajax({
                url:public_path+'user/reject',
                type:'POST',
                data:{userId:userId,rejectReason:rejectReason},
                success:function(response){
                    $('#msgSpan').removeClass('hidden');
                    if(response.success=="1"){
                        $('#rejectReasonText').val('');
                        $('#msgSpan').addClass('green success').html(response.msg);
                        $('#myModal').modal('hide');
                        window.location.reload();
                    }else{
                        $('#msgSpan').addClass('red error').html(response.msg);
                    }
                }
            });
        }else{
            $('#msgSpan').removeClass('hidden');
            $('#msgSpan').addClass('red error').html('Reason required');
        }
    });
    $('#deactivate_reason').click(function(){
        $('#msgSpanDeactivate').removeClass('red').html('');
        var rejectReason = $('#deactivateReasonText').val();
        var userId = $('#userId').val();
        if(rejectReason!=''){
            $.ajax({
                url:public_path+'user/deactivate',
                type:'POST',
                data:{userId:userId,rejectReason:rejectReason},
                success:function(response){
                    $('#msgSpanDeactivate').removeClass('hidden');
                    if(response.success=="1"){
                        $('#deactivateReasonText').val('');
                        $('#msgSpanDeactivate').addClass('green success').html(response.msg);
                        $('#myModalDeactivate').modal('hide');
                        window.location.reload();
                    }else{
                        $('#msgSpanDeactivate').addClass('red error').html(response.msg);
                    }
                }
            });
        }else{
            $('#msgSpanDeactivate').removeClass('hidden');
            $('#msgSpanDeactivate').addClass('red error').html('Reason required');
        }
    });
    $(".modal").on("hidden.bs.modal", function() {
        $('#rejectReasonText').val('');
        $('#msgSpan').removeClass('red').html('');
        //$(".modal-body1").html("Where did he go?!?!?!");
    });
});
function deleteRecord(obj) {
        console.log(obj);
        $.post(obj.href, "_method=delete", function(data) {
            //do refresh
            window.location.reload();
        });
        return false;
}