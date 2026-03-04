const wpPot = require('wp-pot');

wpPot({
  destFile: './languages/directorist.pot',
  domain: 'directorist',
  package: 'Directorist – Business Directory & Classified Listings WordPress Plugin',
  src: [
    './**/*.php',
    '!./vendor-src/**',
    '!./node_modules/**',
    '!./vendor/**'
  ]
});