var FirstSubscriptionVM = function () {
  var me = this;
  me.subscriptionAvailable = ko.observable(false);
  me.subscriptionDetails = ko.observableArray([]);
  me.noSubscription = ko.observable(false);
  me.cardNumber = ko.observable('');
  me.expiry = ko.observable('');
  me.cvv = ko.observable('');
  me.errorMessage = ko.observable('');
  me.subscriptionType = ko.observable();
  me.successMessage = ko.observable('');
  me.creatingMessage = ko.observable('');
  me.disableInput = ko.observable(false);
  me.cardExist = ko.observable(false);
  me.subscriptionIsCancelled = ko.observable(false);
  me.subscriptionId = ko.observable('');
  me.isNewCustomer = ko.observable(true);

  me.getSubscriptionList = function () {
    $.get('get-subscription-list').then(function (d) {
      if (d.supported) {
        me.subscriptionAvailable(true);
        me.subscriptionDetails.push({monthlyPrice: 129, halfYearPrice: 99, fullYearPrice: 79});
        me.cardExist(d.cardExist);
        me.isNewCustomer(d.isNewCustomer);
      } else {
        me.noSubscription(true);
      }
    });
  };

  me.showAddCardPopup = function (d, e) {
    var subType = $(e.currentTarget).parent().find('#stype').val();
    me.subscriptionType(subType);
    if (me.cardExist()) {
      $('#subscribeModal').modal('show');
    } else {
      $('#addCardModal').modal('show');
    }
  };

  me.addCardFunction = function (d, e) {
    me.cardNumber(me.cardNumber().replace(/\_/g, ""));
    me.expiry(me.expiry().replace(/\_/g, ""));
    me.errorMessage('');

    if (!(me.cardNumber() && me.expiry() && me.cvv())) {
      me.errorMessage('Please fill all the details');
      return;
    }

    me.callApi('create-subscription', {
      cardNumber: me.cardNumber(),
      expiry: me.expiry(),
      cvv: me.cvv(),
      subscriptionType: me.subscriptionType()
    });
  };

  me.subscribeFunction = function () {
    me.callApi('create-subscription', {subscriptionType: me.subscriptionType()});
  };

  me.cancelSubscriptionFunction = function () {
    me.callApi('unsubscribe', {subscriptionId: me.subscriptionId()}, 'Cancelling please wait...');
  }

  me.callApi = function (url, data, message) {
    message = message || 'Subscribing please wait...';
    me.disableInput(true);
    me.creatingMessage(message);

    $.post(url, data).then(function (response) {
      me.creatingMessage('');
      me.disableInput(false);

      if (response.success) {
        me.successMessage(response.message);
        if (response.data) {
          me.subscriptionId(response.data);
          $('.modal').modal('hide');
          $('.js-trial').toggle(data.subscriptionType != 1);
          $('.js-no-trial').toggle(data.subscriptionType == 1);
          $('#successModal').modal();
        } else {
          me.subscriptionIsCancelled(true);
        }
      } else {
        me.errorMessage(response.message);
      }
    });
  }

  me._init = function () {
    me.getSubscriptionList();
    $('#card-number').inputmask("9999 9999 9999 9999");  //static mask
    $('#expiry, #editExpiry').inputmask("99/9999");  //static mask
    $(".modal").on("hidden.bs.modal", function () {
      me.errorMessage('');
      me.successMessage('');
      me.cardNumber('');
      me.expiry('');
      me.cvv('');
    });
  };
  me._init();
};

$(function () {
  ko.applyBindings(new FirstSubscriptionVM(), document.getElementById('subscription'));
});
