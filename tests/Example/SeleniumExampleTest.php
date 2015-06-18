<?php
namespace Tests\Example;
/**
 * Selenium Example Test
 * 
 * In order to be able to run this test, you'll need to:
 * 
 * 1. Download the Selenium Server JAR file
 * (selenium-server-standalone-2.xx.y.jar) from
 * http://docs.seleniumhq.org/download/
 * 
 * 2. Start the Selenium Server with: java -jar selenium-server-...jar
 * 
 * 3. Start the PHP webserver with: php -S localhost:8000
 * 
 * 4. Run the phpunit 
 * 
 * @author Antonis Flangofas <a.flangofas@qobo.biz>
 * @group example
 * @group selenium
 * @group network
 * @requires PHP 5.4
 */
class SeleniumExampleTest extends \PHPUnit_Extensions_Selenium2TestCase
{
	protected function setUp()
	{
		$this->setBrowser('firefox');
		$this->setBrowserUrl('http://localhost:8000');
	}


	public function testTitle()
	{
		$this->url('index.php');
		$this->assertContains('phpinfo', $this->title());
	}

}
?>
