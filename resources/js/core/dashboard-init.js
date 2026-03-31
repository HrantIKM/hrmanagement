// Route
window.route = (name, params = null) => {
  const route = routesData[name];
  let uri = routesData[name].uri.toString();

  // eslint-disable-next-line array-callback-return
  route.parameters.map((item, index) => {
    if (Array.isArray(params)) {
      uri = uri.replace(`{${item}}`, params[index]);
    } else {
      if (index) {
        params = '';
      }
      uri = uri.replace(`{${item}}`, params);
    }
  });
  return `${location.origin}/${uri}`;
};

window.$trans = (key) => _.get(window.trans, key, key);

// Roles Permissions
const { roles, permissions } = window.$app;

window.$can = function (value) {
  // eslint-disable-next-line no-underscore-dangle
  let _return = false;
  if (!Array.isArray(permissions)) {
    return false;
  }
  if (value.includes('|')) {
    value.split('|')
      .forEach((item) => {
        if (permissions.includes(item.trim())) {
          _return = true;
        }
      });
  } else if (value.includes('&')) {
    _return = true;
    value.split('&')
      .forEach((item) => {
        if (!permissions.includes(item.trim())) {
          _return = false;
        }
      });
  } else {
    _return = permissions.includes(value.trim());
  }
  return _return;
};

window.$is = (value) => {
  // eslint-disable-next-line no-underscore-dangle
  let _return = false;
  if (!Array.isArray(roles)) {
    return false;
  }
  if (value.includes('|')) {
    value.split('|')
      .forEach((item) => {
        if (roles.includes(item.trim())) {
          _return = true;
        }
      });
  } else if (value.includes('&')) {
    _return = true;
    value.split('&')
      .forEach((item) => {
        if (!roles.includes(item.trim())) {
          _return = false;
        }
      });
  } else {
    _return = roles.includes(value.trim());
  }
  return _return;
};
