<?php

namespace Beapp\SMS\Core\Template;

use Beapp\SMS\Core\SMS;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface SMSTemplate defines the sms to send
 */
interface SMSTemplate
{
    /**
     * Call by {@link SMSService} to build a {@link SMS} instance.
     *
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @return SMS
     */
    public function build(RouterInterface $router, TranslatorInterface $translator): SMS;
}
