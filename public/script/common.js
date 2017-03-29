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
    
    $('#jobtitle_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobtitle/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'jobtitle_name', name: 'jobtitle_name',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#officetype_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'officetype/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'officetype_name', name: 'officetype_name',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#certificate_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'certificate/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'certificate_name', name: 'certificate_name',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#skill_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'skill/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'skill_name', name: 'skill_name',searchable:true},
            {data: 'parent_skill', name: 'parent_skill',searchable:false},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#school_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'school/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'school_name', name: 'school_name',searchable:true},
            {data: 'parent_school', name: 'parent_school',searchable:false},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#jonseeker_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobseeker/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:true},
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#recruiter_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'recruiter/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:true},
            {data: 'office_name', name: 'recruiter_profiles.office_name',searchable:true},
            {data: 'active', name: 'active',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#job_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'report/list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'office_name', name: 'recruiter_profiles.office_name',searchable:true},
            {data: 'address', name: 'address',searchable:false},
            {data: 'jobtitle_name', name: 'job_titles.jobtitle_name',searchable:false},
            {data: 'jobtype', name: 'jobtype',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
    });
    
    $('#seeker_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'report/seekerlist/'+$('#jobId').val(),
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            {data: 'applied_status', name: 'job_lists.applied_status',searchable:false},
        ]
    });
    
    $('#cancel_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'report/cancel',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            {data: 'cancelno', name: 'cancelno',searchable:false},
        ]
    });
    
    $('#response_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'report/response',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'office_name', name: 'recruiter_profiles.office_name',searchable:false},
            {data: 'jobtitle_name', name: 'jobtitle_name',searchable:false},
            {data: 'jobtype', name: 'jobtype',searchable:false},
            {data: 'invited', name: 'invited',searchable:false},
            {data: 'applied', name: 'applied',searchable:false},
            {data: 'sortlisted', name: 'sortlisted',searchable:false},
            {data: 'hired', name: 'hired',searchable:false},
            {data: 'rejected', name: 'rejected',searchable:false},
            {data: 'cancelled', name: 'cancelled',searchable:false},
        ]
    });
    
    $('#jobbylocation_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'report/location',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'city', name: 'city',searchable:false},
            {data: 'searchcount', name: 'searchcount',searchable:false},
        ]
    });
    
    $('#jobseeker_verification').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobseeker/verification-list',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            
            {data: 'license_number', name: 'license_number',searchable:false},
            {data: 'is_job_seeker_verified', name: 'is_job_seeker_verified',searchable:false},
            {data: 'action', name: 'action',searchable:false}
        ]
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
            var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            if (filter.test(adminEmail)) {
            $.ajax({
                url:public_path+'../api/admin/forgot-password',
                type:'POST',
                dataType: 'json',
                data:{email:adminEmail},
                success:function(response){
                    if(response.status == 1){
                        $('#emailForgetpassword').val('');
                        alert(response.message);
                    }else{
                        alert(response.message);
                    }
                    window.location.reload();
                }
            });
        }else{
            alert('Please provide a valid email address');
        }
    }else{
        alert('Please provide a valid email address');
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
        data = {
        "_token": "{{ csrf_token() }}",
        };
        $.get(obj.href, function(data) {
            //do refresh
            window.location.reload();
        });
        return false;
}
