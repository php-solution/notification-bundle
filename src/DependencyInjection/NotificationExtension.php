<?php
namespace PhpSolution\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidDefinitionException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NotificationExtension
 *
 * @package PhpSolution\NotificationBundle\DependencyInjection
 */
class NotificationExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->configureSwiftMailer($config, $container);
        $this->configureExtensions($config, $container);
        $this->configureManagerExtension($config, $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function configureExtensions(array $config, ContainerBuilder $container): void
    {
        if (!$config['extensions']['context_configurator']['enabled']) {
            $container->removeDefinition('notification.extension.context_configurator');
        }
        if (!$config['extensions']['event_dispatcher']['enabled']) {
            $container->removeDefinition('notification.extension.event_dispatcher');
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function configureManagerExtension(array $config, ContainerBuilder $container): void
    {
        $extensionId = $config['extensions']['use_for_manager'];
        if (!empty($extensionId)) {
            if (!$container->hasDefinition($extensionId)) {
                throw new InvalidDefinitionException(sprintf('Undefined notification manager extension with id: "%s"', $extensionId));
            }
            $container->getDefinition('notification.manager')
                ->replaceArgument(2, new Reference($extensionId));
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function configureSwiftMailer(array $config, ContainerBuilder $container): void
    {
        if ($container->hasDefinition('notification.notifier.swift_mailer')) {
            if ($config['notifier_swiftmailer']['enabled']) {
                $container->getDefinition('notification.notifier.swift_mailer')
                    ->replaceArgument(0, new Reference('mailer'))
                    ->replaceArgument(1, $config['notifier_swiftmailer']['default_sender']);
            } else {
                $container->removeDefinition('notification.notifier.swift_mailer');
            }
        }
    }
}
