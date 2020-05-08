<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings;


use const Phylax\Plugin\CustomJS\DONATION_LINK;use const Phylax\Plugin\CustomJS\FACEBOOK_LINK;use const Phylax\Plugin\CustomJS\INSTAGRAM_LINK;use const Phylax\Plugin\CustomJS\PLUGIN_FILE;use const Phylax\Plugin\CustomJS\PLUGIN_VERSION;use const Phylax\Plugin\CustomJS\RATING_LINK;use const Phylax\Plugin\CustomJS\SUPPORT_LINK;use const Phylax\Plugin\CustomJS\TEXT_DOMAIN;use const Phylax\Plugin\CustomJS\YOUTUBE_LINK;

class Page {

	const MENU_SETTINGS_SLUG = 'options-general.php';
	const PLUGIN_SETTINGS_SLUG = 'post-page-custom-js';

	public $pageHandler;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script( 'phylax-custom-javascript-js', plugins_url( 'assets/js/plugin.js', PLUGIN_FILE ), [ 'jquery-ui-tabs' ], PLUGIN_VERSION, true );
			wp_localize_script( 'phylax-custom-javascript-js', 'CustomJS', [
				'alerts' => [
					'doNotShowBirthday' => __( 'Ok, I got it! Please check the confirmation mark and I will proceed. Sorry to hear anyway.', TEXT_DOMAIN ),
				],
				'nonces' => [
				],
			] );
			wp_enqueue_style( 'phylax-custom-javascript-style', plugins_url( 'assets/css/plugin.css', PLUGIN_FILE ), [], PLUGIN_VERSION );
		} );
		add_action( 'admin_menu', function () {
			$this->pageHandler = add_submenu_page(
				self::MENU_SETTINGS_SLUG,
				__( 'Post/Page specific custom JavaScript', TEXT_DOMAIN ),
				__( 'Post/Page JavaScript', TEXT_DOMAIN ),
				'manage_options',
				self::PLUGIN_SETTINGS_SLUG,
				function () {
					$viewActions = new View();
					?>
                    <div class="wrap">
                        <h1><?php echo get_admin_page_title(); ?></h1>
                        <p class="description"><?php
			                echo __( 'Configure your Custom Javascript plugin to best fulfill your needs. Be careful, some of these settings can make your site not as secure as you expect. This applies to attaching external JavaScript files. Choose the sources you use wisely!', TEXT_DOMAIN );
			            ?></p>
                        <hr>
                        <?php $this->donationScreen((new ShowDonation())->show()); ?>
                    <form action="options.php" method="post">
						<?php settings_fields( self::PLUGIN_SETTINGS_SLUG ); ?>
                        <div id="optionTabsContainer">
                            <div id="optionTabs">
                                <ul>
                                    <li><a href="#adminEditor"><?php echo __( 'Admin Page', TEXT_DOMAIN ); ?></a></li>
                                    <li><a href="#defaultValues"><?php echo __( 'Default values', TEXT_DOMAIN ); ?></a></li>
                                    <li><a href="#frameworks"><?php echo __( 'Libraries/Frameworks', TEXT_DOMAIN ); ?></a></li>
                                    <li><a href="#about"><?php echo __( 'About', TEXT_DOMAIN ); ?></a></li>
                                </ul>
                                <div id="adminEditor" class="optionTabsBody">
									<?php $viewActions->do_settings_section( self::PLUGIN_SETTINGS_SLUG, Register::SECTION_ALLOW_TO ); ?>
									<?php $viewActions->do_settings_section( self::PLUGIN_SETTINGS_SLUG, Register::SECTION_EDITOR_HIGHLIGHTING ); ?>
									<?php $viewActions->do_settings_section( self::PLUGIN_SETTINGS_SLUG, Register::SECTION_EDITOR_SIZES ); ?>
                                </div>
                                <div id="defaultValues" class="optionTabsBody">
									<?php $viewActions->do_settings_section( self::PLUGIN_SETTINGS_SLUG, Register::SECTION_DEFAULT_CODE ); ?>
                                </div>
                                <div id="frameworks" class="optionTabsBody">
                                    <?php $viewActions->do_settings_section(self::PLUGIN_SETTINGS_SLUG, Register::SECTION_LIBRARIES ); ?>
                                </div>
                                <div id="about" class="optionTabsBody">
                                    <h2><?php echo __('Post/Page specific custom JavaScript', TEXT_DOMAIN ); ?></h2>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                    <p><?php echo __('', TEXT_DOMAIN ); ?></p>
                                </div>
                            </div>
                            <div class="textright submit">
                                <p class="description"><?php echo __( 'Please note, that by clicking "Save changes" button, all options on all available tabs will be saved, not only the visible ones.', TEXT_DOMAIN ); ?></p>
                                <p><?php submit_button( __( 'Save changes', TEXT_DOMAIN ), 'primary', 'submit', false ); ?></p>
                            </div>
                        </div>
                    </form>
					<?php
				}
			);
		} );
	}

	public function donationScreen( $showDonation = false ) {
		$current = wp_get_current_user();
		$yo      = (int) ( date( 'Y' ) - 1977 );
		?>
        <div id="donation">
            <div id="dAvatar">
            </div>
            <div id="dDescription">
                <h4><?php echo sprintf( 'Hello %s, lovely to meet you. My name is Łukasz Nowicki.', $current->display_name ); ?></h4>
                <p><?php
					echo sprintf( __( 'I am %d y.o. WordPress/Full stack developer from Poland. ', TEXT_DOMAIN ), $yo ) . ' ';
					echo sprintf( __( 'If you have any question or problems with the plugin, do not wait, contact me on <a href="%s">support forum</a>.', TEXT_DOMAIN ), SUPPORT_LINK ) . ' ';
					echo sprintf( __( 'If you are happy with the plugin, please consider <a href="%s">rating the plugin</a>, your opinion is important to me.', TEXT_DOMAIN ), RATING_LINK ) . ' ';
					?></p>
					<?php if ( $showDonation ) : ?>
                <p><?php
					echo __( 'If you got a moment, I would like to present my story :) I weighed 230.6 kg (508.4 lb), I couldn\'t move, I couldn\'t walk. I could walk a few meters, then I had to rest.', TEXT_DOMAIN ) . ' ';
					echo __( 'Thanks to the help of my fiancee (current wife), we started to fight. The wife began reading all scientific books about the diet and began to prepare me the right meals.', TEXT_DOMAIN ) . ' ';
					echo __( 'I started to exercise. At the beginning they were walking. Then I joined the trot, swimming pool, gym. I practice more and more, I used to be a martial arts instructor.', TEXT_DOMAIN ) . ' ';
					echo sprintf( __( 'I started running an <a href="%1$s">Instagram account</a>, <a href="%2$s">Facebook page</a> and <a href="%3$s">YouTube channel</a>.', TEXT_DOMAIN ), INSTAGRAM_LINK, FACEBOOK_LINK, YOUTUBE_LINK ) . ' ';
					echo __( 'If you are non-polish speaker, then Instagram account is the best if you want to join and watch my fight. I also try to translate every post into English.', TEXT_DOMAIN ) . ' ';
					?></p>
                <p><?php
					echo __( 'I have been writing plugins for WordPress for many years. Part of it is a professional job, I also devote a lot of time to writing free plugins.', TEXT_DOMAIN ) . ' ';
					echo __( 'If I am not asking too much, if you can afford it, please support my collection for better exercise equipment and video recording equipment.', TEXT_DOMAIN ) . ' ';
					echo __( 'Thank you in advance for each payment, it helps me a lot.', TEXT_DOMAIN ) . ' ';
					?></p>
                <p><?php
					echo sprintf( __( 'You may donate using <a class="payPalLink" href="%s">Pay Pal link</a> or using wire transfer:', TEXT_DOMAIN ), DONATION_LINK ) . ' ';
					$bankName = 'Alior Bank S.A.';
					$accounts = [
						'us' => [
							's'   => 'USD',
							'bic' => 'ALBPPLPW',
							'acc' => 'PL04249010570000990143895083',
							'cur' => __( 'United States dollar - USD', TEXT_DOMAIN ),
						],
						'gb' => [ 's' => 'GBP', 'bic' => 'ALBPPLPW', 'acc' => 'PL39249010570000990443895083', 'cur' => __( 'Pound sterling - GBP', TEXT_DOMAIN ), ],
						'eu' => [ 's' => 'EUR', 'bic' => 'ALBPPLPW', 'acc' => 'PL48249010570000990243895083', 'cur' => __( 'Euro - EUR', TEXT_DOMAIN ), ],
						'ch' => [ 's' => 'CHF', 'bic' => 'ALBPPLPW', 'acc' => 'PL92249010570000990343895083', 'cur' => __( 'Swiss franc - CHF', TEXT_DOMAIN ), ],
						'pl' => [ 's' => 'PLN', 'bic' => 'ALBPPLPW', 'acc' => '57249010570000990043895083', 'cur' => __( 'Polish złoty - PLN', TEXT_DOMAIN ), ],
					];
					foreach ( $accounts as $flagId => $account ) {
						echo '<span class="theAccount">';
						echo '<a href="#" data-flag_to_show="#flagShow' . $flagId . '"><span class="theFlag flag-' . $flagId . '"></span> ' . $account['s'] . '</a>';
						echo '</span>';
						//echo sprintf( __( '', TEXT_DOMAIN ), 'a' ) . ' ';
					}
					echo __( 'I really appreciate all your efforts to support me!', TEXT_DOMAIN ) . ' ';
					?></p>
                <div id="flagContainer">
					<?php foreach ( $accounts as $flagId => $account ) {
						echo '<p id="flagShow' . $flagId . '" class="theFlagShow"><span class="theFlag flag-' . $flagId . '"></span> ' . $account['s'] . '<span class="aInfo">Bank: <em class="bankName">' . $bankName . '</em>, BIC/SWIFT: <em class="BICSwift">' . $account['bic'] . '</em>, No: <em class="accountNumber">' . $account['acc'] . '</em>, Currency: <em class="accountCurrency">' . $account['cur'] . '</em></span></p>';
					}
					?>
                </div>
                <?php endif; ?>
            </div>
        </div>
		<?php
	}

}