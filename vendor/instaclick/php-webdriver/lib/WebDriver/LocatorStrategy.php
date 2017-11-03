<?php
/**
 * Copyright 2011-2014 Fabrizio Branca. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package WebDriver
 *
 * @author Fabrizio Branca <mail@fabrizio-branca.de>
 * @author Anthon Pang <apang@softwaredevelopment.ca>
 */

namespace WebDriver;

/**
 * WebDriver\LocatorStrategy class
 *
 * @package WebDriver
 */
final class LocatorStrategy
{
    const CLASS_NAME        = 'class name';
    const CSS_SELECTOR      = 'css selector';
    const ID                = 'id';
    const NAME              = 'name';
    const LINK_TEXT         = 'link text';
    const PARTIAL_LINK_TEXT = 'partial link text';
    const TAG_NAME          = 'tag name';
    const XPATH             = 'xpath';
}
