<?php

/*
  Plugin Name: JannieForms
  Description: WordPress plugin wrapper for the JannieForms library
  Version: 0.3.1
  Author: JM Versteeg
 */

include 'jannieforms.lib.php';

add_action('wp_enqueue_scripts', function() {
    wp_register_script('underscore', plugins_url('js/underscore-min.js',   __FILE__));
    wp_register_script('jannietooltips', plugins_url('js/jannietooltips.js', __FILE__) , array('jquery'));
    wp_register_script('maskedinput', plugins_url('js/jquery.maskedinput.min.js',   __FILE__), array('jquery'));
    wp_register_script(
            'jannieforms', plugins_url('js/jannieforms.js', __FILE__) , array(
                'jquery', 'jannietooltips', 'maskedinput', 'underscore'
            ), '1.0'
        );
    wp_enqueue_script('jannieforms');

    wp_enqueue_style('jannietooltips', plugins_url('css/jannietooltips.css', __FILE__));
    wp_enqueue_style('jannieforms', plugins_url('css/jannieforms.css', __FILE__));
} );

add_action('plugins_loaded', function() {

    do_action('jannieforms-register-forms');
    
});