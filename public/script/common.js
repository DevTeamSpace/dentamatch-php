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
            //{data: 'freeTrialPeriod', name: 'free_trial_period',searchable:true},
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.isActive == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'location/'+row.locationId+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
            }}
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
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'affiliation/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
            }}
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
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'jobtitle/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
            }}
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
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'officetype/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
            }}
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
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'certificate/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
            }}
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
            {data: 'parent_skill_name', name: 'parent_skill',searchable:false},
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'skill/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
            }}
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
            {data: 'parent_school_name', name: 'parent_school',searchable:false},
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'school/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
            }}
        ]
    });
    
    var jobseekerList = $('#jobseeker_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobseeker/list',
        //ordering:true,
        order: [[ 5, "desc" ]],
        //bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:true,sortable:false},
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true,sortable:false},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true,sortable:false},
            {data: 'jobtitle_name', name: 'job_titles.jobtitle_name',searchable:true,sortable:false},
            {data: 'preferred_location_name', name: 'preferred_job_locations.preferred_location_name',searchable:true,sortable:false},
            {data: 'registered_on', name: 'created_at',sortable:true,searchable:false},
            {data: 'active', name: 'active',searchable:false,sortable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,sortable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'jobseeker/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;\n\
                        <a href="'+public_path+'recruiter/'+row.id+'/adminResetPassword"  class="btn btn-xs btn-primary">Reset Password</a>&nbsp;\n\
                        <a href="'+public_path+'jobseeker/'+row.id+'/viewdetails"  class="btn btn-xs btn-primary">View Details</a>&nbsp;';
            }}
        ]
    });
    
    var ajaxUrl = jobseekerList.ajax.url();
    function getDataTableUrl(){
        var startDate=$('#startDate').val();
        var endDate=$('#endDate').val();
        console.log(startDate);console.log(endDate);
        return ajaxUrl+'?startDate='+startDate+'&endDate='+endDate;
    }
    $('#search').click( function() {
        console.log(jobseekerList.ajax.url());
        jobseekerList.ajax.url(getDataTableUrl())
        jobseekerList.load(); 
    } );
    
    $('#jobseeker_unverified_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobseeker/listUnverifiedJobseeker',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:true},
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            {data: 'jobtitle_name', name: 'job_titles.jobtitle_name',searchable:true},
            {data: 'preferred_location_name', name: 'preferred_job_locations.preferred_location_name',searchable:true},
            {data: 'license_number', name: 'jobseeker_profiles.license_number',searchable:true},
            {data: 'state', name: 'jobseeker_profiles.state',searchable:true},
            {data: 'created_at', name: 'created_at',searchable:false},
        ]
    });
    
    $('#jobseeker_incomplete_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobseeker/listIncompleteJobseeker',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:true},
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            {data: 'jobtitle_name', name: 'job_titles.jobtitle_name',searchable:true},
            {data: 'preferred_location_name', name: 'preferred_job_locations.preferred_location_name',searchable:true},
            {data: 'license_number', name: 'jobseeker_profiles.license_number',searchable:true},
            {data: 'state', name: 'jobseeker_profiles.state',searchable:true},
            {data: 'created_at', name: 'created_at',searchable:false},
        ]
    });
    
    $('#jobseeker_nonavailable_users_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobseeker/listNonAvailableUsers',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:true},
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            {data: 'jobtitle_name', name: 'job_titles.jobtitle_name',searchable:true},
            {data: 'preferred_location_name', name: 'preferred_job_locations.preferred_location_name',searchable:true},
            {data: 'license_number', name: 'jobseeker_profiles.license_number',searchable:true},
            {data: 'state', name: 'jobseeker_profiles.state',searchable:true},
            {data: 'created_at', name: 'created_at',searchable:false},
        ]
    });
    
    $('#jobseeker_invited_users_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'jobseeker/listInvitedUsers',
        ordering:false,
        //bFilter: false,
        columns: [
            {data: 'email', name: 'email',searchable:true},
            {data: 'first_name', name: 'jobseeker_profiles.first_name',searchable:true},
            {data: 'last_name', name: 'jobseeker_profiles.last_name',searchable:true},
            {data: 'jobtitle_name', name: 'job_titles.jobtitle_name',searchable:true},
            {data: 'preferred_location_name', name: 'preferred_job_locations.preferred_location_name',searchable:true},
            {data: 'license_number', name: 'jobseeker_profiles.license_number',searchable:true},
            {data: 'state', name: 'jobseeker_profiles.state',searchable:true},
            {data: 'created_at', name: 'created_at',searchable:false},
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
            {data: 'active', name: 'active',searchable:false,render: function (data, type, row) {
                return (row.is_active == 1) ?'Yes':'No';
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'recruiter/'+row.id+'/view"  class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> View</a>&nbsp;\n\
                        <a href="'+public_path+'recruiter/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;\n\
                        <a href="'+public_path+'recruiter/'+row.id+'/adminResetPassword"  class="btn btn-xs btn-primary">Reset Password</a>&nbsp;';
            }}
        ]
    });
    
    $('#job_list').DataTable({
        processing: true,
        serverSide: true,
        //responsive: true,
        //autoWidth: false,
        ajax: public_path+'report/list',
        ordering:false,
        //bSort:true,
        //bFilter: false,
        columns: [
            {data: 'office_name', name: 'office_name',searchable:true},
            {data: 'address', name: 'address',searchable:false,render: function (data, type, row) {
                    return row.address.substring(0, 100);
            }},
            {data: 'jobtitle_name', name: 'jobtitle_name',searchable:true},
            {data: 'job_type', name: 'jobtype',searchable:false,render: function (data, type, row) {
                if(row.job_type==1){
                    return 'Fulltime';
                }else if(row.job_type==2){
                    return 'Parttime';
                }else if(row.job_type==3){
                    return 'Temporary';
                }
            }},
            {data: 'pay_rate', name: 'pay_rate',searchable:false},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'report/'+row.id+'/view"  class="btn btn-xs btn-primary"><i class="fa fa-view"></i> View Details</a>&nbsp;'
                +'<a href="javascript:deleteJob('+row.id+');"  class="btn btn-xs btn-primary"><i class="fa fa-delete"></i> Delete</a>&nbsp;';
            }}
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
            {data: 'applied_status', name: 'job_lists.applied_status',searchable:false,render: function (data, type, row) {
                if(row.applied_status==1){
                    return 'Invited';
                }else if(row.applied_status==2){
                    return 'Applied';
                }else if(row.applied_status==3){
                    return 'Shortlisted';
                }else if(row.applied_status==4){
                    return 'Hired';
                }else if(row.applied_status==5){
                    return 'Rejected';
                }else if(row.applied_status==6){
                    return 'Cancelled';
                }else{
                    return 'No Status';
                }
            }},
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
            {data: 'email', name: 'users.email',searchable:true},
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
            {data: 'office_name', name: 'recruiter_profiles.office_name',searchable:true},
            {data: 'jobtitle_name', name: 'job_titles.jobtitle_name',searchable:true},
            {data: 'job_type', name: 'jobtype',searchable:false,render: function (data, type, row) {
                if(row.job_type==1){
                    return 'Fulltime';
                }else if(row.job_type==2){
                    return 'Parttime';
                }else if(row.job_type==3){
                    return 'Temporary';
                }
            }},
            {data: 'pay_rate', name: 'pay_rate',searchable:false},
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
            {data: 'city', name: 'city',searchable:true},
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
            {data: 'license_number', name: 'license_number',searchable:false,searchable:false,render: function (data, type, row) {
                    return (row.license_number!=null && row.license_number!='')?row.license_number:'N/A';
            }},
            {data: 'state', name: 'state',searchable:false,searchable:false,render: function (data, type, row) {
                    return (row.state!=null && row.state!='')?row.state:'N/A';
            }},
            {data: 'jobtitle_name', name: 'job_title',searchable:false,searchable:false,render: function (data, type, row) {
                    return (row.jobtitle_name!=null && row.jobtitle_name!='')?row.jobtitle_name:'N/A';
            }},
            {data: 'is_job_seeker_verified', name: 'is_job_seeker_verified',searchable:false,render: function (data, type, row) {
                if(row.is_job_seeker_verified==0){
                    return 'Not Verified';
                }else if(row.is_job_seeker_verified==1){
                    return 'Approved';
                }else if(row.is_job_seeker_verified==2){
                    return 'Rejected';
                }
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'jobseeker/'+row.id+'/verification"  class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> View</a>&nbsp;';
            }}
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
            {data: 'messageTo', name: 'messageTo',searchable:true,render: function (data, type, row) {
                if(row.messageTo==1){
                    return 'All';
                }else if(row.messageTo==2){
                    return 'Recruiter';
                }else if(row.messageTo==3){
                    return 'Jobseeker';
                }
            }},
            {data: 'message', name: 'message',searchable:false},
            {data: 'messageSent', name: 'messageSent',searchable:false,render: function (data, type, row) {
                return (row.messageSent)?'Notification Sent':'<a href="'+public_path+'notify/'+row.id+'/send"  class="btn btn-xs btn-primary"><i class="fa fa-send"></i> Send Notification</a>&nbsp;'
            }},
            {data: 'createdAt', name: 'createdAt',searchable:false,render: function (data, type, row) {
                var time = row.createdAt.date.split('.')
                return time[0];
            }},
            {data: 'action', name: 'action',searchable:false,render: function (data, type, row) {
                return '<a href="'+public_path+'notify/'+row.id+'/edit"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;\n\
                        <a href="'+public_path+'notify/'+row.id+'/delete" onclick="deleteRecord(this);return false;"  class="delete btn btn-xs btn-primary"><i class="fa fa-remove"></i> Delete</a>';
            }}
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
//    var start_date = $('#start_date').datepicker({
//        format:"yyyy-mm-dd",
//        onRender: function(date) {
//            return date.valueOf() < now.valueOf() ? 'disabled' : '';
//        }
//    }).on('click', function(ev) {
//        end_date.hide();
//    }).on('changeDate', function(ev) {
//        if (ev.date.valueOf() >= end_date.date.valueOf()) {
//            var newDate = new Date(ev.date);
//            newDate.setDate(newDate.getDate());
//            end_date.setValue(newDate);
//        }else{
//            end_date.setValue(end_date.date);
//        }
//        start_date.hide();
//        $('#end_date')[0].focus();
//    }).data('datepicker');
//    var end_date = $('#end_date').datepicker({
//        format:"yyyy-mm-dd",
//        onRender: function(date) {
//            return date.valueOf() < start_date.date.valueOf() ? 'disabled' : '';
//        }
//    }).on('click', function(ev) {
//        start_date.hide();
//    }).on('changeDate', function(ev) {
//      end_date.hide();
//    }).data('datepicker');
//    
//    var startDate = $('#startDate').datepicker({
//        format:"yyyy-mm-dd",
//        onRender: function(date) {
//            return date.valueOf() > now.valueOf() ? 'disabled' : '';
//        }
//    }).on('click', function(ev) {
//        endDate.hide();
//    }).on('changeDate', function(ev) {
//        if (ev.date.valueOf() >= endDate.date.valueOf()) {
//            var newDate = new Date(ev.date);
//            newDate.setDate(newDate.getDate());
//            endDate.setValue(newDate);
//        }else{
//            endDate.setValue(endDate.date);
//        }
//        startDate.hide();
//        $('#endDate')[0].focus();
//    }).data('datepicker');
//    var endDate = $('#endDate').datepicker({
//        format:"yyyy-mm-dd",
//        onRender: function(date) {
//            return (date.valueOf() < startDate.date.valueOf() || 
//                    date.valueOf() > now.valueOf())?'disabled':'';
//        }
//    }).on('click', function(ev) {
//        startDate.hide();
//    }).on('changeDate', function(ev) {
//      endDate.hide();
//    }).data('datepicker');
    
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
                url:public_path+'../api/v1/admin/forgot-password',
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

function deleteJob(jobId){
    console.log(public_path+'report/delete-job');
    $.get(public_path+'report/delete-job',{'jobId':jobId}, function(data) {
            //do refresh
            window.location.reload();
        });
}
