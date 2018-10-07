// to share jquery for all `webpack.config.addEntry(...)`
window.$ = window.jQuery = require('jquery');

require('jquery-confirm');
require('bootstrap');

require('chang/js/data-confirm');
require('chang/js/data-ajax-form');
require('chang/js/form-collection');
require('chang/js/form-validator-html5');
require('chang/js/data-toggle-display');
require('chang/js/data-form-filter');

require('./js/animated');
require('./js/crud');
require('./js/menu/stack');
require('./js/modal-close');
require('./js/modal-href');
require('./js/dismiss');
require('./js/tabs');
require('./js/toggle');
require('./js/tooltip');
require('./js/ripple');
require('./js/navbar');
