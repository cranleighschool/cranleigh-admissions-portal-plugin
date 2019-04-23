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

	/**
	 * @var string
	 */
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

		$transient_name              = Settings::$transient_name;
		$transient                   = get_transient( $transient_name );
		$seconds_length_of_transient = WEEK_IN_SECONDS;

		if ( false === $transient ) {

			$remote = wp_remote_get( $this->generateEntryPointsApiUri() );
			if ( 'application/json' !== $remote[ 'headers' ][ 'content-type' ] ) {
				return new \WP_Error( 400, 'Your Content Type Is Not JSON.' );
			}

			$transient = wp_remote_retrieve_body( $remote );
			set_transient( $transient_name, $transient, $seconds_length_of_transient );
		} else {
			echo 'using transient';
		}

		return json_decode( $transient );
	}

	/**
	 * @param $image_file_name
	 *
	 * @deprecated 2019-04-23 We are now using blobs.
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

			$entry_points = $response;

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
									<img src="<?php echo $entry_point->imageBlob; ?>"
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
