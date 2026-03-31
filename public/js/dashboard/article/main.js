/*
const options = {
  methods: {
    beforeSendRequest() {
      // Before send axios request function called
    },

    afterSuccess(resp) {
      // After success function called
    },

    afterError(resp) {
      // After error function called
    },
  }
}
*/

new FormRequest();
const infoModal = new Modal('infoModal');

$(function () {
  infoModalEvent();
});

function infoModalEvent() {
  infoModal.afterShow(function () {
    // Info Modal Opened!
  });

  infoModal.afterHide(function () {
    // Info Modal Hide!
  });

  infoModal.save(function (e) {
    const currentModalContent = infoModal.getModalElement();

    alert("Saved!");
    infoModal.hide();
  });

  infoModal.cancel(function (e) {
    // e.stopPropagation(); // stop close event

    const currentModalContent = infoModal.getModalElement();

    alert("Canceled!");

    // infoModal.hide()
    // infoModal.show()
  });

  // if custom btn need to add click
  // infoModal.clickItem('.custom-save-btn', function () {
  //   console.log('custom-save-btn');
  // });
}

// Bootstrap5 modal show part
// const modal = new bootstrap.Modal($('#exampleModal'));
// modal.show();
