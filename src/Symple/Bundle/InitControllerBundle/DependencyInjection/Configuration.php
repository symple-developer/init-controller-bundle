<?php

/*
 * This file is a part of the Symple Initializable Controller package.
 *
 * (c) Constantine Seleznyoff <constantine@symple-dev.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symple\Bundle\InitControllerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Constantine Seleznyoff <constantine@symple-dev.ru>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('init_controller');
        $root->children()
                ->booleanNode('enabled')->defaultTrue()->end()
            ->end();

        return $builder;
    }
}
