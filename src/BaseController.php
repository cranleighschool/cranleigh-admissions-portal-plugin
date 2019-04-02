<?php

namespace FredBradley\CranleighAdmissionsPlugin;

use Puc_v4_Factory;

/**
 * Class BaseController
 */
abstract class BaseController {


	/**
	 * @return mixed
	 */
	abstract public function setup_plugin();

	/**
	 * BaseController constructor.
	 */
	public function __construct() {

		$this->setup_plugin();
	}


	/**
	 * @param string $plugin_name
	 * @param string $github_user_name
	 */
	public function run_update_checker( string $plugin_name, string $github_user_name = 'cranleighschool' ) {

		return $this->update_check( $plugin_name, $github_user_name );
	}

	/**
	 * @param string $plugin_name
	 * @param string $user
	 */
	private function update_check( string $plugin_name, string $user ) {

		$update_checker = Puc_v4_Factory::buildUpdateChecker(
			'https://github.com/' . $user . '/' . $plugin_name . '/',
			dirname( dirname( __FILE__ ) ) . '/' . $plugin_name . '.php',
			$plugin_name
		);

		/* Add in option form for setting auth token*/
		//$update_checker->setAuthentication(GITHUB_AUTH_TOKEN);

		$update_checker->setBranch( 'master' );
	}

}

