<?php

namespace Beapp\SMS\Transport\Beanstalk;

use Beapp\SMS\Core\SMS;
use Beapp\SMS\Core\SMSException;
use Beapp\SMS\Core\Transport\SMSTransport;
use Pheanstalk\Pheanstalk;
use Pheanstalk\PheanstalkInterface;

class BeanstalkSMSTransport implements SMSTransport
{
    /** @var Pheanstalk */
    private $client;
    /** @var string */
    private $beanstakdHost;
    /** @var string */
    private $tube;
    /** @var int */
    private $priority;
    /** @var int */
    private $delay;
    /** @var int */
    private $ttr;

    /**
     * @param string $beanstalkHost
     * @param string $tube
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     */
    public function __construct(
        string $beanstalkHost,
        string $tube,
        $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = PheanstalkInterface::DEFAULT_DELAY,
        $ttr = PheanstalkInterface::DEFAULT_TTR
    ) {
        $this->beanstakdHost = $beanstalkHost;
        $this->tube = $tube;
        $this->priority = $priority;
        $this->delay = $delay;
        $this->ttr = $ttr;
    }

    protected function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Pheanstalk($this->beanstakdHost);
        }
        return $this->client;
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
            $this->getClient()
                ->useTube($this->tube)
                ->put($this->preparePayload($sms), $this->priority, $this->delay, $this->ttr);
        } catch (\Exception $e) {
            throw new SMSException("Couldn't send sms through Beanstalk", 0, $e);
        }
    }

    protected function preparePayload(SMS $sms): string
    {
        return json_encode($sms);
    }
}
