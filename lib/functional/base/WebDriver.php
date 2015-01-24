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
 * Overwrite facebook RemoteWebDriver. {@see findElement} & {@see findElements}
 * will now return Element instances.
 *
 * @author pyd <pierre.yves.delettre@gmail.com>
 */
class WebDriver extends \RemoteWebDriver {

    /**
     * Find the first matching web element in the DOM.
     *
     * @param WebDriverBy $by
     * @return Element NoSuchElementException is thrown in HttpCommandExecutor if not found.
     */
    public function findElement (\WebDriverBy $by) {
        
        $params = array('using' => $by->getMechanism(), 'value' => $by->getValue());
        $raw_element = $this->execute(\DriverCommand::FIND_ELEMENT, $params);
        return $this->createElement($raw_element['ELEMENT']);
    }

    /**
     * Find all matching web elements in the DOM.
     *
     * @param WebDriverBy $by
     * @return array of child elements, empty if none found
     * @see WebDriverBy
     */
    public function findElements (\WebDriverBy $by) {

        $params = array('using' => $by->getMechanism(), 'value' => $by->getValue());
        $raw_elements = $this->execute(\DriverCommand::FIND_ELEMENTS, $params);
        $elements = array();
        foreach ($raw_elements as $raw_element) {
            $elements[] = $this->createElement($raw_element['ELEMENT']);
        }
        return $elements;
    }

    /**
     * Return the WebDriverElement with the given id.
     *
     * @param string $id The id of the element to be created.
     * @return RemoteWebElement
     * @return \webElementClassName
     * @throws CException
     */
    protected function createElement ($id) {
        return new Element($this->getExecuteMethod(), $id);
    }
}
