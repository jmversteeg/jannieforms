<?php
/*
  Plugin Name: Fieldwork
  Description: WordPress plugin wrapper for the Fieldwork library
  Version: 4.0.1
  Author: JM Versteeg
 */

function wp_sanity_checks ()
{
    if (!file_exists(__DIR__ . '/vendor')) {
        throw new Exception('vendor directory not found in ' . __DIR__ . '. Did you forget to run composer install?');
    }
}

try {
    wp_sanity_checks();
} catch (Exception $e) {
    die($e->getMessage());
}

spl_autoload_register(function ($class) {
    $prefix   = 'fieldwork\\';
    $base_dir = __DIR__ . '/src/fieldwork/';
    $len      = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0)
        return;
    $relative_class = substr($class, $len);
    $file           = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file))
        require $file;
});

add_action('wp_enqueue_scripts', function () {
    $bower_components = plugins_url('bower_components/', __FILE__);

    wp_register_script('jquery-maskedinputs', $bower_components . 'jquery-maskedinputs/dist/jquery.maskedinput.min.js', array('jquery'), '1.4.0');
    wp_register_script('underscore', $bower_components . 'underscore/underscore-min.js', '1.7.0');
    wp_register_script('datetimepicker', $bower_components . 'datetimepicker/jquery.datetimepicker.js', array('jquery'), '2.4.1');
    wp_register_script('sweetalert', $bower_components . 'sweetalert/lib/sweet-alert.min.js', array(), '0.3.3');

    $deps = array(
        'jquery',
        'jquery-maskedinputs',
        'underscore',
        'datetimepicker',
        'sweetalert'
    );

    wp_enqueue_script('fieldwork', plugins_url('dist/fieldwork.min.js', __FILE__), $deps);
    wp_enqueue_style('fieldwork', plugins_url('dist/fieldwork.css', __FILE__));
});

add_action('plugins_loaded', function () {

    do_action('fieldwork-register-forms');

});