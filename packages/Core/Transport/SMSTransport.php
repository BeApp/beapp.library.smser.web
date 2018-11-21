<?php

namespace Beapp\SMS\Core\Transport;

use Beapp\SMS\Core\SMS;
use Beapp\SMS\Core\SMSException;

/**
 * Interface SMSTransport
 */
interface SMSTransport
{
    /**
     * Delivers the SMS to the recipients through a specific channel (direct call to client, publish to AMQP server, etc...)
     *
     * @param SMS $sms
     * @throws SMSException
     */
    public function sendSMS(SMS $sms): void;
}
