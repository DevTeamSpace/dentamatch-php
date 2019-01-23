var SeekerModel = function (d) {
        var me = this;
        me.seekerId = ko.observable();
        me.seekerName = ko.observable('');
        me.seekerProfilePic = ko.observable('');
        me.seekerUrl = ko.observable('');
        me.seekerJobTitle = ko.observable('');
        
        me._init = function (d) {
            me.seekerId(d.seeker_id);
            me.seekerName(d.first_name + " " + d.last_name);
            me.seekerProfilePic(d.profile_pic);
            me.seekerJobTitle(d.jobtitle_name);
            me.seekerUrl('');
        };

        me._init(d);
    };

    var JobModel = function (d) {
        var me = this;
        me.jobDate = ko.observable();
        me.jobSeekers = ko.observableArray([]);
        me.extraJobSeekers = ko.observable('');
        me.jobId = ko.observable();

        me._init = function (d) {
            if (typeof d == "undefined") {
                return false;
            }
            me.jobDate(moment(d.job_created_at).format('ll'));
            me.jobId(d.recruiter_job_id);
            $.get('calender-seeker-details', {jobId: me.jobId,jobDate: me.jobDate,historyLoad:historyLoad}, function(d){
                for (i in d.data) {
                    if (d.data[i].length > 4) {
                        me.extraJobSeekers((d.data[i].length - 4).toString() + '+');
                    }
                    for (j in d.data[i]) {
                        me.jobSeekers.push(new SeekerModel(d.data[i][j]));
                    }
                    for(i in me.jobSeekers()){
                        me.jobSeekers()[i].seekerUrl(public_path+'job/seekerdetails/'+me.jobSeekers()[i].seekerId()+'/'+me.jobId());
                    }
                }
            });
        };

        me._init(d);
    };

    var allJobModel = function (d) {
        var me = this;
        me.jobTitle = ko.observable('');
        me.noOfJobs = ko.observable();
        me.payRate = ko.observable();
        me.jobs = ko.observableArray([]);

        me._init = function (d) {
            if (typeof d == "undefined") {
                return false;
            }
            me.jobTitle(d.jobtitle_name);
            me.noOfJobs(d.jobs_count);
            me.payRate(d.pay_rate);
            $.get('individual-temp-job', {jobTitleId: d.job_title_id,historyLoad:historyLoad}, function(data){
                for(i in data.data){
                    me.jobs.push(new JobModel(data.data[i]));
                }
            });
        };

        me._init(d);
    };

    ko.bindingHandlers.datetimePicker = {
        init: function (element, valueAccessor, bContext, allBindingsAccessor, bindingContext) {
            console.log(valueAccessor());
            $(element).datetimepicker({
                format: 'L',
            }).on('dp.change', function (e) {
                valueAccessor().opt(e.date.format('L'));
            });
        }
    };

    var ReportsVM = function () {
        var me = this;
        me.isLoading = ko.observable(false);
        me.allJobs = ko.observableArray([]);
        me.filterTo = ko.observable();
        me.filterFrom = ko.observable();
        me.filterError = ko.observable('');
        me.modalJobDate = ko.observable('');
        me.modalSeekers = ko.observableArray('');
        me.modalJobId = ko.observable();

        me.getAllTempJobs = function () {
            console.log(historyLoad);
            $.get('reports-temp-jobs',{ historyLoad:historyLoad }, function (d) {
                for (i in d.data) {
                    me.allJobs.push(new allJobModel(d.data[i]));
                }
            });
        };

        me.showToolTip = function (d, e) {
            $(e.currentTarget).tooltip();
        }

        me.filterJobList = ko.computed(function () {
            if (typeof me.filterFrom() !== "undefined" && typeof me.filterTo() !== "undefined") {
                if (moment(me.filterFrom()) <= moment(me.filterTo())) {
                    return ko.utils.arrayFilter(me.allJobs(), function (job) {
                        var z = ko.utils.arrayFilter(job.jobs(), function (pjob) {
                            var a = pjob.jobDate();
                            var b = me.filterTo();
                            var c = me.filterFrom();
                            var result =  moment(a) <= moment(b) && moment(a) >= moment(c) ? true : false;
                            return result;
                        });
                        return z.length > 0;
                    });
                } else {
                    return me.allJobs();
                }
            } else {
                return me.allJobs();
            }
        });

        me.showHiredJobSeekers = function (d, e) {
            me.modalSeekers([]);
            me.modalJobDate('');
            me.modalJobId('');
            
            me.modalJobDate(d.jobDate());
            me.modalJobId(d.jobId);
            for(i in d.jobSeekers()){
                d.jobSeekers()[i].seekerUrl(public_path+'job/seekerdetails/'+d.jobSeekers()[i].seekerId()+'/'+d.jobId());
                me.modalSeekers.push(d.jobSeekers()[i]);
            }
            $('.reportsModal').modal('show');
        };

        me._init = function () {
            me.getAllTempJobs();
        };
        me._init();
    };
    var ssObj = new ReportsVM();
    ko.applyBindings(ssObj, $('#report')[0]);