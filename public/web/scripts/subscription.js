var FirstSubscriptionVM = function () {
    var me = this;
    me.isLoading = ko.observable(false);
    me.visibleSubcription = ko.observable(false);
    me.subscriptionDetails = ko.observableArray([]);
    me.noSubscription = ko.observable(false);
    me.noSubscriptionDetails = ko.observable('');
    me.cancelButton = ko.observable(true);
    me.cardNumber = ko.observable();
    me.expiry = ko.observable('');
    me.cvv = ko.observable();
    me.errorMessage = ko.observable('');
    me.trailPeriod = ko.observable();
    me.subscriptionType = ko.observable();
    me.successMessage = ko.observable('');
    me.creatingMessage = ko.observable('');
    me.disableInput = ko.observable(false);
    me.cardExist = ko.observable(false);
    me.cancelButtonSubscribe = ko.observable(true);
    
    me.getSubscriptionList = function () {
        if (me.isLoading()) {
            return false;
        }
        me.isLoading(true);
        $.get('get-subscription-list', {}, function (d) {
            if(typeof d.data != "undefined"){
                if(d.data.length == 0){
                    d.data['trailPeriod'] = 0;
                    d.data['free_trial_period'] = 'with 0 months of free trial'
                }else{
                    d.data['trailPeriod'] = d.data['free_trial_period'];
                    d.data['free_trial_period'] = 'with '+d.data['free_trial_period']+' months free trial';
                }
                d.data['halfYearPrice'] = 60;
                d.data['fullYearPrice'] = 99;
                me.visibleSubcription(true);
                me.subscriptionDetails.push(d.data);
                if(d.customer.length != 0){
                    if(d.customer[0].sources.data.length == 0){
                        me.cardExist(false);
                    }else{
                        me.cardExist(true);
                    }
                }else{
                    me.cardExist(false);
                }
            }else{
                me.visibleSubcription(false);
                me.noSubscription(true);
                me.noSubscriptionDetails('We are not provideing service in your area, we will notifiy you whenever we will start our services in your area.');
            }
        }).error(function (xhr, e) {
            me.isLoading(false);
        });
    };
    
    me.addCard = function(d, e){
        me.cardNumber();
        me.expiry();
//        me.expiryWithSlash = ko.computed(function(){
//            if(me.expiry().length == 2){
//                me.expiry(me.expiry()+'/');
//            }else{
//                me.expiry();
//            }
//            return me.expiry();
//        });
        me.cvv();
        me.trailPeriod(d.trailPeriod);
        subType = $(e.currentTarget).parent().find('#stype').val();
        me.subscriptionType(subType);
        me.creatingMessage('');
        me.disableInput(false);
        if(me.cardExist() == true){
            $('#cardAlreadySubscribe').removeAttr('disabled');
            $('#subscribeModal').modal('show');
        }else{
            $('#addCardButton').removeAttr('disabled');
            $('#addCardModal').modal('show');
        }
    };
    
    $(".modal").on("hidden.bs.modal", function(){
        me.errorMessage('');
        me.successMessage('');
        me.cardNumber('');
        me.expiry('');
        me.cvv('');
        me.disableInput(false);
    });
    
    me.addCardFunction = function(d, e){
        console.log(me.expiry());
        me.errorMessage('');
        me.successMessage('');
        if(me.expiry() != null && (me.expiry().indexOf('/') >= 0 || me.expiry().indexOf('/') < 0)){
            var expirySplit = me.expiry().split('/');
            if(expirySplit[1] == null || expirySplit[1] == ""){
                me.errorMessage('Invalid expiry date.');
                return false;
            }
            if(expirySplit[0] == null || expirySplit[0] == ""){
                me.errorMessage('Invalid expiry date.');
                return false;
            }
        }
        if(me.cardNumber() != null && me.expiry() != null && me.cvv() != null){
            me.cancelButton(false);
            me.disableInput(true);
            $('#addCardButton').attr('disabled','disabled');
            me.creatingMessage('Adding card please wait...');
            $('#addCardModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.post('create-subscription', {cardNumber: me.cardNumber(), expiry: me.expiry(), cvv: me.cvv(), subscriptionType: me.subscriptionType(), trailPeriod: me.trailPeriod(), cardExist: me.cardExist()}, function(d){
                me.creatingMessage('');
                if(d.success == false){
                    me.errorMessage(d.message);
                    me.successMessage('');
                    me.cancelButton(true);
                    $('#addCardButton').removeAttr('disabled');
                    me.disableInput(false);
                }else{
                    me.errorMessage('');
                    me.successMessage(d.message);
                    setTimeout(
                        function ()
                        {
                            location.href = 'jobtemplates';
                        }, 700);
                }
            });
        }else{
            me.errorMessage('Please fill all the details');
            me.creatingMessage('');
            me.cancelButton(true);
            me.disableInput(false);
            $('#addCardModal').modal({
                backdrop: true,
                keyboard: true
            });
        }
    };
    
    me.cardAlreadySubscribe = function(d, e){
        me.errorMessage('');
        me.successMessage('');
        me.cancelButtonSubscribe(false);
        me.creatingMessage('Subscribing please wait...');
        $('#cardAlreadySubscribe').attr('disabled','disabled');
        $('#subscribeModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $.post('create-subscription', {subscriptionType: me.subscriptionType(), trailPeriod: me.trailPeriod(), cardExist: me.cardExist()}, function(d){
            me.creatingMessage('');
            if(d.success == false){
                me.errorMessage(d.message);
                me.successMessage('');
                me.cancelButtonSubscribe(true);
                $('#cardAlreadySubscribe').removeAttr('disabled');
            }else{
                me.errorMessage('');
                me.successMessage(d.message);
                setTimeout(
                    function ()
                    {
                        location.href = 'jobtemplates';
                    }, 700);
            }
        });
    };
    
    me._init = function () {
        me.getSubscriptionList();
        $(document).ready(function () {
            $('#card-number').inputmask("9999 9999 9999 9999");  //static mask
            $('#expiry, #editExpiry').inputmask("99/9999");  //static mask
        });
    };
    me._init();
};
var fsObj = new FirstSubscriptionVM();
ko.applyBindings(fsObj, $('#subscription')[0]);