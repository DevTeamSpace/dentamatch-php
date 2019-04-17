var SubscriptionModel = function (data) {
  var me = this;
  me.subscriptionAmount = ko.observable();
  me.subscriptionPlan = ko.observable('');
  me.subscriptionActivation = ko.observable();
  me.subscriptionAutoRenewal = ko.observable();
  me.subscriptionTrialEnd = ko.observable();
  me.subscriptionCancelAt = ko.observable();
  me.subscriptionId = ko.observable('');

  me._init = function (d) {
    me.subscriptionId(d.id);
    var planCost = d.plan.amount / 100;
    me.subscriptionAmount("$" + planCost);
    me.subscriptionActivation(moment.unix(d.created).utc().format('LL'));
    me.subscriptionAutoRenewal(moment.unix(d.current_period_end).utc().format('LL'));
    me.subscriptionCancelAt(moment.unix(d.cancel_at).utc().format('LL'));
    me.subscriptionTrialEnd(moment.unix(d.trial_end).utc().format('LL'));
    me.subscriptionPlan(d.plan.nickname);
  }
  me._init(data);
  return me;
};

var SubscriptionVM = function () {
  var me = this;
  me.isInRequest = ko.observable(false);
  me.isLoadingSubscriptions = ko.observable(false);
  me.visibleSubscription = ko.observable(false);
  me.subscriptionDetails = ko.observableArray([]);
  me.noSubscription = ko.observable(false);
  me.noSubscriptionDetails = ko.observable('');
  me.cardNumber = ko.observable('');
  me.expiry = ko.observable('');
  me.cvv = ko.observable('');
  me.errorMessage = ko.observable('');
  me.successMessage = ko.observable('');
  me.creatingMessage = ko.observable('');
  me.subscription = ko.observableArray([]);
  me.currentPlanNickname = ko.observable('');
  me.cards = ko.observableArray([]);
  me.prompt = ko.observable('');
  me.headMessage = ko.observable('');
  me.showModalFooter = ko.observable(true);
  me.actionButtonText = ko.observable('');
  me.addCardVisible = ko.observable(true);
  me.editExpiry = ko.observable('');
  me.editCardId = ko.observable('');
  me.isSubscribed = ko.observable(true);
  me.isOnTrial = ko.observable(false);

  me.submitModalHandler = null;
  me.onModalSubmit = function (d, e) {
    me.submitModalHandler(d, e);
  };

  me.getSubscription = function () {
    if (me.isLoadingSubscriptions()) {
      return false;
    }
    me.isLoadingSubscriptions(true);
    $.get('get-subscription-details', {}, function (d) {
      me.isLoadingSubscriptions(false);
      var customer = d.data;
      if (d.success && (!customer || !d.data.subscriptions.total_count)) {
        me.noSubscription(true);
        me.noSubscriptionDetails('No subscription availed.');
        location.href = 'subscription-detail';
      } else {
        me.visibleSubscription(true);
        if (d.data.sources.data.length >= 2) {
          me.addCardVisible(false);
        }

        for (i in d.data.subscriptions.data) {
          var subscription = d.data.subscriptions.data[i];
          me.subscription.push(new SubscriptionModel(subscription));

          if (subscription.trial_end > moment.utc().unix()) {
            me.isOnTrial(true);
          }

          if (subscription.cancel_at_period_end === false) {
            me.isSubscribed(true);
            me.currentPlanNickname(subscription.plan.nickname);
          } else {
            me.isSubscribed(false);
          }
          break;
        }
        customer.sources.data.forEach(function (source) {
          source.exp_month = ("0" + source.exp_month).slice(-2);
          me.cards.push(source);
        });
      }
    }).error(function (xhr, e) {
      me.isLoadingSubscriptions(false);
    });
  };

  me.showAddCardPopup = function () {
    $('#addCardModal').modal('show');
  };

  me.showEditCardPopup = function (d) {
    me.editExpiry(d.exp_month + '/' + d.exp_year);
    me.editCardId(d.id);
    $('#editCardModal').modal('show');
  };

  me.showDeleteCardPopup = function (d) {
    if (me.cards().length <= 1) {
      me.showModalFooter(false);
      me.showActionModal('Delete Card', 'Cannot delete card, you should have at least one card added to continue the subscription.', 'Delete')
      return false;
    }
    me.submitModalHandler = me.deleteCardFunction.bind(d);
    me.showActionModal('Delete Card', 'Do you want to delete card?', 'Delete')
  };

  me.showUnsubscribePopup = function () {
    me.submitModalHandler = me.unsubscribeFunction;
    me.showActionModal('Unsubscribe', 'Do you want to unsubscribe?', 'Unsubscribe')
  };

  me.showSubscribePopup = function () {
    me.submitModalHandler = me.subscribeFunction;
    me.showActionModal('Subscribe Again', 'Do you want to subscribe again?', 'Subscribe');
  };

  me.showSwitchToPopup = function (d, e) {
    var switchToPlan = e.target.dataset.plan;
    var price = e.target.dataset.price;
    var message = 'Do you want to change plan to <br>' + switchToPlan + '?';
    message += '<br><br><strong>$' + price + ' per month billed up front</strong>'

    me.submitModalHandler = me.switchToFunction.bind(this, switchToPlan);
    me.showActionModal('Change Plan', message, 'Change')
  }

  me.showActionModal = function (title, message, btnText) {
    me.headMessage(title);
    me.prompt(message);
    me.actionButtonText(btnText);
    $('#actionModal').modal('show');
  }

  me.addCardFunction = function () {
    me.cardNumber(me.cardNumber().replace(/\_/g, ""));
    me.expiry(me.expiry().replace(/\_/g, ""));
    me.errorMessage('');

    if (!(me.cardNumber() && me.expiry() && me.cvv())) {
      me.errorMessage('Please fill all the details');
      return;
    }
    me.callApi('add-card', {cardNumber: me.cardNumber(), expiry: me.expiry(), cvv: me.cvv()},
      'Adding card please wait...', 'Card added successfully.', '');
  };

  me.editCardFunction = function (d, e) {
    me.editExpiry(me.editExpiry().replace(/\_/g, ""));
    me.errorMessage('');

    if (!me.editExpiry()) {
      me.errorMessage('Please fill the details');
      return;
    }

    me.callApi('edit-card', {expiry: me.editExpiry(), cardId: me.editCardId()}, 'Updating card please wait...', '');
  };

  me.deleteCardFunction = function () {
    me.callApi('delete-card', {cardId: this.id}, 'Deleting card please wait...', 'Card deleted successfully.');
  };

  me.unsubscribeFunction = function () {
    var subscriptionId = me.subscription()[0].subscriptionId;
    me.callApi('unsubscribe', {subscriptionId: subscriptionId}, 'Unsubscribing please wait...', 'Unsubscribed successfully.');
  };

  me.subscribeFunction = function () {
    me.callApi('change-subscription-plan', {
      plan: me.subscription()[0].subscriptionPlan(),
      subscriptionId: me.subscription()[0].subscriptionId
    }, 'Subscribing please wait...', 'Subscribed successfully.');
  };

  me.switchToFunction = function (nickname) {
    me.callApi('change-subscription-plan', {
      subscriptionId: me.subscription()[0].subscriptionId(),
      plan: nickname
    }, 'Changing please wait...', 'Plan changed successfully.');
  }

  me.callApi = function (url, data, creatingMessage, successMessage) {
    me.isInRequest(true);
    me.creatingMessage(creatingMessage);

    $.post(url, data).then(function (response) {
      if (response.success) {
        me.prompt(successMessage);
        me.creatingMessage('');
        me.successMessage(successMessage || response.message);
        setTimeout( function () { location.reload(); }, 700);
      } else {
        me.isInRequest(false);
        me.prompt(response.message);
        me.errorMessage(response.message);
      }
    });
  }

  me._init = function () {
    $('body').find('#ChildVerticalTab_1').find('li').removeClass('resp-tab-active');
    $('body').find('#ChildVerticalTab_1').find('li:nth-child(4)').addClass('resp-tab-active')
    me.getSubscription();
    $('#card-number').inputmask("9999 9999 9999 9999");  //static mask
    $('#expiry, #editExpiry').inputmask("99/9999");  //static mask

    $(".modal").on("hidden.bs.modal", function () {
      me.submitModalHandler = null;
      me.errorMessage('');
      me.successMessage('');
      me.showModalFooter(true);
    });
  };
  me._init();
};

$(function () {
  ko.applyBindings(new SubscriptionVM(), document.getElementById('subscription'));
});

