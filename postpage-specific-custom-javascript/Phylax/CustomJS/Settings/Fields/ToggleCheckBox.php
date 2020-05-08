<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class ToggleCheckBox extends Input {

	public $content;

	public function __construct( $content, $default, $option, $id, $label = '', $paragraph = false ) {
		$this->content   = $content;
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
		$item    = '';
		$item    .= $this->paragraphOpen();
		$item    .= $this->labelOpen( '' );
		$item    .= $this->tag( 'input', [
			'type'  => 'hidden',
			'name'  => $this->getName(),
			'value' => '0',
		] );
		$item    .= $this->tag( 'input', [
			'id'      => $this->getId(),
			'type'    => 'checkbox',
			'name'    => $this->getName(),
			'value'   => '1',
			'class'   => 'cjsToggleById',
			'checked' => ( '1' === (string) $this->storedValue ) ? 'checked' : null,
		] );
		$item    .= $this->labelClose();
		$item    .= $this->paragraphClose();
		$display = 'display:block;';
		if ( '0' === $this->storedValue ) {
			$display = 'display:none;';
		}
		$item .= $this->tag( 'div', [
			'id'    => $this->getId() . '_toggle',
			'class' => 'toggleContent',
			'style' => $display,
		] );
		if ( is_string( $this->content ) ) {
			echo $this->content;
		} elseif ( is_callable( $this->content ) ) {
			ob_start();
			call_user_func( $this->content );
			$item .= ob_get_clean();
		}
		$item .= '</div>';

		return $item;
	}

}