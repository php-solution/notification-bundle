<?php
namespace PhpSolution\NotificationBundle\Notifier\SwiftMailer;

use PhpSolution\Notification\Rule\RuleInterface;
use Swift_Message as Message;

/**
 * Class Rule
 *
 * @package PhpSolution\NotificationBundle\Notifier\SwiftMailer
 */
class Rule implements RuleInterface
{
    /**
     * @var Message
     */
    private $message;

    /**
     * Rule constructor.
     *
     * @param string|null $to
     * @param string|null $subject
     * @param string|null $body
     * @param string|null $contentType
     * @param string|null $charset
     */
    public function __construct(string $to = null, string $subject = null, string $body = null, string $contentType = null, string $charset = null)
    {
        $this->message = new Message($subject, $body, $contentType, $charset);
        if (!is_null($to)) {
            $this->message->addTo($to);
        }
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param Message $message
     *
     * @return Rule
     */
    public function setMessage(Message $message): Rule
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public static function getNotifierName(): string
    {
        return Notifier::getName();
    }
}