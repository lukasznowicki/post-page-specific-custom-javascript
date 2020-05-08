<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class InputCheckBox extends Input {

	public function __construct( $default, $option, $id, $label = '', $paragraph = false ) {
		$this->default   = $default;
		$this->option    = $option;
		$this->id        = $id;
		$this->label     = $label;
		$this->paragraph = $paragraph;
		$this->index     = '';
		$this->value     = '1';
	}

	public function get() {
		$this->setStoredValue();
		$item = '';
		$item .= $this->paragraphOpen();
		$item .= $this->labelOpen();
		$item .= $this->tag( 'input', [
			'type'  => 'hidden',
			'name'  => $this->getName(),
			'value' => '0',
		] );
		$item .= $this->tag( 'input', [
			'id'      => $this->getId(),
			'type'    => 'checkbox',
			'name'    => $this->getName(),
			'value'   => '1',
			'checked' => ( '1' === (string) $this->storedValue ) ? 'checked' : null,
		] );
		$item .= $this->labelClose();
		$item .= $this->paragraphClose();

		return $item;
	}

}