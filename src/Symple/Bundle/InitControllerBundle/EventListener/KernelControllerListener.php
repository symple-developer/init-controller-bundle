<?php

/*
 * This file is a part of the Symple Initializable Controller package.
 *
 * (c) Constantine Seleznyoff <constantine@symple-dev.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symple\Bundle\InitControllerBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symple\Bundle\InitControllerBundle\Annotation\Init;
use Symple\Bundle\InitControllerBundle\Controller\InitControllerInterface;

/**
 * @author Constantine Seleznyoff <constantine@symple-dev.ru>
 */
class KernelControllerListener
{
    /**
     * @var Reader
     */
    protected $annotationReader;

    /**
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller) && $controller[0] instanceof InitControllerInterface) {
            $this->initialize($controller);
        }
    }

    /**
     * @param array|\ReflectionMethod[] $methods
     * @return array
     */
    protected function getInitMethods(array $methods)
    {
        $initMethods = [];

        foreach ($methods as $method) {
            $annotations = $this->annotationReader->getMethodAnnotations($method);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Init) {
                    $initMethods[] = ['method' => $method, 'priority' => $annotation->priority];
                }
            }
        }

        return $initMethods;
    }

    /**
     * @param array $controller
     */
    protected function initialize(array $controller)
    {
        $reflector = new \ReflectionClass($controller[0]);
        $methods = $reflector->getMethods(\ReflectionMethod::IS_PUBLIC);
        $initMethods = $this->getInitMethods($methods);
        usort($initMethods, function($a, $b) { return $b['priority'] - $a['priority']; });
        $this->invokeInitMethods($initMethods, $controller[0]);
    }

    /**
     * @param array $initMethods
     * @param object $object
     */
    protected function invokeInitMethods(array $initMethods, $object)
    {
        foreach ($initMethods as $initMethod) {
            /** @var \ReflectionMethod $method */
            $method = $initMethod['method'];
            $method->invoke($object);
        }
    }
}
