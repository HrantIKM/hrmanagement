module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'airbnb-base',
  ],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module',
  },
  plugins: [],
  globals: {
    iziToast: 'readonly',
    $: 'readonly',
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
        extensions: ['.js'],
        moduleDirectory: ['node_modules/', 'resources/'],
      },
    },
  },
};
