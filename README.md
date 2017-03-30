# Notification Bundle
This bundle integrates Notification component to Symfony application.

Please, see [documentation](https://github.com/php-solution/notification-lib) for Notification component before start development.

## Configuration
```
notification:
    extensions:
        use_for_manager: "notification.extension.context_configurator"
        context_configurator:
            enabled: true
        event_dispatcher:
            enabled: false
    notifier_swiftmailer:
        enabled: true
        default_sender: ~
```

## Installing
1. Add to your composer.json
```
    "require": {
        ...
        "php-solution/notification-bundle": "dev-master",
        ...
    }
```
2) run: 
```
    composer update php-solution/notification-bundle
```

## Example
Notification Type:
```
    <?php
    namespace AppBundle\Notification;
    
    use PhpSolution\Notification\Context;
    use PhpSolution\Notification\Extension\ContextConfigure\ConfiguratorInterface;
    use PhpSolution\Notification\Rule\RuleInterface;
    use PhpSolution\Notification\Type\AbstractType;
    use PhpSolution\NotificationBundle\Notifier\SwiftMailer\Rule;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    
    class NotificationType extends AbstractType implements ConfiguratorInterface
    {
        /**
         * Must yield RuleInterface
         *
         * @param Context|null $context
         *
         * @return \Generator|RuleInterface|RuleInterface[]
         */
        public function initialize(?Context $context): \Generator
        {
            yield new Rule($context['recipient_email']);
        }
    
        /**
         * @param OptionsResolver $resolver
         */
        public function configureContext(OptionsResolver $resolver)
        {
            $resolver
                ->setRequired('recipient_email')
                ->setAllowedTypes('recipient_email', 'string');
        }
    }        
```
On controller:
```
    $this->get('notification.manager')->notifyType(
        new NotificationType(), 
        new Context(['recipient_email' => 'email@gmail.com'])
    );
```