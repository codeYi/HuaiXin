/*
Navicat MySQL Data Transfer

Source Server         : li-5.6
Source Server Version : 50638
Source Host           : 192.168.10.112:1521
Source Database       : xgb_nd

Target Server Type    : MYSQL
Target Server Version : 50638
File Encoding         : 65001

Date: 2018-04-04 17:36:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xgjw_questionnaire
-- ----------------------------
DROP TABLE IF EXISTS `xgjw_questionnaire`;
CREATE TABLE `xgjw_questionnaire` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `describe` tinytext COMMENT '问卷描述',
  `sub_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `number` varchar(50) NOT NULL COMMENT '发布者',
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1:开启 0关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='问卷调查主表';

-- ----------------------------
-- Table structure for xgjw_questionnaire_answers
-- ----------------------------
DROP TABLE IF EXISTS `xgjw_questionnaire_answers`;
CREATE TABLE `xgjw_questionnaire_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(50) NOT NULL COMMENT '学生工号',
  `questionnaire_id` int(10) unsigned NOT NULL COMMENT '问卷ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='调查问卷答案';

-- ----------------------------
-- Table structure for xgjw_questionnaire_option
-- ----------------------------
DROP TABLE IF EXISTS `xgjw_questionnaire_option`;
CREATE TABLE `xgjw_questionnaire_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL COMMENT '题目id',
  `option` varchar(300) NOT NULL COMMENT '选项',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for xgjw_questionnaire_questions
-- ----------------------------
DROP TABLE IF EXISTS `xgjw_questionnaire_questions`;
CREATE TABLE `xgjw_questionnaire_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questionnaire_id` smallint(5) unsigned NOT NULL COMMENT '问卷ID',
  `title` varchar(300) NOT NULL DEFAULT '' COMMENT '题目',
  `label` varchar(300) NOT NULL DEFAULT '' COMMENT '矩阵标签',
  `type` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '问卷题目类型 1:单选2:多选3:问答4:矩阵',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COMMENT='调查问卷题目';

-- ----------------------------
-- Table structure for xgjw_questionnaire_reply
-- ----------------------------
DROP TABLE IF EXISTS `xgjw_questionnaire_reply`;
CREATE TABLE `xgjw_questionnaire_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questionnaire_id` int(10) unsigned NOT NULL COMMENT '问卷id',
  `topic_id` int(10) unsigned NOT NULL COMMENT '题目id',
  `content` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '回答内容',
  `type` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '问卷题目类型 1:单选2:多选3:问答4:矩阵',
  `label` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '矩阵标签',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COMMENT='问卷答题表';
