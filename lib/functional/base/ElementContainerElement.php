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
 * @brief ...
 *
 * @author pyd <pierre.yves.delettre@gmail.com>
 */
class ElementContainerElement extends ElementContainer {

    /**
     * @var Element
     */
    protected $element;

    public function __construct(Element $element) {
        $this->element = $element;
        parent::__construct();
    }

    /**
     * Search a web element {@see $element} property then a location alias
     * {@link ElementContainer::__get}
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name){
        if(property_exists($this->element, $name)){
            return $this->element->$name;
        }
        return parent::__get($name);
    }

    /**
     * Setter for a {@see $element} property.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        if(property_exists($this->element, $name)){
            $this->element->$name = $value;
        }
    }

    /**
     * Call {@see $element} method if exists.
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        if(method_exists($this->element, $name)){
            return call_user_func([$this->element, $name], $arguments);
        }
    }

    /**
     * Finder is the parent element {@see $element} itself.
     *
     * @return Element
     */
    public function getFinder() {
        return $this->element;
    }

    /**
     * Set container parent element. When a page is refreshed/reloaded, previously
     * found elements must be refreshed - they are not recognized by Selenium Server
     * anymore.
     * 
     * @param Element $element
     */
    public function refreshContainer(Element $element){
        $this->element = $element;
    }
}
