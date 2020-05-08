<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class InputRadioGroup {

	public $groupLabel;
	public $description;
	public $paragraph;
	public $items;
	public $default;
	public $option;
	public $index;
	public $smaller;

	public function __construct( $items, $default, $option, $index, $groupLabel = '', $description = '', $paragraph = false, $smaller = false ) {
		$this->items       = $items;
		$this->option      = $option;
		$this->groupLabel  = $groupLabel;
		$this->description = $description;
		$this->default     = $default;
		$this->index       = $index;
		$this->paragraph   = $paragraph;
		$this->smaller     = $smaller;
	}

	public function view() {
		echo $this->get();
	}

	public function get() {
		if ( ! is_array( $this->items ) || ( 0 === count( $this->items ) ) ) {
			return '';
		}
		$item = '';
		$item .= '<div class="group' . ( ( is_bool( $this->smaller ) && $this->smaller ) ? ' colSplit' : '' ) . '">';
		if ( is_string( $this->groupLabel ) && ( '' !== $this->groupLabel ) ) {
			$item .= '<h3>' . $this->groupLabel . '</h3>';
		}
		if ( is_string( $this->description ) && ( '' !== $this->description ) ) {
			$item .= '<p class="description">' . $this->description . '</p>';
		}
		$item .= '<div class="wrapper">';
		foreach ( $this->items as $key => $value ) {
			$item .= ( new InputRadio( $key, $this->default, $this->option, $this->index, '', $value, $this->paragraph ) )->get();
		}
		$item .= '</div>';
		$item .= '</div>';

		return $item;
	}
}