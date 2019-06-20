<?php

namespace FredBradley\CranleighAdmissionsPlugin\Shortcodes;

use FredBradley\CranleighAdmissionsPlugin\Mappers\Document;
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
	public $tag = 'admissions_doc';

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
	public function render( $atts, $content = null ): string {

		$this->admissions_portal_uri = Settings::get( 'portal_uri' );

		$a = shortcode_atts(
			[
				'slug' => null,
				'url'  => null,
			],
			$atts
		);

		if ( null === $a['slug'] && null === $a['url'] ) {
			return 'Error. You need to set a SLUG or URL';
		} elseif ( null !== $a['url'] ) {
			return $this->oembed( $a['url'] );
		} elseif ( null !== $a['slug'] ) {
			return $this->oembed( $a['slug'] );
		}
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	private function oembed( string $str ): string {

		$transient_name = 'admis_portal_doc_' . sanitize_title( $str );
		$transient      = get_transient( $transient_name );

		if ( false === $transient ) {
			if ( substr( $str, 0, 8 ) != 'https://' ) {
				$str = $this->admissions_portal_uri . 'documents/' . $str;
			}

			$data = @file_get_contents( $str . '/oembed.json' );
			if ( false === $data ) {
				$error = error_get_last();

				return "<div class='alert alert-danger'><p>Could not find the Admissions Document: <code>" . $str . '</code>.</p></div>';
			} else {
				$transient = new Document( $data );
				set_transient( $transient_name, $transient, WEEK_IN_SECONDS );
			}
		}

		return $this->html( $transient );
	}


	/**
	 * @param Document $document
	 *
	 * @return string
	 */
	private function html( Document $document ): string {

		ob_start();
		?>
		<aside class="card landscape card-download">
			<div class="row">
				<div class="col-sm-4">
					<div class="card-image">
						<a href="<?php echo $document->show_route; ?>">
							<img src="<?php echo $document->thumbnail; ?>">
						</a>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="card-text">

						<h4>
							<a href="<?php echo $document->show_route; ?>" style="user-select: none;"><?php echo $document->title; ?></a>
						</h4>

						<p><?php echo $document->description; ?></p>

						<a class="btn btn-sm btn-primary" title="" href="<?php echo $document->show_route; ?>" rel="nofollow" style="user-select: none;">
							Download
							<small>(<?php echo $document->file_size; ?>)</small>
						</a>
						<?php if ( current_user_can( 'manage_options' ) ) { ?>
							<a class="btn btn-sm btn-danger" href="<?php echo $document->admin_edit; ?>">Edit Download</a>
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
