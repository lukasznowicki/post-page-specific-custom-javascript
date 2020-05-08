<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings;


use Phylax\Plugin\CustomJS\Settings\Fields\InputCheckBox;
use Phylax\Plugin\CustomJS\Settings\Fields\InputRadioGroup;
use Phylax\Plugin\CustomJS\Settings\Fields\InputURL;
use Phylax\Plugin\CustomJS\Settings\Fields\jQuery;
use Phylax\Plugin\CustomJS\Settings\Fields\jQueryCDN;
use Phylax\Plugin\CustomJS\Settings\Fields\jQueryList;
use Phylax\Plugin\CustomJS\Settings\Fields\RegisteredScripts;
use Phylax\Plugin\CustomJS\Settings\Fields\Textarea;
use Phylax\Plugin\CustomJS\Settings\Fields\ToggleCheckBox;
use const Phylax\Plugin\CustomJS\TEXT_DOMAIN;

class Register {

	const SECTION_EDITOR_HIGHLIGHTING = 'cjs_editor_highlighting';

	const FIELD_HIGHLIGHTING = 'cjs_highlighting';

	const FIELD_HIGHLIGHT_IN_SETTINGS = 'cjs_hl_settings';
	const FIELD_HIGHLIGHT_IN_POSTS = 'cjs_hl_posts';
	const FIELD_HIGHLIGHT_IN_PAGES = 'cjs_hl_pages';
	const FIELD_HIGHLIGHT_IN_WOOCOMMERCE_PRODUCT = 'cjs_hl_wc_product';

	const SECTION_EDITOR_SIZES = 'cjs_editor_sizes';

	const FIELD_VIEW_SIZE = 'cjs_sizes';

	const FIELD_SIZE_SETTINGS = 'in_settings';
	const FIELD_SIZE_POST = 'in_post';
	const FIELD_SIZE_PAGE = 'in_page';
	const FIELD_SIZE_WOOCOMMERCE_PRODUCT = 'in_woocommerce_product';

	const SECTION_ALLOW_TO = 'cjs_allow_to';

	const SECTION_DEFAULT_CODE = 'cjs_default_code';

	const FIELD_DEFAULT_VALUE = 'cjs_defaults';

	const FIELD_DEFAULT_POST = 'for_post';
	const FIELD_DEFAULT_PAGE = 'for_page';
	const FIELD_DEFAULT_WC_PRODUCT = 'for_wc_product';
	const FIELD_DEFAULT_POST_FL = 'for_post_fl';
	const FIELD_DEFAULT_PAGE_FL = 'for_page_fl';
	const FIELD_DEFAULT_WC_PRODUCT_FL = 'for_wc_product_fl';

	const SECTION_LIBRARIES = 'cjs_libraries';

	const FIELD_LIBRARIES = 'cjs_libraries';

	const FIELD_FL_ALLOW = 'allow';
	const FIELD_FL_USE = 'use';
	const FIELD_FL_URL = 'url';

	public function __construct() {
		add_action( 'admin_init', function () {
			// Admin Editor
			add_settings_section( self::SECTION_EDITOR_HIGHLIGHTING, __( 'Code highlighting', TEXT_DOMAIN ), function () {
				echo '<p class="description">' . __( 'You may set the input fields to highlight the JavaScript code. Please beware, that it may slow down your browser a bit on outdated computers. It is not dangerous, just be aware.', TEXT_DOMAIN ) . '</p>';
			}, Page::PLUGIN_SETTINGS_SLUG );
			$this->addHighlightingFields();
			add_settings_section( self::SECTION_EDITOR_SIZES, __( 'Input box sizes', TEXT_DOMAIN ), function () {
				echo '<p class="description">' . __( 'You may set the size of the input fields. Default is normal.', TEXT_DOMAIN ) . '</p>';
			}, Page::PLUGIN_SETTINGS_SLUG );
			$this->addSizeFields();
			add_settings_section( self::SECTION_ALLOW_TO, __( 'Allow plugin to', TEXT_DOMAIN ), function () {
				echo '<p class="description">' . __( 'Allow plugin to scan your WordPress installation to find if there are any other of my plugins installed or not. This is completely local and do not connect to any other 3rd party services.', TEXT_DOMAIN ) . '</p>';
				echo '<p class="description">' . __( 'You may also allow plugin to add dashboard panel to get news from me, about new plugins. This requires connection with my server, but you do not send any data, only receive a plain html file with information.', TEXT_DOMAIN ) . '</p>';
			}, Page::PLUGIN_SETTINGS_SLUG );
			// Default values
			add_settings_section( self::SECTION_DEFAULT_CODE, __( 'Default values', TEXT_DOMAIN ), function () {
				echo '<p class="description">' .
				     __( 'Here you can setup default values for JavaScript code on Posts, Pages and WooCommerce Product page.', TEXT_DOMAIN ) .
				     __( 'You may also setup default value for attaching JavaScript libraries and/or frameworks. Please be aware, that this may slow down your your site and avoid attaching unused files!', TEXT_DOMAIN ) .
				     '</p>';
			}, Page::PLUGIN_SETTINGS_SLUG );
			$this->addDefaultValuesFields();
			add_settings_section( self::SECTION_LIBRARIES, __( 'Libraries/Frameworks', TEXT_DOMAIN ), function () {
				echo '<p class="description">' .
				     __( 'Here you may decide to allow including internal or external JavaScript Libraries/Frameworks.', TEXT_DOMAIN ) .
				     '</p>';
			}, Page::PLUGIN_SETTINGS_SLUG );
			$this->addLibrariesFields();
		} );
	}

