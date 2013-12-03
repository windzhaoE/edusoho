<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131203120334 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // installed_applications edusoho本地的应用商城的表结构
        
    	$this->addSQL("
    		CREATE TABLE `installed_applications` (
			  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '应用Id',
			  `logo` varchar(200) NOT NULL COMMENT '应用logo',
			  `category` enum('theme','none') NOT NULL COMMENT '所属类别,可以自行添加',
			  `author_name` varchar(100) NOT NULL COMMENT '作者名称',
			  `author_email` varchar(100) NOT NULL COMMENT '作者邮箱',
			  `author_avatar` varchar(200) NOT NULL COMMENT '作者头像',
			  `author_website` varchar(200) NOT NULL COMMENT '作者网站',
			  `cname` varchar(255) NOT NULL COMMENT '应用的名称',
			  `alias` varchar(100) NOT NULL COMMENT '应用中文别名',
			  `status` enum('installed','uninstalled') NOT NULL COMMENT '当前状态，已安装还是已卸载',
			  `ename` varchar(255) NOT NULL COMMENT '应用的英文名称',
			  `installTime` int(11) NOT NULL DEFAULT '0' COMMENT '安装时间或者升级时间',
			  `uninstallTime` int(11) NOT NULL DEFAULT '0' COMMENT '应用卸载的时间',
			  `version` varchar(50) NOT NULL DEFAULT '0' COMMENT '本应用当前版本',
			  `fromVersion` varchar(50) NOT NULL DEFAULT '0' COMMENT '应用的起始版本',
			  `description` varchar(255) NOT NULL COMMENT '应用描述',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    		");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs

    }
}
