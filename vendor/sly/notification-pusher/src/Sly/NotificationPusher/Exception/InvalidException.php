<?php

/*
 * This file is part of NotificationPusher.
 *
 * (c) 2013 Cédric Dugat <cedric@dugat.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sly\NotificationPusher\Exception;

/**
 * InvalidException.
 *
 * @uses   \RuntimeException
 * @uses   \Sly\NotificationPusher\Exception\ExceptionInterface
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class InvalidException extends \RuntimeException implements ExceptionInterface
{
}
