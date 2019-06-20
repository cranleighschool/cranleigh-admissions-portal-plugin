<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 2019-04-02
 * Time: 11:38
 */

namespace FredBradley\CranleighAdmissionsPlugin\Mappers;

/**
 * Class Document
 *
 * @package FredBradley\CranleighAdmissionsPlugin\Mappers
 */
class Document {

	/**
	 * Document constructor.
	 *
	 * @param string $json_string
	 */
	public function __construct( string $json_string ) {

		$obj = json_decode( $json_string );

		foreach ( get_object_vars( $obj ) as $obj_name => $var_value ) {
			$this->{$this->from_camel_case( $obj_name )} = $var_value;

		}

	}

	/**
	 * Converts CamelCase strings into snake_case style.
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	private function from_camel_case( string $input ) {

		preg_match_all( '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches );
		$ret = $matches[0];
		foreach ( $ret as &$match ) {
			if ( strtoupper( $match ) == $match ) {
				$match = strtolower( $match );
			} else {
				$match = lcfirst( $match );
			}
		}

		return implode( '_', $ret );
	}
}
