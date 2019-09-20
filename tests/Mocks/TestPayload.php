<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of an application or library developed by Datamedrix and
 * is subject to the provisions of your License Agreement with
 * Datamedrix GmbH.
 *
 * @copyright (c) 2019 Datamedrix GmbH
 * ----------------------------------------------------------------------------
 * @author Christian Graf <c.graf@datamedrix.com>
 */

declare(strict_types=1);

namespace DMX\Application\Pipeline\Tests\Mocks;

class TestPayload
{
    /**
     * @var int
     */
    public $touchCount = 0;

    /**
     * @var string
     */
    public $comments = '';

    /**
     * @var array
     */
    public $data = [];
}
