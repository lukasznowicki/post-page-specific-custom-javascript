<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class InputText extends Input {

	public $inputClass;

	public function __construct( $default, $option, $id, $label = '', $paragraph = false, $inputClass = null ) {
		$this->default    = $default;
		$this->option     = $option;
		$this->id         = $id;
		$this->label      = $label;
		$this->paragraph  = $paragraph;
		$this->index      = '';
		$this->value      = '1';
		$this->inputClass = $inputClass;
	}

	public function get() {
		return $this->standardInput( 'text' );
	}

}