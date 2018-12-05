<?php
namespace test;

require __DIR__.'/../vendor/autoload.php';

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;

class SeleniumTestDriver {

	/**
	 * @return RemoteWebDriver
	 */
	public static function create() {
		$host = 'http://localhost:4444/wd/hub';
		$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome(), 5000);
		$driver->manage()->timeouts()->pageLoadTimeout(3);
		// $driver->execute(DriverCommand::SET_TIMEOUT, ['pageLoad' => 1000, 'implicit' => 1000]);
		return $driver;
	}

}

class Test extends TestCase {

	/**
	 * @var RemoteWebDriver $driver
	 */
	private $driver;

	public function setUp() {
		$this->driver = SeleniumTestDriver::create();
	}

	public function tearDown() {
		$this->driver->close();
	}

	private function prepareTest() {
		$d = $this->driver;
		$d->get('http://localhost/BooksPhp/index.php?action=prepareTest');
	}

	private function finishTest() {
		$d = $this->driver;
		$d->get('http://localhost/BooksPhp/index.php?action=finishTest');
	}

	public function insertBook() {
		$d = $this->driver;
		$d->get('http://localhost/BooksPhp/index.php?action=bookForm');
		$form = $d->findElement(WebDriverBy::cssSelector('form.add'));
		// sleep(1);
		$form->findElement(WebDriverBy::name('title'))->sendKeys('Приветствие');
		// sleep(1);
		$form->findElement(WebDriverBy::name('description'))->sendKeys('Работает');
		// sleep(1);
		$form->findElement(WebDriverBy::name('author'))->sendKeys('Сидоров.В.М');
		// sleep(1);
		$form->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
		$elements = $d->findElement(WebDriverBy::cssSelector('table'))->getText();
		sleep(3);
		$this->assertContains('Приветствие', $elements);

	}

	function deleteBook() {

	}

}
