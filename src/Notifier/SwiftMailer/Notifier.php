<?php
namespace PhpSolution\NotificationBundle\Notifier\SwiftMailer;

use PhpSolution\Notification\Notifier\AbstractNotifier;
use PhpSolution\Notification\Rule\RuleInterface;
use Swift_Mailer as Mailer;
use Swift_Message as Message;

/**
 * Class SwiftMailerNotifier
 *
 * @package PhpSolution\NotificationBundle\Notifier\SwiftMailer
 */
class Notifier extends AbstractNotifier
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var string|null
     */
    private $defaultSenderEmail;

    /**
     * Notifier constructor.
     *
     * @param Mailer      $mailer
     * @param string|null $defaultSenderEmail
     */
    public function __construct(Mailer $mailer, string $defaultSenderEmail = null)
    {
        $this->mailer = $mailer;
        $this->defaultSenderEmail = $defaultSenderEmail;
    }

    /**
     * @param RuleInterface|Rule $rule
     */
    public function notifyRule(RuleInterface $rule): void
    {
        if (!$rule instanceof Rule) {
            throw new \InvalidArgumentException(sprintf('"%s" must be instanceof "%s"', get_class($rule), Rule::class));
        }

        $message = $rule->getMessage();
        $resolvedMessage = $this->resolveMessage($message);
        $this->mailer->send($resolvedMessage);
    }

    /**
     * @param Message $message
     *
     * @return Message
     */
    protected function resolveMessage(Message $message): Message
    {
        if (empty($message->getSender()) && !empty($this->defaultSender)) {
            $message->setFrom($this->defaultSender->getEmail());
        }

        return $message;
    }
}