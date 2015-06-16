<?php
require 'vendor/autoload.php';

class SeleniumTest extends PHPUnit_Extensions_Selenium2TestCase
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