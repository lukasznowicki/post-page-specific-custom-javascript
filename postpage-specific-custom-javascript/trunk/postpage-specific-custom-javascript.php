<?php
/**
 * Plugin Name: Post/Page specific Custom Javascript
 * Plugin URI: https://wordpress.org/plugins/postpage-specific-custom-javascript/
 * Description: Post/Page specific Custom JavaScript will allow you to add javascript code to specific posts/pages. It will give you special area in the post/page edit field to attach your JS. It will also let you decide if this JS has to be added in multi-page/post view (like archive posts) or only in a single view (recommended).
 * Version: 0.1.1
 * Author: Łukasz Nowicki
 * Author URI: https://lukasznowicki.info/
 * Requires at least: 5.0
 * Requires PHP: 5.6
 * Tested up to: 5.4
 */

namespace Phylax\WPPlugin\PPCustomJS;

use const DOING_AUTOSAVE;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	public $menu_slug = 'post-page-custom-javascript';
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
		add_action( 'admin_notices', [ $this, 'adminNotices' ] );
	}

	public function options_admin_enqueue_scripts() {
		wp_enqueue_code_editor( [ 'type' => 'application/javascript' ] );
	}

	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( false === is_a( $screen, 'WP_Screen' ) ) {
			return;
		}
		if ( 'settings_page_post-page-custom-js' === $screen->id ) {
			$this->adminNotices();
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
		$value    = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
		if ( 1 === $value ) {
			wp_enqueue_code_editor( [
				'type'       => 'text/javascript',
				'codemirror' => [
					'autoRefresh' => true,
				],
			] );
		}
	}

	public function adminNotices() {
		$current_user   = wp_get_current_user();
		$transient_name = 'ppcs_lastview_' . $current_user->ID;
		$transient      = get_transient( $transient_name );
		$donation       = (string) ( isset( $_GET['donation'] ) ? $_GET['donation'] : '' );
		if ( ( false !== $transient ) && ( 'true' !== $donation ) ) {
			return;
		}
		set_transient( $transient_name, '1', WEEK_IN_SECONDS );
		add_action( 'admin_print_footer_scripts', function () {
		} );
		?>
        <div class="notice notice-success is-dismissible">
            <p><span class="dashicons dashicons-buddicons-groups"
                     style="font-size:48px;width:48px;height:48px;float:left;color:#0a0;margin-right:12px;"></span>
                <strong><?php echo sprintf( __( 'Hello %s!', 'postpage-specific-custom-javascript' ), $current_user->display_name ); ?></strong>
            </p>
            <p>
				<?php
				echo sprintf( __( /** @lang text */
						'I just think, maybe you want to <a href="%1$s">give me a review</a> for my plugin? Or consider some <a href="%2$s">small donation</a>?', 'postpage-specific-custom-javascript' ),
						'https://wordpress.org/support/plugin/postpage-specific-custom-javascript/reviews/#new-post',
						'https://paypal.me/lukasznowicki77'
				     ) . ' ';
				?>
            </p>
        </div>
		<?php
	}

	public function register_settings() {
		register_setting( $this->option_group, $this->option_name );
		add_settings_section( 'plugin-behavior', __( 'Options', 'postpage-specific-custom-javascript' ), function () {
		}, $this->menu_slug );
		add_settings_section( 'default-values', __( 'Default values', 'postpage-specific-custom-javascript' ), [
			$this,
			'section_default_values',
		], $this->menu_slug );
		add_settings_field( 'default_post_js', __( 'Default javascript for new posts', 'postpage-specific-custom-javascript' ), [
			$this,
			'default_post_js',
		], $this->menu_slug, 'default-values' );
		add_settings_field( 'default_page_js', __( 'Default javascript for new pages', 'postpage-specific-custom-javascript' ), [
			$this,
			'default_page_js',
		], $this->menu_slug, 'default-values' );
		add_settings_field( 'enable_highlighting_in', __( 'Code highlight', 'postpage-specific-custom-javascript' ), [
			$this,
			'enable_highlighting_in',
		], $this->menu_slug, 'plugin-behavior' );
		add_settings_field( 'bigger_textarea', __( 'Bigger input field', 'postpage-specific-custom-javascript' ), [
			$this,
			'bigger_textarea',
		], $this->menu_slug, 'plugin-behavior' );
	}

	public function bigger_textarea() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'bigger_textarea';
		$value    = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
		?>
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php echo __( 'Make input boxes bigger', 'postpage-specific-custom-javascript' ); ?></span>
            </legend>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Make input boxes on Posts and Pages bigger', 'postpage-specific-custom-javascript' ); ?>
            </label>
        </fieldset>
		<?php
	}

	public function enable_highlighting_in() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'enable_highlighting_in_settings';
		$value    = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
		?>
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php echo __( 'Enable code highlighting', 'postpage-specific-custom-javascript' ); ?></span>
            </legend>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Enable code highlighting for fields on settings page', 'postpage-specific-custom-javascript' ); ?>
            </label>
            <br>
			<?php
			$field = 'enable_highlighting_in_posts';
			$value = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
			?>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Enable code highlighting for Posts fields', 'postpage-specific-custom-javascript' ); ?>
            </label>
            <br>
			<?php
			$field = 'enable_highlighting_in_pages';
			$value = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
			?>
            <input type="hidden" name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]" value="0">
            <label for="item_<?php echo $field; ?>">
                <input id="item_<?php echo $field; ?>" type="checkbox"
                       name="<?php echo $this->option_name; ?>[<?php echo $field; ?>]"
                       value="1"<?php echo( ( $value === 1 ) ? ' checked="checked"' : '' ); ?>> <?php echo __( 'Enable code highlighting for Pages fields', 'postpage-specific-custom-javascript' ); ?>
            </label>
        </fieldset>
        <p class="description"><?php echo __( '<strong>Warning</strong> Please consider that on weaker computers, enabling JS highlighting may slow you down.', 'postpage-specific-custom-javascript' ); ?></p>
		<?php
	}

	public function default_post_js() {
		$settings = (array) get_option( $this->option_name );
		$field    = 'default_post_js';
		$value    = wp_unslash( (string) ( isset( $settings[ $field ] ) ? $settings[ $field ] : '' ) );
		?>
        <fieldset>
            <label class="screen-reader-text" for="defaultPostJS">
                <span><?php echo __( 'Default javascript for new posts', 'postpage-specific-custom-javascript' ); ?></span>
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
		$value    = wp_unslash( (string) ( isset( $settings[ $field ] ) ? $settings[ $field ] : '' ) );
		?>
        <fieldset>
            <label class="screen-reader-text" for="defaultPageJS">
                <span><?php echo __( 'Default javascript for new pages', 'postpage-specific-custom-javascript' ); ?></span>
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
			<?php echo __( 'You can set the pre-filled content for your newly created posts or pages.', 'postpage-specific-custom-javascript' ); ?>
        </p>
		<?php
	}

	public function page_settings_link_filter( $links ) {
		if ( ! is_array( $links ) ) {
			$links = [];
		}
		$links[] = '<a href="' . $this->build_settings_link() . '">' . __( 'Settings', 'postpage-specific-custom-javascript' ) . '</a>';
		$links[] = '<a href="' . $this->build_settings_link() . '&amp;donation=true">' . __( 'Donate', 'postpage-specific-custom-javascript' ) . '</a>';

		return $links;
	}

	private function build_settings_link() {
		return admin_url( $this->menu_parent_slug . '?page=' . $this->menu_slug );
	}

	public function add_options_page() {
		$sub_menu_suffix = add_submenu_page( $this->menu_parent_slug, __( 'Post/Page specific Custom JS', 'postpage-specific-custom-javascript' ), __( 'Post/Page JS', 'postpage-specific-custom-javascript' ), 'manage_options', $this->menu_slug, [
			$this,
			'options_page_view',
		] );
		$settings        = (array) get_option( $this->option_name );
		$field           = 'enable_highlighting_in_settings';
		$value           = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
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
            <h1><?php echo __( 'Post/Page Custom JS', 'postpage-specific-custom-javascript' ); ?></h1>
            <!--suppress HtmlUnknownTarget -->
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
		$value    = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
		if ( 1 === $value ) :
			?>
            <script>
                jQuery(function ($) {
                    const defaultPageJS = $('#defaultPageJS');
                    const defaultPostJS = $('#defaultPostJS');
                    let editorSettings;
                    if (defaultPageJS.length === 1) {
                        editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                        editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                            indentUnit: 2, tabSize: 2, mode: 'javascript',
                        });
                        wp.codeEditor.initialize(defaultPageJS, editorSettings);
                    }
                    if (defaultPostJS.length === 1) {
                        editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                        editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                            indentUnit: 2, tabSize: 2, mode: 'javascript',
                        });
                        wp.codeEditor.initialize(defaultPostJS, editorSettings);
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
				/** There is JS, we can work with it */
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
		$scriptHtml5 = current_theme_supports( 'html5', 'script' ) ? '' : ' type="text/javascript"';

		return '<!-- ' .
		       __( 'Added by Post/Page specific Custom JavaScript plugin, thank you for using!', 'postpage-specific-custom-javascript' ) .
		       ' -->' . PHP_EOL .
		       '<script' . $scriptHtml5 . '>' . $js . '</script>' . PHP_EOL .
		       $content;
	}

	public function add_meta_boxes() {
		if ( current_user_can( 'manage_options' ) ) {
			add_meta_box( 'phylax_ppscjs', __( 'Custom JavaScript Code', 'postpage-specific-custom-javascript' ), [
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
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		$phylax_ppscjs_js          = trim( strip_tags( $_POST['phylax_ppscjs_js'] ) );
		$phylax_ppscjs_single_only = (int) $_POST['phylax_ppscjs_single_only'];
		if ( ( $phylax_ppscjs_single_only < 0 ) || ( $phylax_ppscjs_single_only > 1 ) ) {
			$phylax_ppscjs_single_only = 0;
		}
		update_post_meta( $post_id, '_phylax_ppscjs_js', $phylax_ppscjs_js );
		update_post_meta( $post_id, '_phylax_ppscjs_single_only', $phylax_ppscjs_single_only );

		return $post_id;
	}

	public function render_phylax_ppscjs( $post ) {
		wp_nonce_field( 'phylax_ppscjs', 'phylax_ppscjs_nonce' );
		$screen   = '';
		$field    = '';
		$dField   = '';
		$settings = (array) get_option( $this->option_name );
		switch ( $post->post_type ) {
			case 'post':
				$field  = 'enable_highlighting_in_posts';
				$dField = 'default_post_js';
				$screen = __( 'Post Custom JS', 'postpage-specific-custom-javascript' );
				break;
			case 'page':
				$field  = 'enable_highlighting_in_pages';
				$dField = 'default_page_js';
				$screen = __( 'Page Custom JS', 'postpage-specific-custom-javascript' );
				break;
		}
		if ( '' == $screen ) {
			return;
		}
		$enable_highlighting = (int) ( isset( $settings[ $field ] ) ? $settings[ $field ] : 0 );
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
		$biggerBox = (int) ( isset( $settings['bigger_textarea'] ) ? $settings['bigger_textarea'] : 0 );
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
                                                                           id="phylax_ppscjs_single_only"<?php echo $checked; ?>> <?php echo __( 'Attach this JS code only on single page view', 'postpage-specific-custom-javascript' ); ?>
            </label>
        </p>
        <p>
			<?php
			echo __( 'Please add only valid JS code, it will be placed inside &lt;script&gt; tag.', 'postpage-specific-custom-javascript' ); ?>
        </p>
		<?php
		if ( $enable_highlighting ) :
			?>
            <script>
                jQuery(function ($) {
                    const phylaxJSEditorDOM = $('#phylax_ppscjs_js');
                    let phylaxJSEditorSettings,
                        phylaxJSEditorInstance;
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
                <!--suppress CssUnusedSymbol -->
                <style>#phylax_ppscjs_js_outer .CodeMirror {
                        height: 600px;
                    }</style>
			<?php
			endif;
		endif;
	}

	public function init() {
		load_plugin_textdomain( 'postpage-specific-custom-javascript', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function text() {
		__( 'Post/Page specific Custom JavaScript will allow you to add javascript code to specific posts/pages. It will give you special area in the post/page edit field to attach your JS. It will also let you decide if this JS has to be added in multi-page/post view (like archive posts) or only in a single view (recommended).', 'postpage-specific-custom-javascript' );
	}

}

new Plugin();