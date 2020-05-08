<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class Textarea extends Input {

	public $addContent;
	public $rows;

	public function __construct( $rows, $default, $option, $id, $label, $paragraph = true, $addContent = '' ) {
		$this->default    = $default;
		$this->option     = $option;
		$this->id         = $id;
		$this->label      = $label;
		$this->paragraph  = $paragraph;
		$this->index      = '';
		$this->addContent = $addContent;
		$this->rows = $rows;
	}

	public function get() {
		$this->setStoredValue();
		$item = '';
		$item .= '<div class="defaultValuesItem">';
		$item .= $this->paragraphOpen();
		$item .= $this->labelOpen();
		$item .= $this->labelClose();
		$item .= $this->paragraphClose();
		$item .= '<div class="dvInputWrapper">';
		$item .= '<textarea id="' . $this->getId() . '" name="' . $this->getName() . '" rows="' . $this->rows . '">';
		$item .= esc_textarea( $this->storedValue );
		$item .= '</textarea>';
		$item .= '</div>';
		$item .= $this->addContent;
		$item .= '</div>';

		return $item;
	}

}