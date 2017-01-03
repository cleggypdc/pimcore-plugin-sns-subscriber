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

namespace SnsSubscriber\Model\Topic;

class Dao extends \Pimcore\Model\Dao\AbstractDao
{

    /**
     * @var string $tableName
     */
    protected $tableName = 'plugin_sns_subscriber_topic';

    public function save()
    {

        $vars           = get_object_vars($this->model);
        $buffer         = [];
        $validColumns   = $this->getValidTableColumns($this->tableName);

        if(count($vars)) {
            foreach ($vars as $k => $v) {

                if (!in_array($k, $validColumns)) {
                    continue;
                }

                if ($k == 'id') {
                    continue;
                }


                $getter = "get" . ucfirst($k);

                if (!is_callable([$this->model, $getter])) {
                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int) $value;
                }

                $buffer[$k] = $value;

            }
        }

        if ($this->model->getId() !== null) {
            $where = ['id = ?' => $this->model->getId()];
            $result = $this->db->update($this->tableName, $buffer, $where);
            return;
        }

        $this->db->insert($this->tableName, $buffer);
        $this->model->setId($this->db->lastInsertId());

        return;
    }

    public function delete()
    {
        $this->db->delete($this->tableName, $this->db->quoteInto("id = ?", $this->model->getId()));
    }

    /**
     * @param integer $id
     * @throws \Exception
     */
    public function getById($id)
    {

        if ($id === null) {
            throw new \Exception('getById requirements not met');
        }

        $this->model->setId($id);

        $data = $this->db->fetchRow(
            "SELECT * FROM {$this->tableName} WHERE id = ?",
            [$this->model->getId()]
        );

        if (!$data["id"]) {
            throw new \Exception('No Topic was found with the given id');
        }

        $this->assignVariablesToModel($data);
    }

    /**
     * @param integer $localId
     * @throws \Exception
     */
    public function getByTopicArnHash($topicArnHash)
    {
        if ($topicArnHash === null) {
            throw new \Exception('getByTopicArnHash requirements not met');
        }

        $this->model->setTopicArnHash($topicArnHash);

        $data = $this->db->fetchRow(
            "SELECT * FROM {$this->tableName} WHERE topicArnHash = ?",
            [$this->model->getTopicArnHash()]
        );

        if (!$data["id"]) {
            throw new \Exception('No Topic was found with the given topicArnHash');
        }

        $this->assignVariablesToModel($data);
    }


}