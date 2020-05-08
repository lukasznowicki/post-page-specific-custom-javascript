<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS;

use Phylax\Plugin\CustomJS\Settings\Page;
use Phylax\Plugin\CustomJS\Settings\Register;

class Plugin {

	public $textDomainLoaded = false;

	public $ajax;
	public $menuPage;
	public $options;

	public function __construct() {
		$this->ajax     = new Ajax();
		$this->menuPage = new Page();
		$this->options  = new Register();
		add_action( 'init', [ $this, 'loadTextDomain' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( PLUGIN_FILE ), function ( $links ) {

			return $links;
		} );
	}

	public function loadTextDomain() {
		if ( ! load_plugin_textdomain( TEXT_DOMAIN, false, dirname( plugin_basename( PLUGIN_FILE ) ) . '/languages/' ) ) {
			new Log( 'Error loading text-domain language file' );
		}
	}

}