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
 * @brief This class is a page opened in the browser with selenium server.
 *
 * @author pyd <pierre.yves.delettre@gmail.com>
 */
class Page {

    protected $route;

    /**
     * Http request for this page
     * @param array $params
     */
    public function open (array $params = []) {
        // create url based on $this->route and $params with UrlManager
        // request page with RemoteWebDriver::get($url)                         // require webDriver object
        // wait page completely loaded
        // check page is well displayed
    }

    /**
     * Wait page loaded
     */
    public function waitLoadComplete () {
        // check it with js: when document.readyState returns true              // require webDriver object
    }

    /**
     * Page is displayed without exception/error
     */
    public function isDisplayed () {
        // check page is well displayed:
        //  - can be an element reference in the DOM                            // require webDriver object
        //  - check response http code (400..., 500...)
        //      search in the exception page DOM: maybe use a custom yii error handler + testkit variable Watcher
        //      use BrowerMob proxy to capture response and check http code
        // define this method in a child class, set it as abstract here?
    }

    /**
     * Page source code
     */
    public function getSource () {
        // RemoteWebDriver::getSource();
    }

    /**
     * Page title tag content
     */
    public function getTitle () {
        // page title using RemoteWebDriver
    }

    /**
     * refresh the page
     */
    public function refresh () {
        // WebDriverNavigation::refresh
        // RemoteWebDriver::navigate()::refresh()
    }
}