	public function addHighlightingFields() {
		add_settings_field( self::FIELD_HIGHLIGHT_IN_SETTINGS, __( 'Highlight code in...', TEXT_DOMAIN ), function () {
			( new InputCheckBox( '0', self::FIELD_HIGHLIGHTING, self::FIELD_HIGHLIGHT_IN_SETTINGS, __( '...the Settings Page (here)', TEXT_DOMAIN ), true ) )->view();
			( new InputCheckBox( '0', self::FIELD_HIGHLIGHTING, self::FIELD_HIGHLIGHT_IN_POSTS, __( '...Posts edit screen', TEXT_DOMAIN ), true ) )->view();
			( new InputCheckBox( '0', self::FIELD_HIGHLIGHTING, self::FIELD_HIGHLIGHT_IN_PAGES, __( '...Pages edit screen', TEXT_DOMAIN ), true ) )->view();
			( new InputCheckBox( '0', self::FIELD_HIGHLIGHTING, self::FIELD_HIGHLIGHT_IN_WOOCOMMERCE_PRODUCT, __( '...WooCommerce Product Pages', TEXT_DOMAIN ), true ) )->view();
		}, Page::PLUGIN_SETTINGS_SLUG, self::SECTION_EDITOR_HIGHLIGHTING );
		register_setting( Page::PLUGIN_SETTINGS_SLUG, self::FIELD_HIGHLIGHTING );
	}

	public function addSizeFields() {
		add_settings_field( self::FIELD_VIEW_SIZE, __( 'Set input view in...', TEXT_DOMAIN ), function () {
			( new InputRadioGroup( [
				__( 'Minimal', TEXT_DOMAIN ),
				__( 'Normal', TEXT_DOMAIN ),
				__( 'Bigger', TEXT_DOMAIN ),
				__( 'Very big', TEXT_DOMAIN ),
			], '1', self::FIELD_VIEW_SIZE, self::FIELD_SIZE_SETTINGS, __( '...Settings page:', TEXT_DOMAIN ), '', true, true ) )->view();
			( new InputRadioGroup( [
				__( 'Minimal', TEXT_DOMAIN ),
				__( 'Normal', TEXT_DOMAIN ),
				__( 'Bigger', TEXT_DOMAIN ),
				__( 'Very big', TEXT_DOMAIN ),
			], '1', self::FIELD_VIEW_SIZE, self::FIELD_SIZE_POST, __( '...Posts edit screen:', TEXT_DOMAIN ), '', true, true ) )->view();
			( new InputRadioGroup( [
				__( 'Minimal', TEXT_DOMAIN ),
				__( 'Normal', TEXT_DOMAIN ),
				__( 'Bigger', TEXT_DOMAIN ),
				__( 'Very big', TEXT_DOMAIN ),
			], '1', self::FIELD_VIEW_SIZE, self::FIELD_SIZE_PAGE, __( '...Pages edit screen:', TEXT_DOMAIN ), '', true, true ) )->view();
			( new InputRadioGroup( [
				__( 'Minimal', TEXT_DOMAIN ),
				__( 'Normal', TEXT_DOMAIN ),
				__( 'Bigger', TEXT_DOMAIN ),
				__( 'Very big', TEXT_DOMAIN ),
			], '1', self::FIELD_VIEW_SIZE, self::FIELD_SIZE_WOOCOMMERCE_PRODUCT, __( '...WooCommerce Product pages:', TEXT_DOMAIN ), '', true, true ) )->view();

		}, Page::PLUGIN_SETTINGS_SLUG, self::SECTION_EDITOR_SIZES );
		register_setting( Page::PLUGIN_SETTINGS_SLUG, self::FIELD_VIEW_SIZE );
	}

