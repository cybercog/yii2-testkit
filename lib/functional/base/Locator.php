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

namespace pyd\functional\base;

use yii\base\InvalidParamException;

/**
 * Facebook api uses a WebDriverBy instance as an argument for its findElement(s)
 * methods. Typical use is:
 * $errorSummaryElement = $page->findElement(WebDriverBy::className('errorSummary'));
 * Here you will be able to store elements locations and use:
 * $errorSummaryElement = $page->findElement('errorSummary');
 *
 * @author pyd <pierre.yves.delettre@gmail.com>
 */
class Locator {

    /**
     * A list of web element locations.
     * @var array each item is indexed by an alias and its value is an array
     * containing a {@link WebDriverBy::$mechanism} & a {@link WebDriverBy::$value}.
     * e.g.
     * 'header'=>['tagName', 'header']
     * 'username'=>['cssSelector', 'form input.username']
     * 'siteNav'=>['id', 'site-nav']
     * For a list of all available mechanisms\strategies {@link WebDriverBy}
     */
    protected $locations = [];

    /**
     * Add location for an element.
     * @param string $alias
     * @param string $mechanism
     * @param string $value
     * @param boolean $overwrite
     * @throws InvalidParamException
     * @throws Exception
     */
    public function addLocation($alias, $mechanism, $value, $overwrite = FALSE){
        if(!is_string($alias)){
            throw new InvalidParamException(__METHOD__." \$alias param must be a string.");
        }elseif(!method_exists('WebDriverBy', $mechanism)){
            throw new InvalidParamException(__METHOD__." \$mechanism param is not a valid WebDriverBy method.");
        }elseif(!is_string($value)){
            throw new InvalidParamException(__METHOD__." \$value param must be a string.");
        }
        if($overwrite || (!isset($this->location))){
            $this->locations[$alias] = [$mechanism, $value];
        }else{
            throw new Exception("Cannot overwrite existing location with alias '$alias'.");
        }
    }

    /**
     * @return array
     */
    public function getLocations(){
        return $this->locations;
    }

    /**
     * Get location data by alias
     * @param string $alias
     * @return array
     * @throws InvalidParamException unknown alias
     */
    public function getLocation($alias){
        if(isset($this->locations[$alias])){
            return $this->locations[$alias];
        }else{
            throw new InvalidParamException("Unknown alias '$alias'.");
        }

    }

    /**
     * @param string $alias
     * @return boolean alias exists
     */
    public function hasLocation($alias){
        return isset($this->locations[$alias]);
    }

    /**
     * @param array|string $location {@see resolveLocation}.
     * @return WebDriverBy instance
     */
    public function createWebDriverBy($location){
        $location = $this->resolveLocation($location);
        list($mechanism, $value) = $location;
        return \WebDriverBy::$mechanism($value);
    }

    /**
     * @brief Retourne un array contenant les données pour créer une instance de
     * WebDriverBy. Le premier est le $mechanism (nom de méthode) et le second la
     * valeur (argument de méthode) requis pour créer une instance de WebDriverBy.
     *
     * @detail L'argument peut être une string (on recherchera l'alias correspondant
     * dans {@see $locations} ou un array. Il devra être au format ['mecanisme', 'valeur'].
     *
     * @param array|string $location
     * @return array
     * @throws Exception $location is a string but not an alias
     * @throws InvalidParamException $location[0] is not a {@link WebDriverBy::$mechanism}
     * @throws InvalidParamException $location[1] is not a string
     * @throws InvalidParamException $location is neither an array nor a string
     */
    protected function resolveLocation($location){
        if(is_string($location)){
            if(isset($this->locations[$location])){
                return $this->locations[$location];
            }else{
                throw new \Exception("Location alias '$location' does not exist.");
            }
        }elseif(is_array($location)){
            $mechanism = $location[0];
            $value = $location[1];
            if(!is_string($mechanism) || !method_exists('WebDriverBy', $mechanism)){
                throw new InvalidParamException("If ".__METHOD__." \$location param is an array, its first item must be a WebDriverBy method name.");
            }elseif(!is_string($value)){
                throw new InvalidParamException("If ".__METHOD__." \$location param is an array, its second item must be a string.");
            }else{
                return $location;
            }
        }else{
            throw new InvalidParamException(__METHOD__." \$location param must be a string or an array.");
        }
    }
}
