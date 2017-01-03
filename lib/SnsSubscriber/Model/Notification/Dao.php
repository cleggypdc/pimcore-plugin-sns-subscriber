<?php

/**
 * Dao
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md
 * file distributed with this source code.
 *
 * @copyright  Copyright (c) 2014-2017 Gather Digital Ltd (https://www.gatherdigital.co.uk)
 * @license    https://www.gatherdigital.co.uk/license     GNU General Public License version 3 (GPLv3)
 */

namespace SnsSubscriber\Model\Notification;

class Dao extends \Pimcore\Model\Dao\AbstractDao
{

    /**
     * @var string $tableName
     */
    protected $tableName = 'plugin_sns_subscriber_notification';

    /**
     * Save object to database
     *
     * @return bool
     */
    public function save()
    {
        $dataAttributes = get_object_vars($this->model);
        $data = [];

        foreach ($dataAttributes as $key => $value) {
            if (in_array($key, $this->getValidTableColumns($this->tableName))) {
                $data[$key] = $value;
            }
        }

        $this->db->insertOrUpdate($this->tableName, $data);

        return true;
    }

    /**
     * Deletes object from database
     *
     * @return void
     */
    public function delete()
    {
        $this->db->delete($this->tableName, $this->db->quoteInto("messageId = ?", $this->model->getMessageId()));
    }

    /**
     * @param string $messageId
     * @throws \Exception
     */
    public function getByMessageId($messageId)
    {

        if ($messageId === null) {
            throw new \Exception('getByMessageId requirements not met');
        }

        $this->model->setMessageId($messageId);

        $data = $this->db->fetchRow(
            "SELECT * FROM {$this->tableName} WHERE messageId = ?",
            [$this->model->getMessageId()]
        );

        if (!$data["messageId"]) {
            throw new \Exception('No Topic was found with the given messageId');
        }

        $this->assignVariablesToModel($data);
    }


}