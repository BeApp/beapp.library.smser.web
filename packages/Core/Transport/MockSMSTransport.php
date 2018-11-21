<?php

namespace Beapp\SMS\Core\Transport;

use Beapp\SMS\Core\SMS;

class MockSMSTransport implements SMSTransport
{

    /** @var SMS|null */
    private $lastSMS;

    /**
     * Delivers the sms to the recipients through a specific channel (direct call to client, publish to AMQP server, etc...)
     *
     * @param SMS $sms
     */
    public function sendSMS(SMS $sms): void
    {
        $this->lastSMS = $sms;
    }

    /**
     * @return mixed
     */
    public function getLastSMS()
    {
        $lastMail = $this->lastSMS;
        $this->lastSMS = null;
        return $lastMail;
    }
}
