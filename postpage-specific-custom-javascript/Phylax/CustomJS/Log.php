<?php
/*
 * ©2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://lukasznowicki.info/
 * @link https://wpkurs.pl/
 */

namespace Phylax\Plugin\CustomJS;


class Log {

	public function __construct( $message, $var = null ) {
		if ( defined( 'WP_DEBUG' ) && \WP_DEBUG ) {
			$this->logMessage( $message );
			if ( ! is_null( $var ) ) {
				$this->logVariable( $var );
			}
		}
	}

	public function logMessage( $message ) {
		if ( ! is_string( $message ) ) {
			$message = (string) $message;
		}
		error_log( 'Post/Page specific custom JavaScript WP plugin: ' . $message );
	}

	public function logVariable( $var ) {
		$type = gettype( $var );
		error_log( 'Developer provided additional variable of type ' . $type . ' and here it is:' );
		switch ( $type ) {
			case 'boolean':
				if ( $var ) {
					error_log( 'TRUE' );
				} else {
					error_log( 'FALSE' );
				}
				break;
			case 'integer':
			case 'double':
			case 'string':
				error_log( $var );
				break;
			case 'array':
			case 'object':
			case 'resource':
			case 'resource (closed)':
				error_log( print_r( $var, true ) );
				break;
			default:
				error_log( print_r( $var, true ) );
				break;
		}

	}

}