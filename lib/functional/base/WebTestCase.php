<?php
namespace pyd\testkit\lib\functional\base;

use pyd\testkit\lib\TestKit;
use pyd\testkit\lib\functional\base\WebDriver;

/**
 * Web test case class.
 *
 * @author pyd001
 */
class WebTestCase extends \pyd\testkit\lib\TestCase {

    /**
     * @var string used by web driver
     */
    public $seleniumServerUrl = 'http://localhost:4444/wd/hub';


    public function setUp(){
        TestKit::$webDriver = WebDriver::create($this->seleniumServerUrl, \DesiredCapabilities::firefox());
        $this->initTestCase();
        parent::setUp();
    }

    public function getCurrentUrl(){

    }

    public function takeScreenShot(){

    }

    /**
     * @brief Suspends test execution until tester press the ENTER key.
     *
     * @note Terminal window must have focus to capture key press.
     */
    public function pauseTest(){
        if(trim(fgets(fopen("php://stdin","r"))) != chr(13)) return;
    }

    public function tearDown(){
        parent::tearDown();
        if(NULL !== $this->webDriver) $this->webDriver->quit();
    }

    /**
     * @brief Initialise l'environnement de test.
     */
    protected function initTestCase(){
        $rc = new \ReflectionClass(get_class($this));
        TestKit::initTestCase(dirname($rc->getFileName()));
    }
}
