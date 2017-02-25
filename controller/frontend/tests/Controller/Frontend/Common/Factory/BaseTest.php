<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2017
 */


namespace Aimeos\Controller\Frontend\Common\Factory;


class BaseTest extends \PHPUnit_Framework_TestCase
{
	private $context;


	protected function setUp()
	{
		$this->context = \TestHelperFrontend::getContext();
		$config = $this->context->getConfig();

		$config->set( 'controller/frontend/common/decorators/default', array() );
		$config->set( 'controller/frontend/catalog/decorators/global', array() );
		$config->set( 'controller/frontend/catalog/decorators/local', array() );

	}


	public function testInjectController()
	{
		$controller = \Aimeos\Controller\Frontend\Catalog\Factory::createController( $this->context, 'Standard' );
		\Aimeos\Controller\Frontend\Catalog\Factory::injectController( '\\Aimeos\\Controller\\Frontend\\Catalog\\Standard', $controller );

		$injectedController = \Aimeos\Controller\Frontend\Catalog\Factory::createController( $this->context, 'Standard' );

		$this->assertSame( $controller, $injectedController );
	}


	public function testInjectControllerReset()
	{
		$controller = \Aimeos\Controller\Frontend\Catalog\Factory::createController( $this->context, 'Standard' );
		\Aimeos\Controller\Frontend\Catalog\Factory::injectController( '\\Aimeos\\Controller\\Frontend\\Catalog\\Standard', $controller );
		\Aimeos\Controller\Frontend\Catalog\Factory::injectController( '\\Aimeos\\Controller\\Frontend\\Catalog\\Standard', null );

		$new = \Aimeos\Controller\Frontend\Catalog\Factory::createController( $this->context, 'Standard' );

		$this->assertNotSame( $controller, $new );
	}

}