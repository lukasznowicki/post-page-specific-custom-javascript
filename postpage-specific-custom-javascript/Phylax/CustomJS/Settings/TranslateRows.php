<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings;


class TranslateRows {

	public $where;

	public function __construct( $where ) {
		$this->where = $where;
	}

	public function view() {
		echo $this->get();
	}

	public function get() {
		$rows = (int) ( ( new Options( Register::FIELD_VIEW_SIZE, $this->where ) )->get() );
		$item = '';
		switch ( $rows ) {
			case 1: // Normal
				$item = '15';
				break;
			case 2: // Bigger
				$item = '25';
				break;
			case 3: // Very big
				$item = '40';
				break;
			default: // Minimal
				$item = '6';
				break;
		}
		return $item;
	}
}