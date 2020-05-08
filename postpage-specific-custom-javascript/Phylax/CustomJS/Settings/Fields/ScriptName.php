<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class ScriptName {

	public $slug;
	public $name;

	public function __construct( $slug ) {
		$this->slug = $slug;
	}

	public function view() {
		echo $this->get();
	}

	public function get() {
		$item       = $this->slug;
		$item       = str_replace( '-', ' ', $item );
		$item       = ucwords( $item );
		$item       = str_replace( [
			'Jquery',
		], [
			'jQuery',
		], $item );
		$this->name = $item;

		return $this->name;
	}
}