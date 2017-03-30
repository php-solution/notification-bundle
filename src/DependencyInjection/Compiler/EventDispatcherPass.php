<?php
namespace PhpSolution\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

/**
 * Class EventPass
 *
 * @package PhpSolution\NotificationBundle\DependencyInjection\Compiler
 */
class EventDispatcherPass extends RegisterListenersPass
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('notification.extension.event_dispatcher')) {
            parent::process($container);
        }
    }
}