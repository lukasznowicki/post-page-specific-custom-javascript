<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class InputRadio extends Input {

	public function __construct( $value, $defaultValue, $option, $id, $index, $label = '', $paragraph = false ) {
		$this->option    = $option;
		$this->id        = $id;
		$this->label     = $label;
		$this->paragraph = $paragraph;
		$this->index     = $index;
		$this->value     = $value;
		$this->default   = $defaultValue;
	}

	public function get() {
		$this->setStoredValue();
		$item = '';
		$item .= $this->paragraphOpen();
		$item .= $this->labelOpen( '_' . $this->value );
		$item .= '<input type="radio" id="' . $this->getId() . '_' . $this->value . '" name="' . $this->getName() . '" value="' . $this->value . '"' . checked( $this->value, $this->storedValue, false ) . '>';
		$item .= $this->labelClose();
		$item .= $this->paragraphClose();

		return $item;
	}

}