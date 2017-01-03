<?php

namespace SnsSubscriber\Model;

class Topic extends \Pimcore\Model\AbstractModel
{

    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $topicArn
     */
    public $topicArn;

    /**
     * @var string $topicArnHash
     */
    public $topicArnHash;

    /**
     * @var bool $subscribed
     */
    public $subscribed;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Topic
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTopicArn()
    {
        return $this->topicArn;
    }

    /**
     * @param string $topicArn
     * @return Topic
     */
    public function setTopicArn($topicArn)
    {
        $this->topicArn = $topicArn;

        return $this;
    }

    /**
     * @return string
     */
    public function getTopicArnHash()
    {
        return $this->topicArnHash;
    }

    /**
     * @param string $topicArnHash
     * @return Topic
     */
    public function setTopicArnHash($topicArnHash)
    {
        $this->topicArnHash = $topicArnHash;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSubscribed()
    {
        return $this->subscribed;
    }

    /**
     * @param boolean $subscribed
     * @return Topic
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = (bool) $subscribed;

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
     * @return Topic
     */
    public static function getById($id)
    {
        $obj = new self;

        try {
            $obj->getDao()->getById($id);
        } catch(\Exception $w) {
            return null;
        }

        return $obj;
    }

    /**
     * Returns a Topic by it's Arn Hash
     * @param string $id
     * @return Topic
     */
    public static function getByTopicArnHash($arnHash)
    {
        $obj = new self;

        try {
            $obj->getDao()->getByTopicArnHash($arnHash);
        } catch(\Exception $w) {
            return null;
        }

        return $obj;
    }

    /**
     * @param string $topicArn
     * @return string
     */
    public static function generateHashFromTopicArn($topicArn)
    {
        return md5($topicArn);
    }


}