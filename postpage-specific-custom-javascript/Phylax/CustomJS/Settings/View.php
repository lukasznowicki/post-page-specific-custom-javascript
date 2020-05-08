<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings;


class View {

	public function do_settings_section( $page, $sectionName ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}
		if ( ! isset( $wp_settings_sections[ $page ][ $sectionName ] ) ) {
			return;
		}
		$section = $wp_settings_sections[ $page ][ $sectionName ];
		if ( $section['title'] ) {
			echo "<h2>{$section['title']}</h2>\n";
		}
		if ( $section['callback'] ) {
			call_user_func( $section['callback'], $section );
		}
		if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
			return;
		}
		echo '<table class="form-table" role="presentation">';
		do_settings_fields( $page, $section['id'] );
		echo '</table>';
	}

}