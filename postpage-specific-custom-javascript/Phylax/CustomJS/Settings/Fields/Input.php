<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


use Phylax\Plugin\CustomJS\Settings\Options;

class Input {

	public $option;
	public $id;
	public $index;
	public $label;
	public $paragraph;
	public $default;
	public $value;
	public $storedValue;

	public function standardInput( $type ) {
		$this->setStoredValue();
		$item = '';
		$item .= $this->paragraphOpen();
		$item .= $this->labelOpen();
		$item .= '<span class="cjsBlock">' . $this->label . '</span>';
		$item .= $this->tag( 'input', [
			'id'    => $this->getId(),
			'type'  => $type,
			'name'  => $this->getName(),
			'value' => esc_attr( $this->storedValue ),
			'class' => $this->inputClass,
		] );
		$item .= $this->labelClose( false );
		$item .= $this->paragraphClose();

		return $item;
	}

	public function setStoredValue() {
		$this->storedValue = $this->getValue( $this->default );
	}

	public function getValue( $default = '' ) {
		return ( new Options( $this->option, $this->id, $this->index ) )->get( $default );
	}

	public function paragraphOpen() {
		if ( true === $this->paragraph ) {
			return '<p>';
		}

		return '';
	}

	public function labelOpen( $addToId = '', $class = '' ) {
		if ( is_string( $this->label ) && ( '' !== $this->label ) ) {
			return '<label for="' . $this->getId() . ( ( is_string( $addToId ) && ( '' !== $addToId ) ) ? $addToId : '' ) . '"' . ( ( '' !== $class ) ? ' class="' . $class . '"' : '' ) . '>';
		}

		return '';
	}

	public function getId() {
		$item = $this->option . '_' . $this->id;
		if ( '' !== $this->index ) {
			$item .= '_' . $this->index;
		}

		return $item;
	}

	public function tag( $tag, $arguments = [], $close = false ) {
		$line = '';
		$line .= '<' . $tag;
		if ( count( $arguments ) > 0 ) {
			$line .= ' ' . $this->arguments( $arguments );
		}
		$line .= '>';
		if ( $close ) {
			$line .= '</' . $tag . '>';
		}

		return $line;
	}

	public function arguments( $arguments ) {
		$line = '';
		foreach ( $arguments as $key => $value ) {
			$line .= $this->argument( $key, $value ) . ' ';
		}
		if ( '' !== $line ) {
			$line = substr( $line, 0, - 1 );
		}

		return $line;
	}

	public function argument( $key, $value ) {
		if ( is_null( $value ) ) {
			return '';
		}

		return $key . '="' . $value . '"';
	}

	public function getName() {
		$item = $this->option . '[' . $this->id . ']';
		if ( '' !== $this->index ) {
			$item .= '[' . $this->index . ']';
		}

		return $item;
	}

	public function labelClose( $show = true ) {
		if ( is_string( $this->label ) && ( '' !== $this->label ) ) {
			if ( $show ) {
				return ' ' . $this->label . '</label>';
			} else {
				return '</label>';
			}
		}

		return '';
	}

	public function paragraphClose() {
		if ( true === $this->paragraph ) {
			return '</p>';
		}

		return '';
	}

	public function view() {
		echo $this->get();
	}

}