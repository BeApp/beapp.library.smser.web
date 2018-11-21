<?php

namespace Beapp\SMS\Core;

class SMS implements \JsonSerializable
{
    /** @var string */
    private $recipient;
    /** @var string */
    private $content;
    /** @var string|null */
    private $sender;
    private $data = [];

    /**
     * SMS constructor.
     * @param string $recipient
     * @param string $content
     * @param null|string $sender
     * @param array $data
     */
    public function __construct(string $recipient, string $content, ?string $sender = null, array $data = [])
    {
        $this->recipient = $recipient;
        $this->content = $content;
        $this->sender = $sender;
        $this->data = $data;
    }

    public static function jsonDeserialize(array $data): self
    {
        $sms = new self(
            $data['recipient'],
            $data['content']
        );
        if ($sender = $data['sender']) {
            $sms->setSender($sender);
        }
        if ($data = $data['data']) {
            $sms->setData($data);
        }
        return $sms;
    }

    public function jsonSerialize(): array
    {
        return [
            'recipient' => $this->getRecipient(),
            'content' => $this->getContent(),
            'sender' => $this->getSender(),
            'data' => $this->getData(),
        ];
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return null|string
     */
    public function getSender(): ?string
    {
        return $this->sender;
    }

    /**
     * @param null|string $sender
     */
    public function setSender(?string $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

}

