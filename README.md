# Symfony SMS library

This Symfony library intends to offer an abstraction between code and the actual SMS service. This allows us to switch between different strategy just by changing configuration.

SMSs are defined by extending the `SMSTemplate` class, and sent by calling `SMSService->sendSMS(template)` method.

## Requirements

* `PHP >= 7.1`
* `symfony >= 4.0`


## Installation

```
composer require beapp/smser-core
```

Then choose one of the following transport layer :

**Direct calls :**
```
composer require beapp/smser-transport-esendex
```

**Send command to worker :**
```
composer require beapp/smser-transport-beanstalk
composer require beapp/smser-transport-rabbitmq
```


## Getting started !

In this sample, we'll use the Esendex transport.

Declare your transport and `SMSService` as Symfony's service.

```
  app.smser:
    class: Beapp\SMS\Core\SMSService
    arguments:
      - '@app.smser.transport.esendex'
      - '@router'
      - '@translator'
      - '%esendex_sender_name%'
      
  app.smser.transport.esndex:
    class: Beapp\SMS\Transport\EsendexSMSTransport
    arguments:
      - '%env(ESENDEX_ACCOUNT_REF)%'
      - '%env(ESENDEX_USERNAME)%'
      - '%env(ESENDEX_PASSWORD)%'
```

Implement a template for your specific SMS to send.

```
class AccountValidationSMSTemplate implements SMSTemplate
{
    /** @var string */
    private $phoneNumber;
    /** @var string */
    private $fullName;
    /** @var string */
    private $token;

    public function __construct(string $phoneNumber, string $fullName, string $token)
    {
        $this->phoneNumber = $phoneNumber;
        $this->fullName = $fullName;
        $this->token = $token;
    }

    public function build(RouterInterface $router, TranslatorInterface $translator): SMS
    {
        $url = $router->generate('account_validate', ['token' => $this->token], UrlGeneratorInterface::ABSOLUTE_URL);

        return new Mail(
            $this->phoneNumber,
            $this->fullName,
            $translator->trans('sms.validation.content', [
                '%name%' => $this->fullName,
                '%url%' => $url,
            ])
        );
    }
}
```

Send the sms from your code.

```
$smsService->sendSMS(new AccountValidationSMSTemplate('012345679', 'John Doe', 'activation-token'));
```
