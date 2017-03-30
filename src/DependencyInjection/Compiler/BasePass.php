<?php
namespace PhpSolution\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class BasePass
 *
 * @package PhpSolution\NotificationBundle\DependencyInjection\Compiler
 */
class BasePass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $this->addToRegistry($container, 'notification.notifier_registry', 'notification.notifier');
        $this->addToRegistry($container, 'notification.type_registry', 'notification.type');
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $registryName
     * @param string           $tagName
     */
    private function addToRegistry(ContainerBuilder $container, string $registryName, string $tagName): void
    {
        if (false === $container->hasDefinition($registryName)) {
            return;
        }

        $registryDef = $container->getDefinition($registryName);
        $taggedServices = $container->findTaggedServiceIds($tagName);
        if (is_array($taggedServices) && count($taggedServices) > 0) {
            foreach (array_keys($taggedServices) as $id) {
                $registryDef->addMethodCall('add', [new Reference($id)]);
            }
        }
    }
}