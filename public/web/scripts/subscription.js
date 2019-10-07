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
  me.isNewCustomer = ko.observable(true); // abandoned logic
  me.selectedSubscription = ko.observable('');
  me.promoCode = ko.observable('');
  me.codeSubmitting = ko.observable(false);
  me.codeMessage = ko.observable('');
  me.couponText = ko.observable('');
  me.noPayment = ko.observable(false);


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
    if (me.cardExist() || me.noPayment()) {
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
      subscriptionType: me.subscriptionType(),
      promoCode: me.promoCode()
    });
  };

  me.subscribeFunction = function () {
    me.callApi('create-subscription', {subscriptionType: me.subscriptionType(), promoCode: me.promoCode()});
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
          $('.js-long').toggle(data.subscriptionType != 1);
          $('.js-month').toggle(data.subscriptionType == 1);
          $('#successModal').modal();
        } else {
          me.subscriptionIsCancelled(true);
        }
      } else {
        me.errorMessage(response.message);
      }
    });
  }

  me.getBoxClass = function (name) {
    if (!me.selectedSubscription())
      return null;
    return me.selectedSubscription() === name? 'box--selected' : 'box--disabled';
  }
  me.monthlyClass = ko.pureComputed(function() {
    return me.getBoxClass('Monthly');
  }, me);

  me.semiAnnualClass = ko.pureComputed(function() {
    return me.getBoxClass('Semi-Annual');
  }, me);

  me.annualClass = ko.pureComputed(function() {
    return me.getBoxClass('Annual');
  }, me);

  me.clearCode = function () {
    me.selectedSubscription('');
    me.promoCode('');
    me.couponText('')
    me.codeMessage('');
    me.noPayment(false);
  }

  me.checkPromoCode = function () {
    me.codeSubmitting(true);
    $.post('/check-promo-code', {promoCode: me.promoCode()}).then(function(response){
      me.codeSubmitting(false);
      if (response.success) {
        me.promoCode(response.data.code);
        me.selectedSubscription(response.data.subscription)
        me.couponText(response.data.text)
        me.noPayment(response.data.noPayment)
      } else {
        me.clearCode();
      }
      me.codeMessage(response.message);
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
