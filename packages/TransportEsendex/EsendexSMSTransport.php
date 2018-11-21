<?php

namespace Beapp\SMS\Transport\Esendex;

use Beapp\SMS\Core\SMS;
use Beapp\SMS\Core\SMSException;
use Beapp\SMS\Core\Transport\SMSTransport;
use Esendex\Authentication\LoginAuthentication;
use Esendex\DispatchService;
use Esendex\Model\DispatchMessage;
use Esendex\Model\Message;

class EsendexSMSTransport implements SMSTransport
{
    /** @var string */
    private $accountRef;
    /** @var string */
    private $username;
    /** @var string */
    private $password;

    /** @var DispatchService|null */
    private $dispatchService;

    /**
     * @param string $accountRef
     * @param string $username
     * @param string $password
     */
    public function __construct(string $accountRef, string $username, string $password)
    {
        $this->accountRef = $accountRef;
        $this->username = $username;
        $this->password = $password;
    }

    protected function getDispatcher(): DispatchService
    {
        if (is_null($this->dispatchService)) {
            $authentication = new LoginAuthentication($this->accountRef, $this->username, $this->password);
            $this->dispatchService = new DispatchService($authentication);
        }
        return $this->dispatchService;
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
            $message = new DispatchMessage($sms->getSender(), $sms->getRecipient(), $sms->getContent(), Message::SmsType);
            $this->getDispatcher()->send($message);
        } catch (\Exception $e) {
            throw new SMSException("Couldn't send sms through Beanstalk", 0, $e);
        }
    }

}
