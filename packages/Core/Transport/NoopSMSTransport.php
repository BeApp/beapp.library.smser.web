<?php

namespace Beapp\SMS\Core\Transport;

use Beapp\SMS\Core\SMS;
use Psr\Log\LoggerInterface;

/**
 * Only log the given {@link SMS}.
 * This is useful in development environment to prevent spamming users
 */
class NoopSMSTransport implements SMSTransport
{

    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * NoopMailerTransport constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendSMS(SMS $sms): void
    {
        $this->logger->debug("NOOP sending sms", ['sms' => $sms]);
    }
}
