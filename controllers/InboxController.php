<?php

use Aws\Sns\Message as AwsSnsMessage;
use Aws\Sns\MessageValidator as AwsSnsMessageValidator;
use SnsSubscriber\Model\Topic;
use SnsSubscriber\Model\Notification;
use Pimcore\Logger;

class InboxController extends \Pimcore\Controller\Action\Webservice
{

    /**
     * @var Topic
     */
    private $_topic;

    /**
     * The default endpoint
     */
    public function indexAction()
    {
        if (!$this->getRequest()->isPost()) {
            http_response_code(405);
            die;
        }

        try {
            $message = AwsSnsMessage::fromRawPostData();
            $validator = new AwsSnsMessageValidator();
            $validator->validate($message);

            if ($message['Type'] === 'SubscriptionConfirmation') {
                $this->confirmSubscription($message);
            } else if ($message['Type'] === 'UnsubscribeConfirmation') {
                $this->confirmUnsubscription($message);
            } else {
                $this->saveNotification($message);
            }

        } catch (Exception $e) {
            http_response_code(404);
            die;
        }


    }

    protected function confirmSubscription(AwsSnsMessage $message)
    {
        $topicArnHash = Topic::generateHashFromTopicArn($message['TopicArn']);
        $topic = Topic::getByTopicArnHash($topicArnHash);

        if (!$this->assertTopic($topic)) {
            http_response_code(404);
            die;
        }

        file_get_contents($message['SubscribeURL']);

        $topic->setSubscribed(true);
        $topic->save();
    }

    protected function confirmUnsubscription(AwsSnsMessage $message)
    {
        $topicArnHash = Topic::generateHashFromTopicArn($message['TopicArn']);
        $topic = Topic::getByTopicArnHash($topicArnHash);

        if (!$this->assertTopic($message, $topic)) {
            http_response_code(404);
            die;
        }

        // yes that's strange its the same URL
        file_get_contents($message['SubscribeURL']);

        $topic->setSubscribed(false);
        $topic->save();
    }

    protected function saveNotification($message)
    {
        $topicArnHash = Topic::generateHashFromTopicArn($message['TopicArn']);
        $topic = Topic::getByTopicArnHash($topicArnHash);

        if (!$this->assertTopic($message, $topic, true)) {
            http_response_code(404);
            die;
        }

        $notification = new Notification();
        $notification->setMessage($message['Message']);
        $notification->setMessageId($message['MessageId']);
        $notification->setTimestamp($message['Timestamp']);
        $notification->setTopicId($topic->getId());
        $notification->setSignature($message['Signature']);
        $notification->setSignatureVersion($message['SignatureVersion']);
        $notification->setCreationDate(time());
        $notification->setSubject($message['Subject']);
        $notification->save();

    }

    /**
     * @param AwsSnsMessage $message
     * @param null|Topic $topic
     * @param bool $checkSubscribed
     * @return bool
     */
    protected function assertTopic($message, $topic=null, $checkSubscribed=false)
    {
        if (!$topic) {
            Logger::warning(sprintf('SnsSubscriber topic [%s] not found ', $message['TopicArn']));
            return false;
        }

        if ($checkSubscribed === true && !$topic->isSubscribed()) {
            Logger::warning(sprintf('SnsSubscriber topic [%s] is not subscribed ', $message['TopicArn']));
            return false;
        }

        return true;
    }


}