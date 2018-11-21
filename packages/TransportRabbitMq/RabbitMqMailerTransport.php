<?php

namespace Beapp\SMS\Transport\Rabbitmq;

use Beapp\SMS\Core\SMS;
use Beapp\SMS\Core\SMSException;
use Beapp\SMS\Core\Transport\SMSTransport;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class RabbitMqMailerTransport implements SMSTransport
{
    /** @var ProducerInterface */
    private $producer;
    /** @var string */
    private $routingKey;

    /**
     * @param ProducerInterface $producer
     * @param string $routingKey
     */
    public function __construct(ProducerInterface $producer, string $routingKey = '')
    {
        $this->producer = $producer;
        $this->routingKey = $routingKey;
    }

    /**
     * Delivers the sms to the recipients through a specific channel (direct call to client, publish to AMQP server, etc...)
     *
     * @param SMS $sms
     * @throws SMSException
     */
    public function sendSMS(SMS $sms): void
    {
        try {
            $this->producer->publish($this->preparePayload($sms), $this->routingKey);
        } catch (\Exception $e) {
            throw new SMSException("Couldn't send sms through RabbitMq", 0, $e);
        }
    }

    protected function preparePayload(SMS $sms): string
    {
        return json_encode($sms);
    }
}
