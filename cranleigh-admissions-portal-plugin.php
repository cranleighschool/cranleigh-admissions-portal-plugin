<?php
/*
Plugin Name: Cranleigh Admissions Portal
Plugin URI: https://github.com/cranleighschool/admissions-portal
Description: Cranleigh Admissions Portal Plugin
Version: 1.0
Author: fredbradley
Author URI: https://www.cranleigh.org
License: GPL2
*/

namespace FredBradley\CranleighAdmissionsPlugin;

if ( ! defined( 'WPINC' ) ) {
	die;
}
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

$plugin = new Plugin();

