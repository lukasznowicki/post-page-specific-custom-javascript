<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


use const Phylax\Plugin\CustomJS\PLUGIN_JQUERY_VERSION;
use const Phylax\Plugin\CustomJS\TEXT_DOMAIN;

class jQuery {

	public $jQuery;

	public function get() {
		/** @var \WP_Scripts $wp_scripts */
		global $wp_scripts;
		if (
			! isset( $wp_scripts ) ||
			! is_a( $wp_scripts, 'WP_Scripts' ) ||
			! isset( $wp_scripts->registered ) ||
			! is_array( $wp_scripts->registered ) ||
			! isset( $wp_scripts->registered['jquery'] ) ||
			! is_a( $wp_scripts->registered['jquery'], '_WP_Dependency' )
		) {
			return [];
		}

		/** @var \_WP_Dependency jQuery */
		$this->jQuery = $wp_scripts->registered['jquery'];
		$sVersion     = $sDeps = '';
		if ( '' !== $this->jQuery->ver ) {
			$sVersion = ' <span class="jsiVersion">v' . $this->jQuery->ver . '</span>';
		}
		$depCount = count( $this->jQuery->deps );
		if ( $depCount > 0 ) {
			$sDeps = ' <span class="jsiDeps">';
			$sDeps .= sprintf( _n( '%s Dependency', '%s dependencies', $depCount, TEXT_DOMAIN ), $depCount );
			$sDeps .= '</span>';
			$sDeps .= '<span class="jsiDepsList">';
			foreach ( $this->jQuery->deps as $depName ) {
				$sDeps .= '<span>' . ( new ScriptName( $depName ) )->get() . '</span>';
			}
			$sDeps .= '</span>';
		}
		$item = [
			'handle' => $this->jQuery->handle,
			'name'   => __( 'Internal jQuery Library', TEXT_DOMAIN ) . $sVersion . $sDeps,
		];

		return [
			'wp-jquery'     => $item['name'],
			'plugin-jquery' => __( 'jQuery Library provided by this plugin', TEXT_DOMAIN ) .
			                   ' <span class="jsiVersion">v' . PLUGIN_JQUERY_VERSION .
			                   '</span><span class="jsiSuppress">' .
			                   __( 'Suppressing internal WordPress jQuery version', TEXT_DOMAIN ) .
			                   '</span><span class="jsiInfoBox">' .
			                   __( 'Please note, that disabling internal WordPress jQuery may break your site, depending on your plugins and/or theme. If some of your plugins or theme is using internal WordPress jQuery, then it may be broken.', TEXT_DOMAIN ) .
			                   '</span>',
			'_custom'       => __( 'You may introduce your own URL to the JavaScript Framework/Library', TEXT_DOMAIN ),
		];
	}

}