// eslint-disable-next-line no-undef
axios.interceptors.response.use((response) => response, (error) => {
  const resp = error.response;

  if (resp.status !== 200 || resp.status !== 201) {
    showErrorMessage(resp.data.message);
  }
  return Promise.reject(error);
});
