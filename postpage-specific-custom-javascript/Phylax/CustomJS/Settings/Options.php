<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings;


class Options {

	public $option;
	public $id;
	public $index;

	public function __construct( $option, $id = '', $index = '' ) {
		$this->option = $option;
		$this->id = $id;
		$this->index = $index;
	}

	public function get( $default = '' ) {
		$options = get_option( $this->option, [] );
		if ( '' === $this->id ) {
			return $options;
		}
		if ( '' === $this->index ) {
			$option = isset( $options[ $this->id ] ) ? $options[ $this->id ] : $default;
		} else {
			$option = isset( $options[ $this->id ] ) ? $options[ $this->id ] : [];
		}
		if ( '' === $this->index ) {
			return $option;
		}

		return isset( $options[ $this->id ][ $this->index ] ) ? $options[ $this->id ][ $this->index ] : $default;

	}

}