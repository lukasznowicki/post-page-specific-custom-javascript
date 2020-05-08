<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS\Settings;


use const Phylax\Plugin\CustomJS\TEXT_DOMAIN;

class ShowDonation {

	public $holidays = [];

	public function setHolidays() {
	}

	public function show() {
		return false;
	}

}