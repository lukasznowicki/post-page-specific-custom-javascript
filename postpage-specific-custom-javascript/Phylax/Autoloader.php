<?php
/**
 * Autoloader feature
 * Long time ago, about 2004, PHP5 was released and introducing "magic" function __autoload. Then I wrote first PHP
 * class autoloader. Of course things changed overtime. As of PHP 5.1.2 (Jan 12, 2006) we get some SPL improvements
 * including spl_autoload*() functions. Of course this class changed since then. Now it supports PHP7.4 and you can
 * find it at https://github.com/lukasznowicki/psr-autoloader
 *
 * Quick note: in this implementation, PHP7.4 syntax is removed and class is adjusted to run in PHP 5.6 for the
 * best compatibility with a lot of WordPress sites, since I use it in WordPress plugin.
 *
 * ©2004-2020 phylax.pl Łukasz Nowicki <kontakt@phylax.pl>
 * @link https://lukasznowicki.info/
 * @link: https://wpkurs.pl/
 * @author Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @link https://github.com/lukasznowicki/psr-autoloader
 */
namespace Phylax;

use Exception;
use const DIRECTORY_SEPARATOR;

/**
 * Class Autoloader
 *
 * Use this class to handle auto loading classes, traits, implementations and abstracts.
 * You may declare namespace and proper directory in the constructor (usually use this
 * feature when you got only one) or by using register_handler and addNamespace methods.
 */
class Autoloader {

	/**
	 * @var array
	 */
	protected $namespaces = [];

	/**
	 * Autoloader constructor. You may assign namespace and directory in the constructor. If you do, the register
	 * handler method will be invoked automatically
	 *
	 * @param string|null $namespace
	 * @param string|null $directory
	 * @param bool $exitOnFail
	 */
	public function __construct( $namespace = null, $directory = null, $exitOnFail = true ) {
		if ( ! is_null( $namespace ) && ! is_null( $directory ) ) {
			$this->registerHandler( $exitOnFail );
			$this->addNamespace( $namespace, $directory );
		}
	}

	/**
	 * @param bool $exitOnFail
	 *
	 * @return bool
	 */
	public function registerHandler( $exitOnFail = true ) {
		try {
			spl_autoload_register( [
				$this,
				'classLoader',
			] );
		} catch ( Exception $exception ) {
			if ( $exitOnFail ) {
				exit;
			}

			return false;
		}

		return true;
	}

	/**
	 * @param string $namespace
	 * @param string $directory
	 * @param bool $prepend
	 *
	 * @return bool
	 */
	public function addNamespace( $namespace, $directory, $prepend = false ) {
		$namespace = $this->prepareNamespace( $namespace );
		$directory = $this->prepareDirectory( $directory );
		if ( ! isset( $this->namespaces[ $namespace ] ) ) {
			$this->namespaces[ $namespace ] = [];
		}
		if ( $prepend ) {
			array_unshift( $this->namespaces[ $namespace ], $directory );
		} else {
			array_push( $this->namespaces[ $namespace ], $directory );
		}

		return true;
	}

	/**
	 * @param string $namespace
	 *
	 * @return string
	 */
	protected function prepareNamespace( $namespace ) {
		return $this->prepareString( $namespace, '\\' );
	}

	/**
	 * @param string $string
	 * @param string $inUse
	 *
	 * @return string
	 */
	protected function prepareString( $string, $inUse ) {
		$string = str_replace( [
			'\\',
			'/',
		], $inUse, $string );
		$string = rtrim( $string, $inUse ) . $inUse;

		return $string;
	}

	/**
	 * @param string $directory
	 *
	 * @return string
	 */
	protected function prepareDirectory( $directory ) {
		return $this->prepareString( $directory, DIRECTORY_SEPARATOR );
	}

	/**
	 * @param string $class
	 *
	 * @return string|null
	 */
	public function classLoader( $class ) {
		$prefix = $class;
		while ( false !== $position = strrpos( $prefix, '\\' ) ) {
			$prefix  = substr( $class, 0, $position + 1 );
			$relCls  = substr( $class, $position + 1 );
			$mapFile = $this->checkMappedFile( $prefix, $relCls );
			if ( $mapFile ) {
				return $mapFile;
			}
			$prefix = rtrim( $prefix, '\\' );
		}

		return null;
	}

	/**
	 * @param string $namespace
	 * @param string $relClass
	 *
	 * @return string|null
	 */
	protected function checkMappedFile( $namespace, $relClass ) {
		if ( false === isset( $this->namespaces[ $namespace ] ) ) {
			return null;
		}
		foreach ( $this->namespaces[ $namespace ] as $baseDir ) {
			$filePath = $baseDir . str_replace( '\\', DIRECTORY_SEPARATOR, $relClass ) . '.php';
			if ( $this->callFile( $filePath ) ) {
				return $filePath;
			}
		}

		return null;
	}

	/**
	 * @param string $filePath
	 *
	 * @return bool
	 * @noinspection PhpIncludeInspection
	 */
	protected function callFile( $filePath ) {
		if ( is_readable( $filePath ) && ! is_dir( $filePath ) ) {
			require_once $filePath;

			return true;
		}

		return false;
	}

}