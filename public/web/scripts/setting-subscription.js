var SubscriptionModel = function (data) {
    var me = this;
    me.subscriptionAmount = ko.observable();
    me.subscriptionPlan = ko.observable('');
    me.subscriptionActivation = ko.observable();
    me.subscriptionAutoRenewal = ko.observable();
    me.subscriptionId = ko.observable('');
    me.leftDays = ko.observable('');

    me._init = function (d) {
        if (typeof d == "undefined") {
            return false;
        }
        me.subscriptionId(d.id);
        me.subscriptionAmount("$" + (String)(d.plan.amount).slice(0, 2));
        me.subscriptionActivation(moment(d.created).format('LL'));
        me.subscriptionAutoRenewal(moment(d.current_period_end).format('LL'));
        me.leftDays(moment(d.current_period_end).diff(moment(d.created), 'days'));
        console.log(d.plan.interval_count);
        if (d.plan.interval_count == 2) {
            me.subscriptionPlan('Half Yearly');
        } else {
            me.subscriptionPlan('Yearly');
        }
    }
    me._init(data);
    return me;
};

var SubscriptionVM = function () {
    var me = this;
    me.isLoading = ko.observable(false);
    me.loadingSubscription = ko.observable('');
    me.isLoadingSubscription = ko.observable(true);
    me.visibleSubcription = ko.observable(false);
    me.subscriptionDetails = ko.observableArray([]);
    me.noSubscription = ko.observable(false);
    me.noSubscriptionDetails = ko.observable('');
    me.cancelButton = ko.observable(true);
    me.cardNumber = ko.observable();
    me.expiry = ko.observable('');
    me.cvv = ko.observable();
    me.errorMessage = ko.observable('');
    me.successMessage = ko.observable('');
    me.creatingMessage = ko.observable('');
    me.disableInput = ko.observable(false);
    me.subscription = ko.observableArray([]);
    me.cards = ko.observableArray([]);
    me.allData = ko.observableArray([]);
    me.prompt = ko.observable('');
    me.cancelButtonDelete = ko.observable(false);
    me.headMessage = ko.observable('');
    me.showModalFooter = ko.observable(true);
    me.actionButtonText = ko.observable('');
    me.addCardVisible = ko.observable(true);
    me.cancelButtonEdit = ko.observable(true);
    me.editCvv = ko.observable();
    me.editExpiry = ko.observable('');
    me.editCardNumber = ko.observable('');
    me.editCardId = ko.observable('');
    me.unsubscribeButton = ko.observable(true);
    me.resubscribeButton = ko.observable(false);
    me.subscriptionType = ko.observable();
    me.switchVisible = ko.observable(false);
    me.switchToText = ko.observable('');

    me.getSubscription = function () {
        me.isLoadingSubscription(true);
        me.loadingSubscription('Loading subscription please wait.');
        if (me.isLoading()) {
            return false;
        }
        me.isLoading(true);
        $.get('get-subscription-details', {}, function (d) {
            me.isLoadingSubscription(false);
            if (d.success == false || d.data.data.data.subscriptions.data.length == 0) {
                me.noSubscription(true);
                me.visibleSubcription(false);
                me.noSubscriptionDetails('No subscription availed.');
            } else {
                for (i in d.data.data.data.subscriptions.data) {
                    if (d.data.data.data.subscriptions.data[i].cancel_at_period_end === false) {
                        me.noSubscription(false);
                        me.visibleSubcription(true);
                        if (d.data.data.data.sources.data.length >= 2) {
                            me.addCardVisible(false);
                        }
                        me.subscription.push(new SubscriptionModel(d.data.data.data.subscriptions.data[i]));
                        me.unsubscribeButton(true);
                        me.resubscribeButton(false);
                        me.switchVisible(true);
                        console.log(d.data.data.data.subscriptions.data[i].plan.id);
                        if (d.data.data.data.subscriptions.data[i].plan.id === "one-year") {
                            me.switchToText('Switch to Half Yearly');
                        } else {
                            me.switchToText('Switch to Yearly');
                        }
                        break;
                    } else {
                        me.noSubscription(false);
                        me.visibleSubcription(true);
                        if (d.data.data.data.sources.data.length >= 2) {
                            me.addCardVisible(false);
                        }
                        me.subscription.push(new SubscriptionModel(d.data.data.data.subscriptions.data[i]));
                        me.resubscribeButton(true);
                        me.unsubscribeButton(false);
                        me.switchVisible(false);
                        break;
                    }
                }
                for (i in d.data.data.data.sources.data) {
                    if(d.data.data.data.sources.data[i].exp_month <= 9){
                        d.data.data.data.sources.data[i].exp_month = '0'+d.data.data.data.sources.data[i].exp_month;
                    }
                    me.cards.push(d.data.data.data.sources.data[i]);
                }
                me.allData.push(me.subscription(), me.cards()[0]);
            }
        }).error(function (xhr, e) {
            me.isLoading(false);
        });
    };

    me.addCard = function (d, e) {
        me.cardNumber();
        me.expiry();
        me.expiryWithSlash = ko.computed(function () {
            if (me.expiry().length == 2) {
                me.expiry(me.expiry() + '/');
            } else {
                me.expiry();
            }
            return me.expiry();
        });
        me.cvv();
        me.creatingMessage('');
        me.disableInput(false);
        me.errorMessage('');
        me.successMessage('');
        $('#addCardModal').modal('show');
    };

    $(".modal").on("hidden.bs.modal", function () {
        //        me.errorMessage('');
        //        me.successMessage('');
        //        me.cardNumber('');
        //        me.expiry('');
        //        me.cvv('');
        //        me.disableInput(false);
        //        me.prompt('');
        //        me.headMessage('');
        //        me.actionButtonText('');
        //        me.showModalFooter(false);
        //        me.cancelButtonDelete(false);
        //        me.editCvv();
        //        me.editCardNumber('');
        //        me.editExpiry('');
    });

    me.addCardFunction = function (d, e) {
        me.cardNumber(me.cardNumber().replace(/\_/g , ""));
        me.expiry(me.expiry().replace(/\_/g , ""));
        me.errorMessage('');
        me.successMessage('');

        if (me.expiry() != null && (me.expiry().indexOf('/') >= 0 || me.expiry().indexOf('/') < 0)) {
            var expirySplit = me.expiry().split('/');
            if (expirySplit[1] == null || expirySplit[1] == "") {
                me.errorMessage('Invalid expiry date.');
                return false;
            }
            if (expirySplit[0] == null || expirySplit[0] == "") {
                me.errorMessage('Invalid expiry date.');
                return false;
            }
        }
        if (me.cardNumber() != null && me.expiry() != null && me.cvv() != null) {
            me.cancelButton(false);
            me.disableInput(true);
            $('#addCardButton').attr('disabled', 'disabled');
            me.creatingMessage('Adding card please wait...');
            $('#addCardModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.post('add-card', {cardNumber: me.cardNumber(), expiry: me.expiry(), cvv: me.cvv()}, function (d) {
                me.creatingMessage('');
                if (d.success == false) {
                    me.errorMessage(d.message);
                    me.successMessage('');
                    me.cancelButton(true);
                    $('#addCardButton').removeAttr('disabled');
                    me.disableInput(false);
                } else {
                    me.errorMessage('');
                    me.successMessage(d.message);
                    setTimeout(
                            function () {
                                location.reload();
                            }, 700);
                }
            });
        } else {
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

    me.deleteCard = function (d, e) {
        if (me.cards().length <= 1) {
            me.prompt('Cannot delete card, you should have atleast one card added to continue the subscription.');
            me.headMessage('Delete Card');
            me.showModalFooter(false);
            me.cancelButtonDelete(true);
            $('#actionModal').modal('show');
            return false;
        } else {
            me.prompt('Do you want to delete card.');
            me.headMessage('Delete Card');
            me.actionButtonText('Delete');
            me.showModalFooter(true);
            me.cancelButtonDelete(true);
            $('#actionModal').modal('show');
            $('#actionButton').on('click', function () {
                me.prompt('Deleting card please wait.');
                me.headMessage('Delete Card');
                me.cancelButtonDelete(false);
                me.showModalFooter(false);
                $.post('delete-card', {cardId: d.id}, function (d) {
                    if (d.success == false) {
                        me.prompt('Cannot delete card, please contact admin.');
                        me.headMessage('Delete Card');
                        me.cancelButtonDelete(true);
                        me.showModalFooter(true);
                    } else {
                        me.prompt('Card deleted successfully.');
                        setTimeout(
                                function () {
                                    location.reload();
                                }, 400);
                    }
                });
            });
        }
    };

    me.unsubscribePlan = function (d, e) {
        me.prompt('Do you want to unsubscribe ?');
        me.headMessage('Unsubscribe');
        me.showModalFooter(true);
        me.cancelButtonDelete(true);
        me.actionButtonText('Unsubscribe');
        $('#actionModal').modal('show');
        $('#actionButton').on('click', function () {
            me.prompt('Unsubscribing please wait.');
            me.cancelButtonDelete(false);
            me.showModalFooter(false);
            subscriptionId = d.subscription()[0].subscriptionId;
            $.post('unsubscribe', {subscriptionId: subscriptionId}, function (d) {
                if (d.success == true) {
                    me.prompt('Unsubscribed successfully.');
                } else {
                    me.prompt(d.message);
                }
                setTimeout(function () {
                    location.reload();
                }, 700);
            });
        });
    };

    me.subscribeAgain = function (d, e) {
        me.subscriptionType();
        me.prompt('Do you want to subscribe again ?');
        me.headMessage('Subscribe Again');
        me.showModalFooter(true);
        me.cancelButtonDelete(true);
        me.actionButtonText('Subscribe');

        if (d.subscription()[0].subscriptionPlan() === 'Yearly') {
            me.subscriptionType(2);
        } else {
            me.subscriptionType(1);
        }
        $('#actionModal').modal('show');
        $('#actionButton').on('click', function () {
            me.prompt('Subscribing please wait.');
            me.cancelButtonDelete(false);
            me.showModalFooter(false);
            $.post('change-subscription-plan', {plan: me.subscriptionType(), subscriptionId: d.subscription()[0].subscriptionId, type: "resubscribe"}, function (d) {
                if (d.success == true) {
                    me.prompt('Subscribed successfully.');
                } else {
                    me.prompt(d.message);
                }
                setTimeout(function () {
                    location.reload();
                }, 700);
            });
        });
    };

    me.switchTo = function (d, e) {
        me.headMessage('Change Plan');
        me.showModalFooter(true);
        me.cancelButtonDelete(true);
        me.actionButtonText('Change');
        if (d.subscription()[0].subscriptionPlan() === "Yearly") {
            me.prompt('Do you want to change plan to half yearly. ?');
        } else {
            me.prompt('Do you want to change plan to annually. ?');
        }
        $('#actionModal').modal('show');
        $('#actionButton').on('click', function () {
            me.prompt('Changing please wait.');
            me.cancelButtonDelete(false);
            me.showModalFooter(false);
            if (d.subscription()[0].subscriptionPlan() == "Yearly") {
                plan = 1;
            } else {
                plan = 2;
            }
            $.post('change-subscription-plan', {subscriptionId: d.subscription()[0].subscriptionId, plan: plan, type: "change"}, function (d) {
                if (d.success == true) {
                    me.prompt('Plan changed successfully.');
                } else {
                    me.prompt(d.message);
                }
                setTimeout(function () {
                    location.reload();
                }, 700);
            });
        });
    }

    me.editCard = function (d, e) {
        me.editCvv();
        me.editCardNumber('XXXX-XXXX-XXXX-' + d.last4);
        me.editExpiry(d.exp_month + '/' + d.exp_year);
        me.editCardId(d.id);
        me.errorMessage('');
        me.successMessage('');
        me.creatingMessage('');
        $('#editCardModal').modal('show');
    };

    me.editCardFunction = function (d, e) {
        me.editExpiry(me.editExpiry().replace(/\_/g , ""));
        me.errorMessage('');
        me.successMessage('');
        if (me.editExpiry() !== null && me.editExpiry().indexOf('/') >= 0) {
            var editExpirySplit = me.editExpiry().split('/');
            if (editExpirySplit[1] === null || editExpirySplit[1] === "" || editExpirySplit[0] === null || editExpirySplit[0] === "") {
                me.errorMessage('Invalid expiry details.');
                return false;
            }
        }
        if (me.editExpiry() !== null || me.editExpiry() !== '') {
            me.cancelButtonEdit(false);
            $('#editCardButton').attr('disabled', 'disabled');
            me.creatingMessage('Updating card please wait...');
            $('#editCardModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.post('edit-card', {expiry: me.editExpiry(), cardId: me.editCardId()}, function (data) {
                me.creatingMessage('');
                if (data.success == false) {
                    me.errorMessage(data.message);
                    me.successMessage('');
                    me.cancelButtonEdit(true);
                    $('#editCardButton').removeAttr('disabled');
                } else {
                    me.errorMessage('');
                    me.successMessage(data.message);
                    setTimeout(
                            function () {
                                location.reload();
                            }, 700);
                }
            });
        } else {
            me.errorMessage('Please fill the details');
            me.creatingMessage('');
            me.cancelButtonEdit(true);
            $('#editCardModal').modal({
                backdrop: true,
                keyboard: true
            });
        }
    };

    me._init = function () {
        $('body').find('#ChildVerticalTab_1').find('li').removeClass('resp-tab-active');
        $('body').find('#ChildVerticalTab_1').find('li:nth-child(4)').addClass('resp-tab-active')
        me.getSubscription();
        $(document).ready(function () {
            $('#card-number').inputmask("9999 9999 9999 9999");  //static mask
            $('#expiry, #editExpiry').inputmask("99/9999");  //static mask
        });
    };
    me._init();
};
var ssObj = new SubscriptionVM();
ko.applyBindings(ssObj, $('#subscription')[0]);
