/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// require jQuery and make it globally accessible
import $ from 'jquery';
global.$ = global.jQuery = window.jQuery = $;

// require Bootstrap
require('bootstrap');

// add Remix Icon
import 'remixicon/fonts/remixicon.css';

// Klassy Cafe theme
require('./klassy_cafe/assets/js/base');

// start the Stimulus application
import './bootstrap';
