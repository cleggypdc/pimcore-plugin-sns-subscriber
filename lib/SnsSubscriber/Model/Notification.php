<?php

namespace SnsSubscriber\Model;

class Notification extends \Pimcore\Model\AbstractModel
{
    /**
     * @var string $message
     */
    public $message;

    /**
     * @var string $messageId
     */
    public $messageId;

    /**
     * @var int $topicId
     */
    public $topicId;

    /**
     * @var string $signature
     */
    public $signature;

    /**
     * @var string $signatureVersion
     */
    public $signatureVersion;

    /**
     * @var string $subject
     */
    public $subject;

    /**
     * @var string $timestamp
     */
    public $timestamp;

    /**
     * @var int $creationDate
     */
    public $creationDate;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param string $messageId
     * @return Notification
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTopicId()
    {
        return $this->topicId;
    }

    /**
     * @param int $topicId
     * @return Notification
     */
    public function setTopicId($topicId)
    {
        $this->topicId = (int) $topicId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     * @return Notification
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureVersion()
    {
        return $this->signatureVersion;
    }

    /**
     * @param string $signatureVersion
     * @return Notification
     */
    public function setSignatureVersion($signatureVersion)
    {
        $this->signatureVersion = $signatureVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Notification
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     * @return Notification
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param int $creationDate
     * @return Notification
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = (int) $creationDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        return $this->getDao()->save();
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        return $this->getDao()->delete();
    }

    /**
     * Returns a Topic by it's id
     * @param integer $id
     * @return Notification
     */
    public static function getByMessageId($messageId)
    {
        $obj = new self;

        try {
            $obj->getDao()->getByMessageId($messageId);
        } catch(\Exception $w) {
            return null;
        }

        return $obj;
    }


}
