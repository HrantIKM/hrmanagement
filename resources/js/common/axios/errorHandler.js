// eslint-disable-next-line no-undef
axios.interceptors.response.use((response) => response, (error) => {
  const resp = error.response;
  if (resp && resp.status >= 400 && typeof showErrorMessage === 'function') {
    const msg = resp.data?.message
      || (resp.data?.errors && Object.values(resp.data.errors).flat().join(' '))
      || 'Request failed.';
    showErrorMessage(msg);
  }
  return Promise.reject(error);
});
