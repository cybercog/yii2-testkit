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
 * Overwrite facebook RemoteWebElement. {@see findElement} & {@see findElements}
 * methods will now return Element instances + some new methods.
 *
 * @author pyd <pierre.yves.delettre@gmail.com>
 */
class Element extends \RemoteWebElement {

    /**
     * @param \RemoteExecuteMethod $executor
     * @param string $id
     */
    public function __construct(\RemoteExecuteMethod $executor, $id) {
        $this->executor = $executor;
        $this->id = $id;
        $this->fileDetector = new \UselessFileDetector();
    }

    /**
     * Find the first matching child web element of this element.
     *
     * @param WebDriverBy $by
     * @return Element NoSuchElementException is thrown in HttpCommandExecutor if not found.
     */
    public function findElement(\WebDriverBy $by) {
        $params = array('using'=>$by->getMechanism(), 'value'=>$by->getValue(), ':id'=>$this->id);
        $raw_element = $this->executor->execute(\DriverCommand::FIND_CHILD_ELEMENT,$params);
        return $this->createElement($raw_element['ELEMENT']);
    }

    /**
     * Find all matching child web elements of this element.
     *
     * @param WebDriverBy $by
     * @return array of child elements, empty if none found
     * @see WebDriverBy
     */
    public function findElements(\WebDriverBy $by) {
        $params = array('using' => $by->getMechanism(), 'value' => $by->getValue(),':id'=>$this->id);
        $raw_elements = $this->executor->execute(\DriverCommand::FIND_CHILD_ELEMENTS, $params);
        $elements = array();
        foreach ($raw_elements as $raw_element) {
            $elements[] = $this->createElement($raw_element['ELEMENT']);
        }
        return $elements;
    }

    /**
     * Change an attribute value.
     *
     * @param string $attribute
     * @param string $value
     */
    public function modifyAttribute($attribute, $value) {
        $script = "arguments[0].$attribute = arguments[1];";
        $args = array(array('ELEMENT' => $this->getID()), $value);
        $this->executor->execute(array('script' => $script, 'args' => $args));
    }

    /**
     * @return boolean This element has focus
     */
    public function hasFocus(){
        return $this->equals(TestKit::$webDriver->getActiveElement());
    }

    /**
     * Wait for this element to be visible.
     *
     * @param integer $timeout
     * @param integer $interval
     */
    public function waitIsDisplayed($timeout=5, $interval=1000){
        TestKit::$webDriver->wait($timeout, $interval)->until(
            function(){ return $this->isDisplayed(); }
        );
    }

    /**
     * Wait for this element to be hidden.
     *
     * @param integer $timeout
     * @param integer $interval
     */
    public function waitIsHidden($timeout=5, $interval=1000){
        TestKit::$webDriver->wait($timeout, $interval)->until(
            function(){ return !$this->isDisplayed(); }
        );
    }

    /**
     * Create an element. Replace RemoteWebElement::newElement.
     *
     * @param integer $id Selenium server ID for this element.
     * @return Element
     */
    protected function createElement($id) {
        return new Element($this->executor, $id);
    }
}
