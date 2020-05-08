<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings\Fields;


class jQueryCDN {

	public function get() {
		return [
			'jQuery CDN <small>3.5.1</small>' => 'https://code.jquery.com/jquery-3.5.1.min.js',
			'CNDJS CloudFlare <small>3.5.1</small>' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js',
		];
	}

}