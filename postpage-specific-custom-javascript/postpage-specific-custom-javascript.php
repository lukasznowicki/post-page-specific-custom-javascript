<?php
/**
 * Plugin Name: Post/Page specific custom JavaScript
 * Plugin URI: https://wordpress.org/plugins/postpage-specific-custom-javascript/
 * Description: Post/Page specific custom JavaScript will allow you to add javascript code to specific posts/pages. It will give you special area in the post/page edit field to attach your JavaScript. It will also let you decide if this JS has to be added in multi-page/post view (like archive posts) or only in a single view. Additionally you will be able to decide if you want to attach jQuery or not.
 * Version: 0.1.0
 * Author: Łukasz Nowicki
 * Author URI: https://lukasznowicki.info/
 * Requires at least: 5.0
 * Requires PHP: 5.6
 * Tested up to: 5.4
 * Domain Path: /languages
 *
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS;

use Phylax\Autoloader;
use const DIRECTORY_SEPARATOR;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Phylax\Autoloader' ) ) {
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'Phylax' . DIRECTORY_SEPARATOR . 'Autoloader.php';
}

const TEXT_DOMAIN    = 'postpage-specific-custom-javascript';
const PLUGIN_VERSION = '0.1.0';
const PLUGIN_JQUERY_VERSION = '3.5.1';

const SUPPORT_LINK = 'https://wordpress.org/support/plugin/postpage-specific-custom-javascript/';
const RATING_LINK  = 'https://wordpress.org/support/plugin/postpage-specific-custom-javascript/reviews/#new-post';

const INSTAGRAM_LINK = 'https://www.instagram.com/dietagrubasa/';
const FACEBOOK_LINK  = 'https://www.facebook.com/GrubasFit/';
const YOUTUBE_LINK   = 'https://www.youtube.com/channel/UC5bNmVekWJTeKElhInF8neg';

const DONATION_LINK = 'https://paypal.me/lukasznowicki77';

define( __NAMESPACE__ . '\PLUGIN_FILE', __FILE__ );
define( __NAMESPACE__ . '\PLUGIN_URL', plugins_url( '/', __FILE__ ) );

new Autoloader( 'Phylax\Plugin\CustomJS', __DIR__ . DIRECTORY_SEPARATOR . 'Phylax' . DIRECTORY_SEPARATOR . 'CustomJS' );
new Plugin();

return;













/*
const PLUGIN_JQUERY_VERSION = '3.5.0';

const TEXT_DOMAIN          = 'phylaxppscjs';
const SETTINGS_PARENT_SLUG = 'options-general.php';

const PLUGIN_OPTIONS_SLUG = 'post-page-custom-js';

const SETTINGS_SECTION_OPTIONS = 'options';

const FIELD_OPTIONS_SYNTAX_HIGHLIGHT = 'options_page_syntax_highlight';
const FIELD_POST_SYNTAX_HIGHLIGHT    = 'default_post_syntax_highlight';
const FIELD_PAGE_SYNTAX_HIGHLIGHT    = 'default_page_syntax_highlight';

const FIELD_INPUT_SIZE_POSTS = 'input_size_posts';
const FIELD_INPUT_SIZE_PAGES = 'input_size_pages';

const FIELD_ALLOW_JQUERY = 'allow_jquery_group';

const FIELD_JQ_WORDPRESS = 'jquery_use_wordpress';
const FIELD_JQ_PLUGIN    = 'jquery_use_plugin';
const FIELD_JQ_CUSTOM    = 'jquery_use_custom';

const SETTINGS_SECTION_DEFAULTS = 'defaults';

const FIELD_DEFAULT_POST_CONTENT = 'default_new_post_js';
const FIELD_DEFAULT_PAGE_CONTENT = 'default_new_page_js';
const FIELD_DEFAULT_POST_JQUERY  = 'default_new_post_jquery';
const FIELD_DEFAULT_PAGE_JQUERY  = 'default_new_page_jquery';

const USE_JQUERY_NOT       = 0;
const USE_JQUERY_WORDPRESS = 1;
const USE_JQUERY_PLUGIN    = 2;
const USE_JQUERY_EXTERNAL  = 3;

class old_Plugin {

	public function __construct() {
		add_action( 'admin_init', function () {
			* Load text domain to allow translations *
			load_plugin_textdomain( 'phylaxppscjs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		} );
		add_action( 'admin_enqueue_scripts', function ( $pageId ) {
			if ( 'settings_page_' . PLUGIN_OPTIONS_SLUG !== $pageId ) {
				return;
			}
			wp_enqueue_script( PLUGIN_OPTIONS_SLUG . '_js', plugins_url( 'assets/js/plugin.js', __FILE__ ), [ 'jquery' ], null, true );
			wp_enqueue_style( PLUGIN_OPTIONS_SLUG . '_css', plugins_url( 'assets/css/plugin.css', __FILE__ ), '', null );
		} );
		add_action( 'admin_menu', function () {
			/* Add options page to the "Settings" page *
			add_submenu_page(
				SETTINGS_PARENT_SLUG,
				__( 'Post/Page specific custom JavaScript code', TEXT_DOMAIN ),
				__( 'Post/Page JS', TEXT_DOMAIN ),
				'manage_options',
				PLUGIN_OPTIONS_SLUG,
				function () {
					/* View the options page *
					?>
                    <div id="<?php echo PLUGIN_OPTIONS_SLUG; ?>_wrap" class="wrap">
                        <h1><?php echo get_admin_page_title(); ?></h1>
                        <form action="options.php" method="post">
							<?php
							settings_fields( PLUGIN_OPTIONS_SLUG );
							do_settings_sections( PLUGIN_OPTIONS_SLUG );
							submit_button();
							?>
                        </form>
                    </div>
					<?php
				}
			);
		} );
		add_action( 'admin_init', function () {
			/* Register settings for this plugin *

			add_settings_section( SETTINGS_SECTION_OPTIONS, __( 'Options', TEXT_DOMAIN ), function () {
				echo '<p>' . __( 'Here you can setup plugin behavior. Please note, that allowing JavaScript to run on archive pages or including external, plugin and internal jQuery at once (let us say, few posts on archive page, all with different jQuery type) <strong>may crash your site</strong> and will not work properly. Choose one standard and keep to that.', TEXT_DOMAIN ) . '</p>';
				echo '<hr>';
				$plugins = get_plugins();

				$cssPluginKey       = 'postpage-specific-custom-css/post-page-specific-custom-css.php';
				$cssReviewLink      = 'https://wordpress.org/support/plugin/postpage-specific-custom-css/reviews/#new-post';
				$jsPluginKey        = 'postpage-specific-custom-js/post-page-specific-custom-js.php';
				$jsReviewLink       = 'https://wordpress.org/support/plugin/postpage-specific-custom-js/reviews/#new-post';
				$cssPluginInstalled = false;
				$cssPluginActivated = false;
				$isMyBirthday       = false;
				if ( isset( $plugins[ $cssPluginKey ] ) ) {
					$cssPluginInstalled = true;
					if ( in_array( $cssPluginKey, get_option( 'active_plugins', [] ) ) ) {
						$cssPluginActivated = true;
					}
				}
				if ( '0502' === date( 'md' ) ) {
					$isMyBirthday = true;
				}

				$isMyBirthday = true;

				if ( $cssPluginInstalled && $cssPluginActivated && ( ! $isMyBirthday ) ) {
					return;
				}
				$announcement = '<div id="post-page-custom-js_announcement">';
				if ( $isMyBirthday ) {
					$announcement .= '<div class="pjs_birthday dismiss"><div class="pjs_birthday_wrapper">' .
					                 __( 'Hello!<br>It is my birthday :)', TEXT_DOMAIN ) . ' ' .
					                 sprintf( __( 'I hope I just turned %d...', TEXT_DOMAIN ), ( ( (int) date( 'Y' ) ) - 1977 ) ) . ' ' .
					                 __( 'Such day happens one time a year, so please forgive me bothering you here. I hope you like this plugin and you are happy using that one.', TEXT_DOMAIN ) . ' ' .
					                 __( 'I wrote this plugin for free and it will remain free, do not worry.', TEXT_DOMAIN ) . ' ' .
					                 __( 'I just want to ask you, maybe you can consider', TEXT_DOMAIN ) .
					                 '<ul>' .
					                 '<li>' . sprintf( __( '<a href="%s">Writing a review</a> and rank my plugin?', TEXT_DOMAIN ), $jsReviewLink ) . '</li>' .
					                 '<li>' . sprintf( __( 'Donate me few Euros (account %s)', TEXT_DOMAIN ), 'BIC/SWIFT ALBPPLPW, No: PL48249010570000990243895083' ) . '</li>' .
					                 '<li>' . sprintf( __( 'Donate me few Dollars (account %s)', TEXT_DOMAIN ), 'BIC/SWIFT ALBPPLPW, No: PL04249010570000990143895083' ) . '</li>' .
					                 '<li>' . sprintf( __( 'Donate me few Pounds (account %s)', TEXT_DOMAIN ), 'BIC/SWIFT ALBPPLPW, No: PL39249010570000990443895083' ) . '</li>' .
					                 '<li>' . sprintf( __( 'Donate me few CHFs (account %s)', TEXT_DOMAIN ), 'BIC/SWIFT ALBPPLPW, No: PL92249010570000990343895083' ) . '</li>' .
					                 '<li>' . sprintf( __( 'Donate me few PLNs (account %s)', TEXT_DOMAIN ), 'BIC/SWIFT ALBPPLPW, No: 57249010570000990043895083' ) . '</li>' .
					                 '</ul>' .
					                 '<p>' . __( 'Any of above would be great :)', TEXT_DOMAIN ) . '</p>' .
					                 '<div id="pjs_nextYear">' . __( 'Yeah, cool, remaind me next year', TEXT_DOMAIN ) . '</div>' .
					                 '</div></div>';
				}
				$announcement .= '</div>';
				echo $announcement;
			}, PLUGIN_OPTIONS_SLUG );

			add_settings_field( FIELD_OPTIONS_SYNTAX_HIGHLIGHT, __( 'Code highlight', TEXT_DOMAIN ), function () {
				$this->fieldCheckbox( FIELD_OPTIONS_SYNTAX_HIGHLIGHT, __( 'Enable code highlighting for fields on settings page', TEXT_DOMAIN ), 0, true );
				$this->fieldCheckbox( FIELD_POST_SYNTAX_HIGHLIGHT, __( 'Enable code highlighting for Posts fields', TEXT_DOMAIN ), 0, true );
				$this->fieldCheckbox( FIELD_PAGE_SYNTAX_HIGHLIGHT, __( 'Enable code highlighting for Pages fields', TEXT_DOMAIN ), 0, true );
				echo '<p class="description">' . __( '<strong>Warning</strong> Please consider that on weaker computers, enabling CSS highlighting may slow you down.', TEXT_DOMAIN ) . '</p>';
			}, PLUGIN_OPTIONS_SLUG, SETTINGS_SECTION_OPTIONS );
			add_settings_field( FIELD_INPUT_SIZE_POSTS, __( 'Size of the input boxes', TEXT_DOMAIN ), function () {
				echo '<div>' . __( 'For posts:', TEXT_DOMAIN ) . '</div>';
				echo '<div class="pjs_ml2 pjs_mb1">';
				$this->radioSection( [
					'key'       => FIELD_INPUT_SIZE_POSTS,
					'default'   => '1',
					'paragraph' => true,
					'values'    => [
						0 => __( 'Minimal size', TEXT_DOMAIN ),
						1 => __( 'Normal size', TEXT_DOMAIN ),
						2 => __( 'Big size', TEXT_DOMAIN ),
						3 => __( 'Very big size', TEXT_DOMAIN ),
					],
				] );
				echo '</div>';
				echo '<div>' . __( 'For pages:', TEXT_DOMAIN ) . '</div>';
				echo '<div class="pjs_ml2">';
				$this->radioSection( [
					'key'       => FIELD_INPUT_SIZE_PAGES,
					'default'   => '1',
					'paragraph' => true,
					'values'    => [
						0 => __( 'Minimal size', TEXT_DOMAIN ),
						1 => __( 'Normal size', TEXT_DOMAIN ),
						2 => __( 'Big size', TEXT_DOMAIN ),
						3 => __( 'Very big size', TEXT_DOMAIN ),
					],
				] );
				echo '</div>';
			}, PLUGIN_OPTIONS_SLUG, SETTINGS_SECTION_OPTIONS );

			add_settings_field( FIELD_ALLOW_JQUERY, __( 'Allow inlcuding jQuery', TEXT_DOMAIN ), function () {
				$this->fieldCheckbox( FIELD_ALLOW_JQUERY, __( 'Enable', TEXT_DOMAIN ), 0, true, 'cJS_jQueryOptions' );
				echo '<div id="cJS_jQueryOptions" class="pjs_ml2">';
				echo '<p>' . __( 'Allow using following jQuery libraries', TEXT_DOMAIN ) . '</p>';
				$this->fieldCheckbox( FIELD_JQ_WORDPRESS, __( 'Use internal WordPress jQuery library', TEXT_DOMAIN ), 1, true );
				$this->fieldCheckbox( FIELD_JQ_PLUGIN, sprintf( __( 'Use plugins jQuery library (current version %s), suppressing WordPress internal library', TEXT_DOMAIN ), PLUGIN_JQUERY_VERSION ), 0, true );
				$this->fieldCheckbox( FIELD_JQ_CUSTOM, __( 'Use custom jQuery url, suppressing WordPress internal library. It can be CDN, existing theme/plugin library or even other library than WordPress', TEXT_DOMAIN ), 0, true, 'cJS_jQueryURL' );
				echo '<div id="cJS_jQueryURL" class="pjs_ml2 pjs_mt1">';
				echo '<p class="description">' . __( 'This field is intended to provide valid external jQuery URL. Of course is up to you, which one JavaScript library/framework you will include here. You may even add more than one library/framework by putting their links separated by comma, space or semicolon. Please, just be careful.', TEXT_DOMAIN ) . '</p>';
				echo '<p><label for=""></label></p>';
				echo '<p><input class="large-text code" type="text" value="" placeholder="https://code.jquery.com/jquery-3.5.0.min.js"></p>';
				echo '<p class="description"><span class="pjs_inline_warning">' . __( '<strong>Warning</strong> Please be very careful when adding JavaScript libraries from external sources!', TEXT_DOMAIN ) . '</span></p>';
				echo '</div>';
				echo '</div>';
			}, PLUGIN_OPTIONS_SLUG, SETTINGS_SECTION_OPTIONS );

			add_settings_section( SETTINGS_SECTION_DEFAULTS, __( 'Defaults', TEXT_DOMAIN ), function () {
				echo '<p>' . __( 'You can set the pre-filled content for your newly created posts or pages. You may also choose, if the jQuery should be added by default (and which way is the default one). The last option will work only if you enable jQuery in the Settings page of course.', TEXT_DOMAIN ) . '</p>';
				echo '<p>' . __( 'Do not attach any &lt;script&gt markup, it will be added automatically. Just raw, pure JavaScript. Maybe with some library and/or framework, of course. Did I mention you should be careful?', TEXT_DOMAIN ) . '</p>';
			}, PLUGIN_OPTIONS_SLUG );
			add_settings_field( FIELD_DEFAULT_POST_CONTENT, $this->getLabel( FIELD_DEFAULT_POST_CONTENT, __( 'Default javascript for new posts', TEXT_DOMAIN ) ), function () {
				$this->fieldTextarea( FIELD_DEFAULT_POST_CONTENT );
				echo '<p class="description">' . __( 'Default jQuery adding method, if applicable:', TEXT_DOMAIN ) . '</p>';
				$this->fieldJQSelect( FIELD_DEFAULT_POST_JQUERY );
			}, PLUGIN_OPTIONS_SLUG, SETTINGS_SECTION_DEFAULTS );
			add_settings_field( FIELD_DEFAULT_PAGE_CONTENT, $this->getLabel( FIELD_DEFAULT_PAGE_CONTENT, __( 'Default javascript for new posts', TEXT_DOMAIN ) ), function () {
				$this->fieldTextarea( FIELD_DEFAULT_PAGE_CONTENT );
				echo '<p class="description">' . __( 'Default jQuery adding method, if applicable:', TEXT_DOMAIN ) . '</p>';
				$this->fieldJQSelect( FIELD_DEFAULT_PAGE_JQUERY );
			}, PLUGIN_OPTIONS_SLUG, SETTINGS_SECTION_DEFAULTS );

			register_setting( PLUGIN_OPTIONS_SLUG, PLUGIN_OPTIONS_SLUG );
		} );
	}

	public function fieldCheckbox( string $fieldName, string $label = '', $default_value = '', bool $paragraph = false, string $toggleId = '' ) {
		$value = (int) ( ( get_option( PLUGIN_OPTIONS_SLUG, [] )[ $fieldName ] ) ?? $default_value );
		if ( $paragraph ) {
			echo '<p>';
		}
		echo '<input type="hidden" name="' . $this->createName( $fieldName ) . '" value="0">';
		echo $this->getLabel( $fieldName, '', true ) . '<input id="' . $this->createId( $fieldName ) . '" name="' . $this->createName( $fieldName ) . '" type="checkbox" value="1" ' . checked( 1, $value, false );
		if ( '' !== $toggleId ) {
			echo ' data-cjstoggle="' . $toggleId . '"';
		}
		echo '> ' . $label . '</label>';
		if ( $paragraph ) {
			echo '</p>';
		}
	}

	public function createName( string $fieldName ): string {
		return PLUGIN_OPTIONS_SLUG . '[' . $fieldName . ']';
	}

	public function getLabel( string $fieldName, string $body = '', bool $openOnly = false ): string {
		$content = '<label for="' . $this->createId( $fieldName ) . '">';
		if ( $openOnly ) {
			return $content;
		}

		return $content . ' ' . $body . '</label>';
	}

	public function createId( string $fieldName ): string {
		return PLUGIN_OPTIONS_SLUG . '_' . $fieldName;
	}

	public function radioSection( array $inputs ) {
		$content = '';
		$value   = ( ( get_option( PLUGIN_OPTIONS_SLUG, [] )[ $inputs['key'] ] ) ?? $inputs['default'] );
		foreach ( $inputs['values'] as $fieldId => $fieldLabel ) {
			if ( $inputs['paragraph'] ) {
				$content .= '<p>';
			}
			$content .= $this->getLabel( $inputs['key'] . $fieldId, '', true );
			$content .= '<input type="radio" id="' . $this->createId( $inputs['key'] . $fieldId ) . '" name="' . $this->createName( $inputs['key'] ) . '" value="' . $fieldId . '" ' . checked( $fieldId, $value, false ) . '> ' . $fieldLabel . '</label>';
			if ( $inputs['paragraph'] ) {
				$content .= '</p>';
			}
		}
		echo $content;
	}

	public function fieldTextarea( string $fieldName ) {
		$value = (string) ( ( get_option( PLUGIN_OPTIONS_SLUG, [] )[ $fieldName ] ) ?? '' );
		echo '<textarea id="' . $this->createId( $fieldName ) . '" name="' . $this->createName( $fieldName ) . '" class="large-text code" rows="10" cols="50">' . esc_textarea( $value ) . '</textarea>';
	}

	public function fieldJQSelect( string $fieldName ) {
		$value = (int) ( ( get_option( PLUGIN_OPTIONS_SLUG, [] )[ $fieldName ] ) ?? 0 );
		$items = [
			USE_JQUERY_NOT       => __( 'Do not use jQuery by default', TEXT_DOMAIN ),
			USE_JQUERY_WORDPRESS => __( 'Use internal WordPress jQuery', TEXT_DOMAIN ),
			USE_JQUERY_PLUGIN    => sprintf( __( 'Use plugins internal jQuery (v%s)', TEXT_DOMAIN ), PLUGIN_JQUERY_VERSION ),
			USE_JQUERY_EXTERNAL  => __( 'Use external jQuery', TEXT_DOMAIN ),
		];
		echo '<p><select id="' . $this->createId( $fieldName ) . '" name="' . $this->createName( $fieldName ) . '" class="">';
		foreach ( $items as $oId => $oLabel ) {
			echo '<option value="' . $oId . '"' . ( ( $oId === $value ) ? ' selected="selected"' : '' ) . '>' . $oLabel . '</option>';
		}
		echo '</select>';
	}

}

/*
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *


class oldPlugin {

	public $menu_slug = 'post-page-custom-js';
	public $menu_parent_slug = 'options-general.php';
	public $option_group = 'ppcs_settings_group';
	public $option_name = 'ppcs_settings_name';

	public function __construct() {
		add_action( 'init', [
			$this,
			'init',
		] );
		add_filter( 'the_content', [
			$this,
			'the_content',
		] );
		if ( is_admin() ) {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [
				$this,
				'page_settings_link_filter',
			] );
			add_action( 'add_meta_boxes', [
				$this,
				'add_meta_boxes',
			] );
			add_action( 'save_post', [
				$this,
				'save_post',
			] );
			add_action( 'admin_menu', [
				$this,
				'add_options_page',
			] );
			add_action( 'admin_init', [
				$this,
				'register_settings',
			] );
			add_action( 'admin_enqueue_scripts', [
				$this,
				'admin_enqueue_scripts',
			] );
		}
	}

	public function options_admin_enqueue_scripts() {
		wp_enqueue_code_editor( [ 'type' => 'text/javascript' ] );
	}

	public function admin_enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( false === is_a( $screen, 'WP_Screen' ) ) {
			return;
		}
		if ( 'post' !== $screen->base ) {
			return;
		}
		$field = '';
		if ( ( $screen->id === 'post' ) && ( $screen->post_type === 'post' ) ) {
			$field = 'enable_highlighting_in_posts';
		}
		if ( ( $screen->id === 'page' ) && ( $screen->post_type === 'page' ) ) {
			$field = 'enable_highlighting_in_pages';
		}
		if ( '' === $field ) {
			return;
		}
		$settings = (array) get_option( $this->option_name );
		$value    = (int) ( $settings[ $field ] ?? 0 );
		if ( 1 === $value ) {
			wp_enqueue_code_editor( [
				'type'       => 'text/javascript',
				'codemirror' => [
					'autoRefresh' => true,
				],
			] );
		}
	}

	public function register_settings() {
		register_setting( $this->option_group, $this->option_name );
		add_settings_section( 'plugin-behavior', __( 'Options', 'phylaxppscjs' ), [
			$this,
			'section_plugin_behavior',
		], $this->menu_slug );
		add_settings_section( 'default-values', __( 'Default values', 'phylaxppscjs' ), [
			$this,
			'section_default_values',
		], $this->menu_slug );
		add_settings_field( 'default_post_js', __( 'Default javascript for new posts', 'phylaxppscjs' ), [
			$this,
			'default_post_js',
		], $this->menu_slug, 'default-values' );
		add_settings_field( 'default_page_js', __( 'Default javascript for new pages', 'phylaxppscjs' ), [
			$this,
			'default_page_js',
		], $this->menu_slug, 'default-values' );
		add_settings_field( 'enable_highlighting_in', __( 'Code highlight', 'phylaxppscjs' ), [
			$this,
			'enable_highlighting_in',
		], $this->menu_slug, 'plugin-behavior' );
		add_settings_field( 'bigger_textarea', __( 'Bigger input field', 'phylaxppscjs' ), [
			$this,
			'bigger_textarea',
		], $this->menu_slug, 'plugin-behavior' );
		add_settings_field( 'include_jquery', __( 'Include jQuery', 'phylaxppscjs' ), [
			$this,
			'includeJQuery'
		], $this->menu_slug, 'plugin-behavior' );
	}

	public function includeJQuery() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'include_jquery';
		$value    = (int) ( $settings[ $field ] ?? 0 );
		?>
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php echo __( 'Include jQuery framework', 'phylaxppscjs' ); ?></span>
            </legend>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Include jQuery framework', 'phylaxppscjs' ); ?>
            </label>
        </fieldset>
		<?php
	}

	public function bigger_textarea() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'bigger_textarea';
		$value    = (int) ( $settings[ $field ] ?? 0 );
		?>
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php echo __( 'Make input boxes bigger', 'phylaxppscjs' ); ?></span>
            </legend>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Make input boxes on Posts and Pages bigger', 'phylaxppscjs' ); ?>
            </label>
        </fieldset>
		<?php
	}

	public function enable_highlighting_in() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'enable_highlighting_in_settings';
		$value    = (int) ( $settings[ $field ] ?? 0 );
		?>
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php echo __( 'Enable code highlighting', 'phylaxppscjs' ); ?></span>
            </legend>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Enable code highlighting for fields on settings page', 'phylaxppscjs' ); ?>
            </label>
            <br>
			<?php
			$field = 'enable_highlighting_in_posts';
			$value = (int) ( $settings[ $field ] ?? 0 );
			?>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Enable code highlighting for Posts fields', 'phylaxppscjs' ); ?>
            </label>
            <br>
			<?php
			$field = 'enable_highlighting_in_pages';
			$value = (int) ( $settings[ $field ] ?? 0 );
			?>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Enable code highlighting for Pages fields', 'phylaxppscjs' ); ?>
            </label>
        </fieldset>
        <p class="description"><?php echo __( '<strong>Warning</strong> Please consider that on weaker computers, enabling JS highlighting may slow you down.', 'phylaxppscjs' ); ?></p>
		<?php
	}

	public function default_post_js() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'default_post_js';
		$value    = wp_unslash( $settings[ $field ] ?? '' );
		?>
        <fieldset>
            <label class="screen-reader-text" for="defaultPostJS">
                <span><?php echo __( 'Default javascript for new posts', 'phylaxppscjs' ); ?></span>
            </label>
            <p>
                <textarea id="defaultPostJS" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                          class="large-text code" rows="10" cols="50"><?php echo $value; ?></textarea>
            </p>
        </fieldset>
		<?php
	}

	public function default_page_js() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'default_page_js';
		$value    = wp_unslash( $settings[ $field ] ?? '' );
		?>
        <fieldset>
            <label class="screen-reader-text" for="defaultPageJS">
                <span><?php echo __( 'Default javascript for new pages', 'phylaxppscjs' ); ?></span>
            </label>
            <p>
                <textarea id="defaultPageJS" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                          class="large-text code" rows="10" cols="50"><?php echo $value; ?></textarea>
            </p>
        </fieldset>
		<?php
	}

	public function section_default_values() {
		?>
        <p>
			<?php echo __( 'You can set the pre-filled content for your newly created posts or pages.', 'phylaxppscjs' ); ?>
        </p>
		<?php
	}

	public function section_plugin_behavior() {
	}

	public function page_settings_link_filter( $links ) {
		if ( ! is_array( $links ) ) {
			$links = [];
		}
		$links[] = '<a href="' . $this->build_settings_link() . '">' . __( 'Settings', 'phylaxppscjs' ) . '</a>';

		return $links;
	}

	private function build_settings_link() {
		return admin_url( $this->menu_parent_slug . '?page=' . $this->menu_slug );
	}

	public function add_options_page() {
		$sub_menu_suffix = add_submenu_page( $this->menu_parent_slug, __( 'Post/Page specific custom JS', 'phylaxppscjs' ), __( 'Post/Page JS', 'phylaxppscjs' ), 'manage_options', $this->menu_slug, [
			$this,
			'options_page_view',
		] );
		$settings        = (array) get_option( $this->option_name );
		$field           = 'enable_highlighting_in_settings';
		$value           = (int) ( $settings[ $field ] ?? 0 );
		if ( 1 === $value ) {
			add_action( 'load-' . $sub_menu_suffix, [
				$this,
				'options_admin_enqueue_scripts',
			] );
		}
	}

	public function options_page_view() {

		?>
        <div class="wrap">
            <h1><?php echo __( 'Post/Page Custom JS', 'phylaxppscjs' ); ?></h1>
            <form action="options.php" method="POST">
				<?php settings_fields( $this->option_group ); ?>
                <div>
					<?php do_settings_sections( $this->menu_slug ); ?>
                </div>
				<?php submit_button(); ?>
            </form>
        </div>
		<?php
		$settings = (array) get_option( $this->option_name );
		$field    = 'enable_highlighting_in_settings';
		$value    = (int) ( $settings[ $field ] ?? 0 );
		if ( 1 === $value ) :
			?>
            <script>
                jQuery(function ($) {
                    var defaultPageJS = $('#defaultPageJS');
                    var defaultPostJS = $('#defaultPostJS');
                    var editorSettings;
                    var editor;
                    if (defaultPageJS.length === 1) {
                        editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                        editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                            indentUnit: 2, tabSize: 2, mode: 'javascript',
                        });
                        editor = wp.codeEditor.initialize(defaultPageJS, editorSettings);
                    }
                    if (defaultPostJS.length === 1) {
                        editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                        editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                            indentUnit: 2, tabSize: 2, mode: 'javascript',
                        });
                        editor = wp.codeEditor.initialize(defaultPostJS, editorSettings);
                    }
                });
            </script>
		<?php
		endif;
	}

	public function the_content( $content ) {
		if ( isset( $GLOBALS['post'] ) ) {
			$post_id                   = $GLOBALS['post']->ID;
			$phylax_ppscjs_single_only = get_post_meta( $post_id, '_phylax_ppscjs_single_only', true );
			$phylax_ppscjs_js          = get_post_meta( $post_id, '_phylax_ppscjs_js', true );
			if ( '' != $phylax_ppscjs_js ) {
				# mamy JS!
				if ( is_single() || is_page() ) {
					$content = $this->join( $content, $phylax_ppscjs_js );
				} elseif ( '0' == $phylax_ppscjs_single_only ) {
					$content = $this->join( $content, $phylax_ppscjs_js );
				}
			}
		}

		return $content;
	}

	public function join( $content, $js ) {
		return '<!-- ' . __( 'Added by Post/Page specific custom JS plugin, thank you for using!', 'phylaxppscjs' ) . ' -->' . PHP_EOL . '<script>' . $js . '</script>' . PHP_EOL . $content;
	}

	public function add_meta_boxes() {
		if ( current_user_can( 'manage_options' ) ) {
			add_meta_box( 'phylax_ppscjs', __( 'Custom JS', 'phylaxppscjs' ), [
				$this,
				'render_phylax_ppscjs',
			], [
				'post',
				'page',
			], 'advanced', 'high' );
		}
	}

	public function save_post( $post_id ) {
		$test_id = (int) $post_id;
		if ( $test_id < 1 ) {
			return $post_id;
		}
		if ( ! isset( $_POST['phylax_ppscjs_nonce'] ) ) {
			return $post_id;
		}
		$nonce = $_POST['phylax_ppscjs_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'phylax_ppscjs' ) ) {
			return $post_id;
		}
		if ( ( 'page' != $_POST['post_type'] ) && ( 'post' != $_POST['post_type'] ) ) {
			return $post_id;
		}
		if ( ( 'post' == $_POST['post_type'] ) && ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		if ( ( 'page' == $_POST['post_type'] ) && ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && \DOING_AUTOSAVE ) {
			return $post_id;
		}
		$phylax_ppscjs_js          = trim( strip_tags( $_POST['phylax_ppscjs_js'] ) );
		$phylax_ppscjs_single_only = (int) $_POST['phylax_ppscjs_single_only'];
		if ( ( $phylax_ppscjs_single_only < 0 ) || ( $phylax_ppscjs_single_only > 1 ) ) {
			$phylax_ppscjs_single_only = 0;
		}
		update_post_meta( $post_id, '_phylax_ppscjs_js', $phylax_ppscjs_js );
		update_post_meta( $post_id, '_phylax_ppscjs_single_only', $phylax_ppscjs_single_only );
	}

	public function render_phylax_ppscjs( $post ) {
		wp_nonce_field( 'phylax_ppscjs', 'phylax_ppscjs_nonce' );
		$screen   = '';
		$settings = (array) get_option( $this->option_name );
		switch ( $post->post_type ) {
			case 'post':
				$field  = 'enable_highlighting_in_posts';
				$dField = 'default_page_js';
				$screen = __( 'Post custom JS', 'phylaxppscjs' );
				break;
			case 'page':
				$field  = 'enable_highlighting_in_pages';
				$dField = 'default_post_js';
				$screen = __( 'Page custom JS', 'phylaxppscjs' );
				break;
		}
		if ( '' == $screen ) {
			return;
		}
		$enable_highlighting = (int) ( $settings[ $field ] ?? 0 );
		$post_meta           = get_post_meta( $post->ID );
		$brand_new           = false;
		if ( false === isset( $post_meta['_phylax_ppscjs_js'] ) ) {
			$brand_new = true;
		}
		$phylax_ppscjs_js = get_post_meta( $post->ID, '_phylax_ppscjs_js', true );
		if ( ( '' === $phylax_ppscjs_js ) && ( true === $brand_new ) ) {
			$phylax_ppscjs_js .= (string) ( $settings[ $dField ] );
		}
		$phylax_ppscjs_single_only = get_post_meta( $post->ID, '_phylax_ppscjs_single_only', true );
		if ( '' == $phylax_ppscjs_single_only ) {
			$phylax_ppscjs_single_only = 0;
		}
		if ( $phylax_ppscjs_single_only ) {
			$checked = ' checked="checked"';
		} else {
			$checked = '';
		}
		$biggerBox = (int) ( $settings['bigger_textarea'] ?? 0 );
		?>
        <p class="post-attributes-label-wrapper">
            <label for="phylax_ppscjs_js"><?php echo $screen; ?></label>
        </p>
        <div id="phylax_ppscjs_js_outer">
            <textarea name="phylax_ppscjs_js" id="phylax_ppscjs_js" class="widefat textarea"
                      rows="<?php echo( ( 0 === $biggerBox ) ? '10' : '25' ) ?>"><?php echo esc_textarea( $phylax_ppscjs_js ); ?></textarea>
        </div>
        <p class="post-attributes-label-wrapper">
            <label for="phylax_ppscjs_single_only"><input type="hidden" name="phylax_ppscjs_single_only"
                                                          value="0"><input type="checkbox"
                                                                           name="phylax_ppscjs_single_only" value="1"
                                                                           id="phylax_ppscjs_single_only"<?php echo $checked; ?>> <?php echo __( 'Attach this JS code only on single page view', 'phylaxppscjs' ); ?>
            </label>
        </p>
        <p>
			<?php
			echo __( 'Please add only valid JS code, it will be placed between &lt;script&gt; tags.', 'phylaxppscjs' ); ?>
        </p>
		<?php
		if ( $enable_highlighting ) :
			?>
            <script>
                jQuery(function ($) {
                    var phylaxJSEditorDOM = $('#phylax_ppscjs_js');
                    var phylaxJSEditorSettings;
                    var phylaxJSEditorInstance;
                    if (phylaxJSEditorDOM.length === 1) {
                        phylaxJSEditorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                        phylaxJSEditorSettings.codemirror = _.extend({}, phylaxJSEditorSettings.codemirror, {
                            indentUnit: 2, tabSize: 2, mode: 'javascript',
                        });
                        phylaxJSEditorInstance = wp.codeEditor.initialize(phylaxJSEditorDOM, phylaxJSEditorSettings);
                        //console.log( 'Next', phylaxJSEditorDOM.next('.CodeMirror').find('.CodeMirror-code') );
                        $(document).on('keyup', '#phylax_ppscjs_js_outer .CodeMirror-code', function () {
                            console.clear();
                            phylaxJSEditorDOM.html(phylaxJSEditorInstance.codemirror.getValue());
                            phylaxJSEditorDOM.trigger('change');
                        });
                    }
                });
            </script>
			<?php
			if ( 1 === $biggerBox ) :
				?>
                <style>
                    #phylax_ppscjs_js_outer .CodeMirror {
                        height: 600px;
                    }
                </style>
			<?php
			endif;
		endif;
	}

	public function init() {
		load_plugin_textdomain( 'phylaxppscjs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	private function text() {
		__( '', 'phylaxppscjs' );
	}

}

new Plugin();
*/