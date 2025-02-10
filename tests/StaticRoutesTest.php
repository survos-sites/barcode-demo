<?php

namespace Tests;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;
use Pierstoval\SmokeTesting\FunctionalSmokeTester;
use Pierstoval\SmokeTesting\FunctionalTestData;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StaticRoutesTest extends WebTestCase
{
	use FunctionalSmokeTester;

	#[TestDox('/$method $url ($route)')]
	#[TestWith(['GET', 'https://barcode.wip/health', 'monitor.health'])]
	#[TestWith(['GET', 'https://barcode.wip/internal/health', 'monitor.internal_health'])]
	#[TestWith(['GET', 'https://barcode.wip/info', 'monitor.info'])]
	#[TestWith(['GET', 'https://barcode.wip/internal/info', 'monitor.internal_info'])]
	#[TestWith(['GET', 'https://barcode.wip/', 'app_homepage'])]
	public function testRoute(string $method, string $url, string $route): void
	{
		$this->runFunctionalTest(
		            FunctionalTestData::withUrl($url)
		                ->withMethod($method)
		                ->expectRouteName($route)
		                ->appendCallableExpectation($this->assertStatusCodeLessThan500($method, $url))
		        );
	}


	public function assertStatusCodeLessThan500(string $method, string $url): \Closure
	{
		return function (KernelBrowser $browser) use ($method, $url) {
		                $statusCode = $browser->getResponse()->getStatusCode();
		                $routeName = $browser->getRequest()->attributes->get('_route', 'unknown');

		                static::assertLessThan(
		                    500,
		                    $statusCode,
		                    sprintf('Request "%s %s" for %s route returned an internal error.', $method, $url, $routeName),
		                );
		            };
	}
}
