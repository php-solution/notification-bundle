<?php
namespace PhpSolution\NotificationBundle;

use PhpSolution\NotificationBundle\DependencyInjection\Compiler\BasePass;
use PhpSolution\NotificationBundle\DependencyInjection\Compiler\EventDispatcherPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class NotificationBundle
 *
 * @package PhpSolution\NotificationBundle
 */
class NotificationBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new BasePass())
            ->addCompilerPass(new EventDispatcherPass('event_dispatcher', 'notification.event_listener', 'notification.event_subscriber'));
    }
}
