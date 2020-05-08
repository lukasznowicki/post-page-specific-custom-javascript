<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS;


class Ajax {

	public function __construct() {
	}

	public function error( $msg ) {
		echo json_encode( [
			'error' => 1,
			'msg'   => $msg,
		] );
		exit;
	}
}