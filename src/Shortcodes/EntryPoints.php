<?php

namespace FredBradley\CranleighAdmissionsPlugin\Shortcodes;

use FredBradley\CranleighAdmissionsPlugin\Mappers\Document;
use FredBradley\CranleighAdmissionsPlugin\Settings;

/**
 * Class AdmissionsDoc
 *
 * @package FredBradley\CranleighAdmissionsPlugin\Shortcodes
 */
class EntryPoints extends ShortcodeController {


	/**
	 * @var string
	 */
	public $tag = 'admissions_entry_points';
	/**
	 * @var
	 */
	public $api_token;
	/**
	 * @var string
	 */
	public $api_version = 'v1';
	/**
	 * @var string
	 */
	public $admissions_portal_uri;

	public $image_path = 'images/entrypoints';

	/**
	 * @return string
	 */
	private function generateEntryPointsApiUri() {

		$query = http_build_query( [ 'api_token' => $this->api_token ] );
		$uri   = $this->admissions_portal_uri . 'api/' . $this->api_version . '/entry-points?' . $query;

		return $uri;
	}


	/**
	 * @return array|mixed|object|\WP_Error
	 */
	private function getResponse() {

		$get = wp_remote_get( $this->generateEntryPointsApiUri() );
		if ( 'application/json' !== $get[ 'headers' ][ 'content-type' ] ) {
			return new \WP_Error( 'Not JSON', 'Your Content Type Is Not JSON.' );
		}

		$body = wp_remote_retrieve_body( $get );

		return json_decode( $body );
	}

	/**
	 * @param $image_file_name
	 *
	 * @return string
	 */
	private function getImage( $image_file_name ) {

		return Settings::get( 'portal_uri' ) . trailingslashit( $this->image_path ) . $image_file_name;
	}

	/**
	 * @param array $atts
	 * @param null  $content
	 *
	 * @return string
	 */
	public function render( $atts, $content = null ) {

		$this->admissions_portal_uri = Settings::get( 'portal_uri' );
		$this->api_token             = Settings::get( 'api_token' );

		$response = $this->getResponse();

		if ( is_wp_error( $response ) ) {
			error_log( 'Failed to connect to the Admissions Portal. Check Portal URI and API TOKEN' );

			return false;
		} else {

			$entry_points = $this->getResponse();

		}

		return $this->html( $entry_points );
	}


	/**
	 * @param array $entry_points
	 *
	 * @return string
	 */
	private function html( array $entry_points ) {

		ob_start();
		?>
		<div class="was-a-well">
			<h3>Select an entry point for more information</h3>
			<div class="row">
				<?php
				foreach ( $entry_points as $entry_point ) :
					?>

					<div class="col-sm-3 col-xs-6">
						<a target="_blank" class="no-underline"
						   href="<?php echo $entry_point->ahref; ?>">
							<div class="card card-style-<?php echo $entry_point->cardStyle; ?>">
								<div class="card-image">
									<img src="<?php echo $this->getImage( $entry_point->image ); ?>"
										 class="img-responsive" />
								</div>
								<div class="card-title">
									<h3 class="text-center"><?php echo $entry_point->title; ?></h3>
									<h4 class="text-center">(<?php echo $entry_point->yearGroupModel->year_group->name; ?>)</h4>
								</div>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}
