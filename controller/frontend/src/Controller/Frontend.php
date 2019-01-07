<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 * @package MShop
 */


namespace Aimeos\Controller;


/**
 * Factory which can create all Frontend controllers
 *
 * @package \Aimeos\Controller\Frontend
 */
class Frontend
{
	static private $cache = true;
	static private $objects = [];


	/**
	 * Enables or disables caching of class instances
	 *
	 * @param boolean $value True to enable caching, false to disable it.
	 * @return boolean Previous cache setting
	 */
	static public function cache( $value )
	{
		self::$cache = (boolean) $value;
		self::$objects = [];
	}


	/**
	 * Creates the required controller specified by the given path of controller names
	 *
	 * Controllers are created by providing only the domain name, e.g.
	 * "basket" for the \Aimeos\Controller\Frontend\Basket\Standard or a path of names to
	 * retrieve a specific sub-controller if available.
	 * Please note, that only the default controllers can be created. If you need
	 * a specific implementation, you need to use the factory class of the
	 * controller to hand over specifc implementation names.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object required by managers
	 * @param string $path Name of the domain (and sub-managers) separated by slashes, e.g "basket"
	 * @return \Aimeos\Controller\Frontend\Iface New frontend controller
	 * @throws \Aimeos\Controller\Frontend\Exception If the given path is invalid or the manager wasn't found
	 */
	static public function create( \Aimeos\MShop\Context\Item\Iface $context, $path )
	{
		if( empty( $path ) ) {
			throw new \Aimeos\Controller\Frontend\Exception( sprintf( 'Controller path is empty' ) );
		}

		if( self::$cache === false || !isset( self::$objects[$path] ) )
		{
			if( ctype_alnum( $path ) === false ) {
				throw new \Aimeos\Controller\Frontend\Exception( sprintf( 'Invalid characters in controller name "%1$s"', $path ) );
			}

			$factory = '\\Aimeos\\Controller\\Frontend\\' . ucfirst( $path ) . '\\Factory';

			if( class_exists( $factory ) === false ) {
				throw new \Aimeos\Controller\Frontend\Exception( sprintf( 'Class "%1$s" not available', $factory ) );
			}

			if( ( $controller = call_user_func_array( [$factory, 'create'], [$context] ) ) === false ) {
				throw new \Aimeos\Controller\Frontend\Exception( sprintf( 'Invalid factory "%1$s"', $factory ) );
			}

			self::$objects[$path] = $controller;
		}

		return clone self::$objects[$path];
	}
}