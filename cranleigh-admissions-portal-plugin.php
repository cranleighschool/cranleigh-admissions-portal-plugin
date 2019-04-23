<?php
/**
Plugin Name: Cranleigh Admissions Portal
Plugin URI: https://github.com/cranleighschool/cranleigh-admissions-portal-plugin
Description: Cranleigh Admissions Portal Plugin. The thing that bridges the gap between our websites and our portal.
Version: 1.4
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

