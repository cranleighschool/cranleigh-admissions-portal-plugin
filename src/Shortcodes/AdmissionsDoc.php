<?php

namespace FredBradley\CranleighAdmissionsPlugin\Shortcodes;

use FredBradley\CranleighAdmissionsPlugin\Settings;

/**
 * Class AdmissionsDoc
 *
 * @package FredBradley\CranleighAdmissionsPlugin\Shortcodes
 */
class AdmissionsDoc extends ShortcodeController {

	/**
	 * @var string
	 */
	public $tag = "admissions_doc";

	/**
	 * @var string
	 */
	public $admissions_portal_uri;

	/**
	 * @param array $atts
	 * @param null  $content
	 *
	 * @return string
	 */
	public function render( $atts, $content = null ) {

		$this->admissions_portal_uri = Settings::get( 'portal_uri' );

		$a = shortcode_atts( [
			'slug' => null,
			'url'  => null,
		], $atts );

		if ( $a[ 'slug' ] === null && $a[ 'url' ] === null ) {
			return 'Error. You need to set a SLUG or URL';
		} elseif ( $a[ 'url' ] !== null ) {
			return $this->oembed( $a[ 'url' ] );
		} elseif ( $a[ 'slug' ] !== null ) {
			return $this->oembed( $a[ 'slug' ] );
		}
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	private function oembed( string $str ) {

		if ( substr( $str, 0, 8 ) != 'https://' ) {
			$str = $this->admissions_portal_uri . "documents/" . $str;
		}

		if ( ! $data = @file_get_contents( $str . "/oembed.json" ) ) {
			$error = error_get_last();

			return "<div class='alert alert-danger'><p>Could not find Admissions Document: <code>" . $str . "</code>.</p></div>";
		} else {
			$json = json_decode( $data );

			return $this->html( $json );
		}


	}

	/**
	 * @param $document
	 *
	 * @return string
	 */
	private function html( $document ) {

		ob_start();
		?>
		<aside class="card landscape card-download">
			<div class="row">
				<div class="col-sm-4">
					<div class="card-image">
						<a href="<?php echo $document->showRoute; ?>" style="user-select: none;">
							<img src="<?php echo $document->thumbnail; ?>" style="user-select: none;">
						</a>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="card-text">

						<h4>
							<a href="<?php echo $document->showRoute; ?>" style="user-select: none;"><?php echo $document->title; ?></a>
						</h4>

						<p><?php echo $document->description; ?></p>

						<a class="btn btn-sm btn-primary" title="" href="<?php echo $document->showRoute; ?>" rel="nofollow" style="user-select: none;">
							Download
							<small>(<?php echo $document->fileSize; ?>)</small>
						</a>
						<?php if ( current_user_can( 'manage_options' ) ) { ?>
							<a class="btn btn-sm btn-danger" href="<?php echo $document->adminEdit; ?>">Edit Download</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</aside>

		<?php
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}