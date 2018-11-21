<?php

namespace Beapp\SMS\Core;

use Beapp\SMS\Core\Template\SMSTemplate;
use Beapp\SMS\Core\Transport\SMSTransport;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SMSService
 */
class SMSService
{

    /** @var SMSTransport $smsTransport */
    private $smsTransport;

    /** @var RouterInterface $router */
    private $router;

    /** @var TranslatorInterface $translator */
    private $translator;

    /** @var string */
    private $defaultSender;

    /**
     * MailerService constructor.
     * @param SMSTransport $smsTransport
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param string $defaultSender
     */
    public function __construct(
        SMSTransport $smsTransport,
        RouterInterface $router,
        TranslatorInterface $translator,
        string $defaultSender
    ) {
        $this->smsTransport = $smsTransport;
        $this->router = $router;
        $this->translator = $translator;
        $this->defaultSender = $defaultSender;
    }

    /**
     * Build a {@link SMS} instance from the given {@link SMSTemplate} and dispatch it to the configured {@link SMSTransport}.
     * The {@link SMSTransport} offers an abstraction to send an SMS thought different channel.
     *
     * @param SMSTemplate $smsTemplate
     * @throws SMSException
     */
    public function sendSMS(SMSTemplate $smsTemplate)
    {
        $sms = $smsTemplate->build($this->router, $this->translator);

        if (empty($sms->getSender())) {
            $sms->setSender($this->defaultSender);
        }

        $this->smsTransport->sendSMS($sms);
    }

}
