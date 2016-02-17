<?php

/*
 * This file is a part of the Symple Initializable Controller package.
 *
 * (c) Constantine Seleznyoff <constantine@symple-dev.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symple\Bundle\InitControllerBundle\Annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 *
 * @author Constantine Seleznyoff <constantine@symple-dev.ru>
 */
class Init
{
    /**
     * @var int
     */
    public $priority = 0;
}
