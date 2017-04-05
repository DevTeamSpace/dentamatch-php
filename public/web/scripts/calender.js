var JobModel = function (data) {
        var me = this;
        me.title = ko.observable('');
        me.start = ko.observable('');
        me.officeTypeName = ko.observable('');
        me.userDetails = ko.observableArray([]);
        me.officeAddress = ko.observable('');
        me.officeName = ko.observable('');
        me.jobId = ko.observable();

        me._init = function (d) {
            me.title = d.jobtitle_name;
            exploadeTempJobs = d.temp_job_dates.split(',');
            tempJobDates = [];
            console.log(d.temp_job_dates);
            for(i in exploadeTempJobs){
                if(tempJobDates.indexOf(exploadeTempJobs[i]) < 0){
                    tempJobDates.push(exploadeTempJobs[i]);
                }
            }
            console.log(tempJobDates);
            me.start = moment(d.created_at).format('YYYY-MM-DD');
            me.officeTypeName = d.office_type_name;
            me.officeAddress = d.address;
            me.officeName = d.office_name;
            me.jobId = d.id;
            for (i in d.seekers) {
                for (j in d.seekers[i]) {
                    d.seekers[i][j].pic = d.seekers[i][j].profile_pic;
                    d.seekers[i][j].name = d.seekers[i][j].first_name + ' ' + d.seekers[i][j].last_name;
                }
                me.userDetails.push(d.seekers[i]);
            }
        };
        me._init(data);
    };

    var SeekersModel = function (data, jobId) {
        var me = this;
        me.seekerJobTitle = ko.observable('');
        me.seekerPic = ko.observable('');
        me.seekerName = ko.observable('');
        me.seekerId = ko.observable();
        me.seekerUrl = ko.observable('');

        me._init = function (d, jobId) {
            me.seekerJobTitle = d.jobtitle_name;
            me.seekerPic = d.profile_pic;
            me.seekerName = d.first_name + ' ' + d.last_name;
            me.seekerId = d.seeker_id;
            me.seekerUrl = 'job/seekerdetails/'+d.seeker_id+'/'+jobId;
        };
        me._init(data, jobId);
    };

    var CalenderVM = function (data) {
        var me = this;
        me.datesData = ko.observableArray([]);
        me.seekersOfParticularJob = ko.observableArray([]);
        me.particularJobTitle = ko.observable();
        me.particularOfficeName = ko.observable();
        me.particularOfficeTypeName = ko.observable();
        me.particularOfficeAddress = ko.observable();
        me.particularJobUrl = ko.observable();
        me.jobCreated = ko.observable();
        me.allJobs = ko.observableArray([]);

        me.getCalenderDetails = function () {
            $.get('calender-details', {}, function (d) {
                if (d.jobs.length !== 0 || typeof d.jobs !== "undefined") {
                    for (i in d.jobs)
                        me.datesData.push(new JobModel(d.jobs[i]));
                }
                for(i in me.datesData()){
                    if(me.datesData()[i].userDetails().length != 0){
                        me.datesData()[i].userDetails = me.datesData()[i].userDetails()[0]
                    }else{
                        me.datesData()[i].userDetails = [];
                    }
                }
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev',
                        center: 'title',
                        right: 'next'
                    },
                    defaultView: "month",
                    editable: false,
                    firstDay: 1,
                    columnFormat: 'dddd',
                    displayEventTime: false,
                    eventLimit: 2,
                    eventLimitText: "more jobs >",
                    eventLimitClick: function (event, jsEvent, view) {
                        me.showJobs(event);
                    },
                    eventClick: function (event, jsEvent, view) {
                        me.showSeekers(event, fw = 1);
                    },
                    eventRender: function (event, element, view) {
//                        console.log(event);
                        for (var i = 0; i <= event.userDetails.length - 1; i++) {
                            if (i < 2) {
                                $(element).find('span.fc-title').after('<img class="img-circle wd-22 mr-r-2" src="' + event.userDetails[i].pic + '" />');
                            }

                        }
                        ;
                        if (event.userDetails.length > 2) {
                            $(element).find('.fc-content').append('<span class="cir-22">' + (event.userDetails.length - 2) + "+" + '<span>');
                        }

                        var dateString = event.start.format("YYYY-MM-DD");

                        $(view.el[0]).find('.fc-day[data-date=' + dateString + ']').addClass('eventdays');


                    },
                    events: me.datesData()

                });
            });
        };

        me.showSeekers = function (d, e, fw) {
            if(fw !== "undefined"){
                console.log(fw);
            }
            me.particularJobTitle('');
            me.particularOfficeName('');
            me.particularOfficeTypeName('');
            me.particularOfficeAddress('');
            me.seekersOfParticularJob([]);

            me.particularJobTitle(d.title);
            me.particularOfficeName(d.officeName);
            me.particularOfficeTypeName(d.officeTypeName);
            me.particularOfficeAddress(d.officeAddress);
            me.particularJobUrl('job/details/'+d.jobId);
            me.jobCreated(moment(d.start).format('LL'));
            if (d.userDetails.length !== 0) {
                for (i in d.userDetails) {
                    me.seekersOfParticularJob.push(new SeekersModel(d.userDetails[i], d.jobId));
                }
            }
            $('.calendar_brief').modal();
        };
        
        me.showJobs = function(d, e){
            me.allJobs([]);
            me.jobCreated(moment(d.date).format('LL'));
            for(i in d.segs){
                me.allJobs.push(d.segs[i].event);
            }
            $('.calendar_list').modal();
        };

        me._init = function () {
            me.getCalenderDetails();
        };
        me._init();
    };
    var ssObj = new CalenderVM();
    ko.applyBindings(ssObj, $('#calenderParent')[0]);