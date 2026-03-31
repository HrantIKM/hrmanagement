module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'plugin:vue/essential',
    'airbnb-base',
    'plugin:vue/base',
  ],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module',
  },
  plugins: [
    'vue',
  ],
  globals: {
    iziToast: 'readonly',
    $: 'readonly',
    Vue: 'readonly',
    moment: 'readonly',
  },
  rules: {
    'import/no-extraneous-dependencies': ['error', { devDependencies: true }],
    'no-alert': 'off',
    'import/prefer-default-export': 'off',
    'import/extensions': ['error', 'always', {
      js: 'never',
      mjs: 'never',
      jsx: 'never',
      ts: 'never',
      tsx: 'never',
      vue: 'never',
    }],
    camelcase: 'off',
    'no-plusplus': 'off',
    semi: 'error',
    'func-names': ['error', 'never'],
    'no-unused-vars': ['error', { vars: 'all', args: 'none', ignoreRestSiblings: false }],
    'no-param-reassign': 'off',
    radix: 'off',
    'no-restricted-globals': 'off',
    'import/order': 'off',
  },
  settings: {
    'import/resolver': {
      node: {
        extensions: ['.js', '.vue'],
        moduleDirectory: ['node_modules/', 'resources/'],
      },
    },
  },
};
