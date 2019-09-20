<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of an application or library developed by Datamedrix and
 * is subject to the provisions of your License Agreement with
 * Datamedrix GmbH.
 *
 * @copyright (c) 2018 Datamedrix GmbH
 * ----------------------------------------------------------------------------
 * @author Christian Graf <c.graf@datamedrix.com>
 */

declare(strict_types=1);

namespace DMX\Application\Pipeline\Exceptions;

use Throwable;

class InvalidHookHandlerException extends \UnexpectedValueException
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function __construct(?string $message = null, int $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'The designated hook handler is not valid!';
        }

        parent::__construct($message, $code, $previous);
    }
}