	public function addDefaultValuesFields() {
		add_settings_field( self::FIELD_DEFAULT_POST, __( 'Default values for...', TEXT_DOMAIN ), function () {
			$rows = ( new TranslateRows( self::FIELD_SIZE_SETTINGS ) )->get();
			echo '<div id="CustomJSDefaults">';
			( new Textarea( $rows, '', self::FIELD_DEFAULT_VALUE, self::FIELD_DEFAULT_POST, __( '...JavaScript in Posts:', TEXT_DOMAIN ), true,
				( new InputCheckBox( '0', self::FIELD_DEFAULT_VALUE, self::FIELD_DEFAULT_POST_FL, 'Include default Library/Framework if set', true ) )->get()
			) )->view();
			( new Textarea( $rows, '', self::FIELD_DEFAULT_VALUE, self::FIELD_DEFAULT_PAGE, __( '...JavaScript in Pages:', TEXT_DOMAIN ), true,
				( new InputCheckBox( '0', self::FIELD_DEFAULT_VALUE, self::FIELD_DEFAULT_PAGE_FL, 'Include default Library/Framework if set', true ) )->get()
			) )->view();
			( new Textarea( $rows, '', self::FIELD_DEFAULT_VALUE, self::FIELD_DEFAULT_WC_PRODUCT, __( '...JavaScript in WooCommerce Product Pages:', TEXT_DOMAIN ), true,
				( new InputCheckBox( '0', self::FIELD_DEFAULT_VALUE, self::FIELD_DEFAULT_WC_PRODUCT_FL, 'Include default Library/Framework if set', true ) )->get()
			) )->view();
			echo '</div>';
		}, Page::PLUGIN_SETTINGS_SLUG, self::SECTION_DEFAULT_CODE );
		register_setting( Page::PLUGIN_SETTINGS_SLUG, self::FIELD_DEFAULT_VALUE );
	}

	public function addLibrariesFields() {
		add_settings_field( self::FIELD_FL_ALLOW, __( 'Warning!', TEXT_DOMAIN ), function () {
			( new ToggleCheckBox( function () {
				//( new RegisteredScripts() )->get();
				( new InputRadioGroup( ( new jQuery() )->get(), 'wp-jquery', self::FIELD_LIBRARIES, self::FIELD_FL_USE, __( 'Use this library:', TEXT_DOMAIN ), '', true, false ) )->view();
				echo '<div class="cjsLibraryLink">';
				( new InputURL( '', self::FIELD_LIBRARIES, self::FIELD_FL_URL, __( 'Framework/Library URL:', TEXT_DOMAIN ), false, 'large-text' ) )->view();
				echo '<p class="description">' .
				     __( 'Please be very careful! Including external JavaScript Libraries and/or Frameworks may be dangerous to your site. Use only trusted sources!', TEXT_DOMAIN ) . ' ' .
				     __( 'You can use commonly trusted sources below, clicking on the button will complete the URL', TEXT_DOMAIN ) .
				     '</p>';
				echo '<p class="cjsInsertCDN">';
				$trustedSources = ( new jQueryCDN() )->get();
				foreach ( $trustedSources as $name => $value ) {
					echo '<button data-url="' . esc_attr( $value ) . '" class="button button-secondary cjsFillUrl">' . $name . '</button>';
				}
				echo '</p>';
				echo '</div>';
			}, '0', self::FIELD_LIBRARIES, self::FIELD_FL_ALLOW, __( 'Allow plugin to include JavaScript Libraries and/or Frameworks', TEXT_DOMAIN ), true ) )->view();

		}, Page::PLUGIN_SETTINGS_SLUG, self::SECTION_LIBRARIES );
		register_setting( Page::PLUGIN_SETTINGS_SLUG, self::FIELD_LIBRARIES );
	}
}