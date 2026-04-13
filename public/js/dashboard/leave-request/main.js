// eslint-disable-next-line no-undef,no-new
new FormRequest();
['leave-request-approve-form', 'leave-request-reject-form'].forEach((formId) => {
  if (document.getElementById(formId)) {
    // eslint-disable-next-line no-undef,no-new
    new FormRequest(undefined, formId);
  }
});
