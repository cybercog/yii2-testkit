<?php

/**
 * Copyright (c) 2014 Pierre-Yves DELETTRÉ
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace pyd\testkit\lib\functional\base;

/**
 * This is a web elements container. A page - a window? - or a web element.
 *
 * @author pyd <pierre.yves.delettre@gmail.com>
 */
abstract class ElementContainer {

    /**
     * @var Locator
     */
    protected $locator;

    public function __construct(){
        $this->locator = new Locator();
        $this->initLocations();
    }

    /**
     * Add container initial locations here.
     */
    protected function initLocations(){}

    /**
     * @brief Finder is an object that implements findElement & findElements methods.
     * It can be a WebDriver or an Element object. The former will be used in
     * a Page container while the later
     */
    abstract protected function getFinder();

    /**
     * Return the first matching element if $name is a location alias.
     *
     * @param string $name
     * @return Element
     */
    public function __get($name){
        if($this->locator->hasLocation($name)){
            return $this->findElement($name);
        }
    }

    /**
     * @return Locator
     */
    public function  getLocator(){
        return $this->locator;
    }

    /**
     * Find the first web element matching location.
     *
     * @param string|array $location
     * @return \pyd\testkit\lib\functional\Element
     */
    public function findElement($location){
        return $this->getFinder()->findElement($this->locator->createWebDriverBy($location));
    }

    /**
     * Find all elements matching location.
     *
     * @param string|array $location
     * @return array
     */
    public function findElements($location){
        return $this->getFinder()->findElements($this->locator->createWebDriverBy($location));
    }

    /**
     * Element is present - visible or not.
     *
     * @param string|array $location
     * @return boolean
     */
    public function hasElement($location){
        try{
            $this->findElement($location);
            return TRUE;
        } catch (\NoSuchElementException $e) {
            return FALSE;
        }
    }

    /**
     * Wait for an element - visible or not - to be present.
     *
     * @param string|array $location
     * @param integer $timeout in seconds
     * @param integer $interval in milliseconds
     */
    public function waitElementPresent($location, $timeout=5, $interval=1000){
        $by = $this->locator->createWebDriverBy($location);
        TestKit::$webDriver->wait($timeout, $interval)->until(
            \WebDriverExpectedCondition::presenceOfElementLocated($by)
        );
    }
}
