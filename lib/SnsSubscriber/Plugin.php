<?php
/**
 * Plugin
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md
 * file distributed with this source code.
 *
 * @copyright  Copyright (c) 2014-2017 Gather Digital Ltd (https://www.gatherdigital.co.uk)
 * @license    https://www.gatherdigital.co.uk/license     GNU General Public License version 3 (GPLv3)
 */

namespace SnsSubscriber;

use Pimcore\API\Plugin as PluginLib;

class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{

    /**
     * @return bool
     */
    public static function install()
    {
        $db = \Pimcore\Db::get();

        $db->query("CREATE TABLE `plugin_sns_subscriber_notifications` (
          `message` longtext NOT NULL,
          `messageId` char(26) PRIMARY KEY,
          `topicId` int(11) NOT NULL,
          `signature` text NOT NULL,
          `signatureVersion` varchar(255) NULL,
          `subject` varchar(255) NULL,
          `timestamp` varchar(255) NULL,
          `creationDate` bigint(20) NOT NULL
        ) COMMENT='';");

        $db->query("CREATE TABLE `plugin_sns_subscriber_topics` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `topicArn` text NOT NULL,
          `topicArnHash` varchar(32) NOT NULL,
          `subscribed` tinyint(1)
        ) COMMENT='';");

        return "SNS Subscriber Plugin installed!";
    }

    public static function uninstall()
    {
        $db = \Pimcore\Db::get();
        $db->query("DROP TABLE `plugin_sns_subscriber_notifications`");
        $db->query("DROP TABLE `plugin_sns_subscriber_topics`");

        return 'SNS Subscriber Plugin Removed!';
    }

    public static function isInstalled()
    {
        return \Pimcore\Db::get()->query("SHOW TABLES LIKE 'plugin_sns_subscriber_notifications'")->rowCount() > 0;
    }

}
