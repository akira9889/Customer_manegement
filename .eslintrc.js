module.exports = {
  parser: "@babel/eslint-parser",
  parserOptions: {
    "requireConfigFile": false,
  },
  extends: "airbnb-base",
  rules: {
    "no-undef": 0,
    "no-alert": 0,
    "no-console": 0,
    "no-use-before-define": 0,
    "no-unused-vars": 0,
  }
};
