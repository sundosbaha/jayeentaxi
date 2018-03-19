<?php

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Behat\Snippet\Exception;

use Behat\Testwork\Exception\TestworkException;

/**
 * All snippet exceptions should implement this interface.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface SnippetException extends TestworkException
{
}
