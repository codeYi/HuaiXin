/*
Navicat MySQL Data Transfer

Source Server         : 东哥
Source Server Version : 50722
Source Host           : 192.168.10.111:3306
Source Database       : sinocrew

Target Server Type    : MYSQL
Target Server Version : 50722
File Encoding         : 65001

Date: 2018-06-15 16:29:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sino_access
-- ----------------------------
DROP TABLE IF EXISTS `sino_access`;
CREATE TABLE `sino_access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色id',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点id',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色权限表';

-- ----------------------------
-- Records of sino_access
-- ----------------------------
INSERT INTO `sino_access` VALUES ('6', '16', '0', null);
INSERT INTO `sino_access` VALUES ('6', '15', '0', null);
INSERT INTO `sino_access` VALUES ('6', '14', '0', null);
INSERT INTO `sino_access` VALUES ('6', '13', '0', null);
INSERT INTO `sino_access` VALUES ('6', '12', '0', null);
INSERT INTO `sino_access` VALUES ('6', '11', '0', null);
INSERT INTO `sino_access` VALUES ('6', '10', '0', null);
INSERT INTO `sino_access` VALUES ('7', '6', '0', null);
INSERT INTO `sino_access` VALUES ('7', '7', '0', null);
INSERT INTO `sino_access` VALUES ('7', '8', '0', null);
INSERT INTO `sino_access` VALUES ('7', '23', '0', null);
INSERT INTO `sino_access` VALUES ('7', '24', '0', null);
INSERT INTO `sino_access` VALUES ('7', '25', '0', null);
INSERT INTO `sino_access` VALUES ('7', '26', '0', null);
INSERT INTO `sino_access` VALUES ('7', '27', '0', null);
INSERT INTO `sino_access` VALUES ('7', '28', '0', null);
INSERT INTO `sino_access` VALUES ('7', '29', '0', null);
INSERT INTO `sino_access` VALUES ('7', '30', '0', null);
INSERT INTO `sino_access` VALUES ('7', '31', '0', null);
INSERT INTO `sino_access` VALUES ('7', '32', '0', null);
INSERT INTO `sino_access` VALUES ('7', '33', '0', null);
INSERT INTO `sino_access` VALUES ('8', '2', '0', null);
INSERT INTO `sino_access` VALUES ('8', '4', '0', null);
INSERT INTO `sino_access` VALUES ('8', '18', '0', null);
INSERT INTO `sino_access` VALUES ('8', '19', '0', null);
INSERT INTO `sino_access` VALUES ('8', '20', '0', null);
INSERT INTO `sino_access` VALUES ('8', '21', '0', null);
INSERT INTO `sino_access` VALUES ('8', '35', '0', null);
INSERT INTO `sino_access` VALUES ('8', '36', '0', null);
INSERT INTO `sino_access` VALUES ('8', '37', '0', null);
INSERT INTO `sino_access` VALUES ('8', '38', '0', null);
INSERT INTO `sino_access` VALUES ('8', '39', '0', null);
INSERT INTO `sino_access` VALUES ('6', '58', '0', null);

-- ----------------------------
-- Table structure for sino_agent
-- ----------------------------
DROP TABLE IF EXISTS `sino_agent`;
CREATE TABLE `sino_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理人id',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL COMMENT '代理结束日期',
  `node_id` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '节点id',
  `time` datetime NOT NULL COMMENT '系统时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态 1代理中 2已撤销 3已结束',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='权限代理表';

-- ----------------------------
-- Records of sino_agent
-- ----------------------------
INSERT INTO `sino_agent` VALUES ('5', '1', '44', '2018-06-11', '2018-06-12', '', '2018-06-12 16:50:25', '3');
INSERT INTO `sino_agent` VALUES ('6', '1', '41', '2018-06-11', '2018-06-12', '', '2018-06-12 16:57:18', '3');

-- ----------------------------
-- Table structure for sino_borrow
-- ----------------------------
DROP TABLE IF EXISTS `sino_borrow`;
CREATE TABLE `sino_borrow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mariner_id` int(10) NOT NULL COMMENT '船员id',
  `date` date NOT NULL COMMENT '借款日期',
  `tally` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '记账月份',
  `currency` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '币种 美元/人民币',
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '借款金额',
  `repayment` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '还款金额',
  `reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '借款原因',
  `changer` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '变更人',
  `change_date` date DEFAULT NULL COMMENT '变更日期',
  `if_settle` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否结清 1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='借还款表';

-- ----------------------------
-- Records of sino_borrow
-- ----------------------------
INSERT INTO `sino_borrow` VALUES ('32', '32', '2018-05-17', '2018-05', '人民币', '5000.00', '0.00', '', '刘丽娜', '2018-06-13', '0');
INSERT INTO `sino_borrow` VALUES ('33', '33', '2018-05-17', '2018-06', '人民币', '4500.00', '0.00', 'test', '刘丽娜', '2018-06-13', '0');
INSERT INTO `sino_borrow` VALUES ('34', '34', '2018-06-05', '2018-06', '人民币', '4600.00', '300.00', '临时借用', '刘丽娜', '2018-06-13', '0');
INSERT INTO `sino_borrow` VALUES ('35', '31', '2018-06-13', '2018-06', '人民币', '110.00', '0.00', '意外险', '刘丽娜', '2018-06-13', '0');
INSERT INTO `sino_borrow` VALUES ('36', '41', '2018-06-14', '2018-06', '人民币', '1000.00', '0.00', '意外险', '刘丽娜', '2018-06-14', '0');
INSERT INTO `sino_borrow` VALUES ('37', '34', '2018-06-12', '2018-06', '人民币', '1000.00', '1000.00', '意外险', '刘丽娜', '2018-06-13', '1');
INSERT INTO `sino_borrow` VALUES ('38', '34', '2018-06-14', '2018-06', '美元', '200.00', '200.00', '临时', '刘丽娜', '2018-06-13', '1');
INSERT INTO `sino_borrow` VALUES ('39', '41', '2018-06-12', '2018-06', '美元', '100.00', '0.00', '生活', '刘丽娜', '2018-06-13', '0');

-- ----------------------------
-- Table structure for sino_borrow_record
-- ----------------------------
DROP TABLE IF EXISTS `sino_borrow_record`;
CREATE TABLE `sino_borrow_record` (
  `pid` int(10) unsigned NOT NULL COMMENT '借款id',
  `date` date NOT NULL COMMENT '还款时间',
  `currency` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '货币类型  美元或人民币',
  `money` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '还款金额',
  `changer` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '变更人',
  `change_date` date NOT NULL COMMENT '变更时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='还款记录表';

-- ----------------------------
-- Records of sino_borrow_record
-- ----------------------------
INSERT INTO `sino_borrow_record` VALUES ('34', '2018-06-13', '人民币', '100.00', '刘丽娜', '2018-06-13');
INSERT INTO `sino_borrow_record` VALUES ('34', '2018-06-13', '人民币', '200.00', '刘丽娜', '2018-06-13');
INSERT INTO `sino_borrow_record` VALUES ('37', '2018-06-13', '人民币', '1000.00', '刘丽娜', '2018-06-13');
INSERT INTO `sino_borrow_record` VALUES ('38', '2018-06-13', '美元', '200.00', '刘丽娜', '2018-06-13');

-- ----------------------------
-- Table structure for sino_borrow_sure
-- ----------------------------
DROP TABLE IF EXISTS `sino_borrow_sure`;
CREATE TABLE `sino_borrow_sure` (
  `month` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '月份',
  `rmb_amount` decimal(11,2) NOT NULL COMMENT '人民币收款',
  `rmb_repayment` decimal(11,2) NOT NULL COMMENT '人民币还款',
  `usd_amount` decimal(11,2) NOT NULL COMMENT '美元收款',
  `usd_repayment` decimal(11,2) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='借还款对账表';

-- ----------------------------
-- Records of sino_borrow_sure
-- ----------------------------
INSERT INTO `sino_borrow_sure` VALUES ('2018-05', '5000.00', '0.00', '0.00', '0.00', '2018-06-13 12:01:00');

-- ----------------------------
-- Table structure for sino_business
-- ----------------------------
DROP TABLE IF EXISTS `sino_business`;
CREATE TABLE `sino_business` (
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '船东id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船东-业务主管表';

-- ----------------------------
-- Records of sino_business
-- ----------------------------
INSERT INTO `sino_business` VALUES ('24', '45');

-- ----------------------------
-- Table structure for sino_charge
-- ----------------------------
DROP TABLE IF EXISTS `sino_charge`;
CREATE TABLE `sino_charge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mariner_id` int(11) unsigned NOT NULL COMMENT '船员id',
  `date` date NOT NULL COMMENT '收费日期',
  `month` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '收费月份',
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '收款金额',
  `surplus` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '剩余金额',
  `changer` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '变更人',
  `change_date` date NOT NULL COMMENT '变更时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='收款表';

-- ----------------------------
-- Records of sino_charge
-- ----------------------------
INSERT INTO `sino_charge` VALUES ('23', '41', '2018-06-12', '2018-06', '50000.00', '45000.00', '刘丽娜', '2018-06-13');
INSERT INTO `sino_charge` VALUES ('24', '31', '2018-06-13', '2018-06', '4500.00', '4500.00', '刘丽娜', '2018-06-13');

-- ----------------------------
-- Table structure for sino_charge_record
-- ----------------------------
DROP TABLE IF EXISTS `sino_charge_record`;
CREATE TABLE `sino_charge_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '收费id',
  `money` decimal(8,2) NOT NULL COMMENT '还款金额',
  `time` date NOT NULL COMMENT '还款时间',
  `changer` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '变更人',
  `change_date` date NOT NULL,
  `month` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '还款月份',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='收费还款记录表';

-- ----------------------------
-- Records of sino_charge_record
-- ----------------------------
INSERT INTO `sino_charge_record` VALUES ('24', '23', '5000.00', '2018-06-13', '刘丽娜', '2018-06-13', '2018-06');

-- ----------------------------
-- Table structure for sino_charge_sure
-- ----------------------------
DROP TABLE IF EXISTS `sino_charge_sure`;
CREATE TABLE `sino_charge_sure` (
  `month` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '对账月份',
  `amount` decimal(12,2) NOT NULL,
  `repayment` decimal(12,2) NOT NULL,
  `time` datetime NOT NULL COMMENT '对账时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='收费对账表';

-- ----------------------------
-- Records of sino_charge_sure
-- ----------------------------
INSERT INTO `sino_charge_sure` VALUES ('2018-06', '54500.00', '5000.00', '2018-06-13 09:15:25');

-- ----------------------------
-- Table structure for sino_expense
-- ----------------------------
DROP TABLE IF EXISTS `sino_expense`;
CREATE TABLE `sino_expense` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mariner_id` int(10) NOT NULL,
  `date` date NOT NULL COMMENT '报销时间',
  `month` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '报销月份',
  `shipowner_id` int(10) unsigned NOT NULL COMMENT '船东id',
  `vessel_id` int(10) NOT NULL COMMENT '船名id',
  `address` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '报销地',
  `fleet` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '船队',
  `reason` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '出差事由',
  `explain` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '出差事由说明',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '报销统计',
  `really` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实际报销金额',
  `over_date` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '财务签批日期',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '支付方式 1代表 “现金”   2代表“转账”',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '签核状态  0签核 2未通过  1已通过',
  `warn` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '通过提醒 1提醒 0不提醒',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船员报销主表';

-- ----------------------------
-- Records of sino_expense
-- ----------------------------
INSERT INTO `sino_expense` VALUES ('111', '41', '2018-06-13', '2018-06', '24', '10', '北京', 'test', '上下船', 'test', '1000.00', '900.00', '2018-06-13', '1', '1', '1');
INSERT INTO `sino_expense` VALUES ('112', '34', '2018-06-13', '2018-06', '24', '11', '武汉', 'test2', '签证', 'test2', '700.00', '0.00', '2018-06-13', '1', '1', '1');
INSERT INTO `sino_expense` VALUES ('115', '39', '2018-06-14', '2018-06', '24', '12', '上海', 'test3', '培训', 'test3aa1', '707.00', '0.00', '', '1', '0', '0');
INSERT INTO `sino_expense` VALUES ('119', '39', '2018-06-13', '2018-06', '24', '12', '上海', 'test3', '培训', 'test3', '1607.00', '1047.00', '2018-06-13', '1', '1', '1');
INSERT INTO `sino_expense` VALUES ('120', '31', '2018-06-13', '2018-06', '24', '10', '北京', 'aa', '上下船', 'sd', '200.00', '0.00', '', '1', '0', '0');
INSERT INTO `sino_expense` VALUES ('121', '41', '2018-06-14', '2018-06', '25', '11', '上海', 'A', '上下船', '阿萨德阿萨德', '12817.00', '0.00', '', '1', '0', '0');

-- ----------------------------
-- Table structure for sino_expense_assume
-- ----------------------------
DROP TABLE IF EXISTS `sino_expense_assume`;
CREATE TABLE `sino_expense_assume` (
  `pid` int(11) NOT NULL COMMENT '主表id',
  `shiper_traffic` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东交通费',
  `shiper_hotel` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东住宿费',
  `shiper_city` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东市内交通费',
  `shiper_examination` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东体检费',
  `shiper_train` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东培训费',
  `shiper_subsidy` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东补贴费',
  `shiper_else` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东其他费用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of sino_expense_assume
-- ----------------------------
INSERT INTO `sino_expense_assume` VALUES ('111', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_expense_assume` VALUES ('112', '100.00', '100.00', '100.00', '100.00', '200.00', '10.00', '201.00');
INSERT INTO `sino_expense_assume` VALUES ('119', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_expense_assume` VALUES ('120', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_expense_assume` VALUES ('115', '0.00', '0.00', '0.00', '0.00', '0.00', '2.00', '0.00');
INSERT INTO `sino_expense_assume` VALUES ('121', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');

-- ----------------------------
-- Table structure for sino_expense_data
-- ----------------------------
DROP TABLE IF EXISTS `sino_expense_data`;
CREATE TABLE `sino_expense_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL COMMENT '船员报销id',
  `date` date NOT NULL COMMENT '日期',
  `start_address` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '起始地点',
  `end_address` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '到达地点',
  `traffic_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交通费',
  `hotel_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '住宿费',
  `city_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市内交通费',
  `examination_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '体检费',
  `train_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '培训费',
  `subsidy_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '补贴费用',
  `else_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '其他费用',
  `assume` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '费用承担方',
  `number` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '电子发票编号',
  `remark` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船员报销数据表';

-- ----------------------------
-- Records of sino_expense_data
-- ----------------------------
INSERT INTO `sino_expense_data` VALUES ('44', '111', '2018-06-07', 'aaa', 'bbbb', '1000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '');
INSERT INTO `sino_expense_data` VALUES ('45', '112', '2018-06-12', 'test2', 'test3', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '0', '200', '100');
INSERT INTO `sino_expense_data` VALUES ('47', '119', '2018-06-11', 'test3', 'test3', '101.00', '101.00', '101.00', '101.00', '101.00', '101.00', '101.00', '24', 'fp125', '1000');
INSERT INTO `sino_expense_data` VALUES ('48', '119', '2018-06-13', '湖北', '广西', '100.00', '200.00', '100.00', '100.00', '100.00', '200.00', '100.00', '25', 'fp124', '测试啊');
INSERT INTO `sino_expense_data` VALUES ('49', '120', '2018-06-06', 'asdf', 'asdf', '200.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '');
INSERT INTO `sino_expense_data` VALUES ('53', '115', '2018-06-11', 'test3', 'test3', '101.00', '101.00', '101.00', '101.00', '101.00', '101.00', '101.00', '24', '', '1000');
INSERT INTO `sino_expense_data` VALUES ('54', '121', '2018-06-13', '武汉', '北京', '12.00', '12.00', '123.00', '12312.00', '123.00', '123.00', '112.00', '', '', '');

-- ----------------------------
-- Table structure for sino_expense_debt
-- ----------------------------
DROP TABLE IF EXISTS `sino_expense_debt`;
CREATE TABLE `sino_expense_debt` (
  `pid` int(11) unsigned NOT NULL COMMENT '报销id',
  `date` date NOT NULL COMMENT '时间',
  `project` varchar(30) NOT NULL DEFAULT '' COMMENT '借款项目',
  `us_debt` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '外币欠款',
  `us_receipt` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '外币还款',
  `rmb_debt` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '人民币欠款',
  `rmb_receipt` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '人民币还款'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='船员报销欠款记录';

-- ----------------------------
-- Records of sino_expense_debt
-- ----------------------------
INSERT INTO `sino_expense_debt` VALUES ('112', '2018-06-13', '借款', '200.00', '0.00', '4600.00', '100.00');
INSERT INTO `sino_expense_debt` VALUES ('112', '2018-06-13', '社保欠款', '0.00', '0.00', '1270.00', '0.00');
INSERT INTO `sino_expense_debt` VALUES ('112', '2018-06-13', '意外险欠款', '0.00', '0.00', '1000.00', '0.00');

-- ----------------------------
-- Table structure for sino_expense_option
-- ----------------------------
DROP TABLE IF EXISTS `sino_expense_option`;
CREATE TABLE `sino_expense_option` (
  `pid` int(11) unsigned NOT NULL COMMENT '船员报销主表id',
  `traffic` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '交通费',
  `hotel` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '住宿费',
  `city` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '市内交通费',
  `examination` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '体检费',
  `train` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '培训费',
  `subsidy` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '补贴',
  `else` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '其他',
  `assume` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '费用承担方',
  `remark` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船员报销费用备注';

-- ----------------------------
-- Records of sino_expense_option
-- ----------------------------
INSERT INTO `sino_expense_option` VALUES ('111', '火车,16', '', '', '', '', '', '', '', '');
INSERT INTO `sino_expense_option` VALUES ('112', '火车,16', '北京,16', 'test2,15', 'test2,16', 'test2,15', 'test2,17', 'test2', 'test2', 'test2');
INSERT INTO `sino_expense_option` VALUES ('119', '火车,16', '北京,16', 'test3,17', 'test,16', 'tset,15', 'test,17', 'tste', 'tset', 'tset');
INSERT INTO `sino_expense_option` VALUES ('120', '火车,15', '', '', '', '', '', '', '', '');
INSERT INTO `sino_expense_option` VALUES ('115', '火车,16', '北京,16', 'test3,17', 'test,16', 'tset,15', 'test,17', 't', 't', 't');
INSERT INTO `sino_expense_option` VALUES ('121', '火车,16', '广州,16', ',16', ',16', ',15', '', '', '', '');

-- ----------------------------
-- Table structure for sino_insurance
-- ----------------------------
DROP TABLE IF EXISTS `sino_insurance`;
CREATE TABLE `sino_insurance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '意外险表',
  `mariner_id` int(11) NOT NULL COMMENT '船员id',
  `supplier_id` int(10) NOT NULL COMMENT '供应商id',
  `effect_time` date NOT NULL COMMENT '生效时间',
  `finish_time` date NOT NULL COMMENT '结束时间',
  `company` decimal(8,2) NOT NULL COMMENT '公司承担',
  `person` decimal(8,2) NOT NULL COMMENT '个人承担',
  `time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='意外险表';

-- ----------------------------
-- Records of sino_insurance
-- ----------------------------
INSERT INTO `sino_insurance` VALUES ('24', '31', '16', '2018-06-03', '2020-06-17', '120.00', '110.00', '2018-06-13 11:42:26');
INSERT INTO `sino_insurance` VALUES ('25', '41', '16', '2018-06-06', '2019-06-12', '100.00', '1000.00', '2018-06-14 08:38:55');

-- ----------------------------
-- Table structure for sino_insurance_borrow
-- ----------------------------
DROP TABLE IF EXISTS `sino_insurance_borrow`;
CREATE TABLE `sino_insurance_borrow` (
  `pid` int(10) unsigned NOT NULL COMMENT '意外险id',
  `borrow_id` int(10) unsigned NOT NULL COMMENT '借款id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='意外险借款中间表';

-- ----------------------------
-- Records of sino_insurance_borrow
-- ----------------------------
INSERT INTO `sino_insurance_borrow` VALUES ('24', '35');
INSERT INTO `sino_insurance_borrow` VALUES ('25', '36');

-- ----------------------------
-- Table structure for sino_insured
-- ----------------------------
DROP TABLE IF EXISTS `sino_insured`;
CREATE TABLE `sino_insured` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mariner_id` int(11) unsigned NOT NULL COMMENT '船员id',
  `area` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '参保地区',
  `starttime` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '开始时间',
  `fee_payable` varchar(3) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '是否补缴',
  `is_stop` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否停保 1是 0否',
  `add_month` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '当前月份',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='参保人员信息';

-- ----------------------------
-- Records of sino_insured
-- ----------------------------
INSERT INTO `sino_insured` VALUES ('19', '31', '武汉', '2018-04', '是', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('20', '31', '武汉', '2018-05', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('21', '32', '天津', '2018-06', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('22', '33', '北京', '2018-06', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('23', '34', '北京', '2018-06', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('24', '35', '北京', '2018-06', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('25', '36', '北京', '2018-06', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('26', '37', '北京', '2018-06', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('27', '38', '上海', '2018-06', '否', '0', '2018-06');
INSERT INTO `sino_insured` VALUES ('28', '39', '上海', '2018-06', '否', '0', '2018-06');

-- ----------------------------
-- Table structure for sino_insured_stop
-- ----------------------------
DROP TABLE IF EXISTS `sino_insured_stop`;
CREATE TABLE `sino_insured_stop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mariner_id` int(11) NOT NULL COMMENT '船员id',
  `area` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '地区',
  `starttime` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '停缴时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of sino_insured_stop
-- ----------------------------
INSERT INTO `sino_insured_stop` VALUES ('10', '31', '武汉', '2018-07');
INSERT INTO `sino_insured_stop` VALUES ('11', '35', '北京', '2018-10');

-- ----------------------------
-- Table structure for sino_invoice
-- ----------------------------
DROP TABLE IF EXISTS `sino_invoice`;
CREATE TABLE `sino_invoice` (
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '报销id',
  `number` varchar(30) NOT NULL DEFAULT '' COMMENT '电子发票编号',
  `is_mariner` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否是船员报销 1是 2不是'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电子发票信息';

-- ----------------------------
-- Records of sino_invoice
-- ----------------------------
INSERT INTO `sino_invoice` VALUES ('10', '123', '2');
INSERT INTO `sino_invoice` VALUES ('10', '4848', '2');
INSERT INTO `sino_invoice` VALUES ('10', '789', '2');
INSERT INTO `sino_invoice` VALUES ('11', '', '2');
INSERT INTO `sino_invoice` VALUES ('13', '12435678', '2');
INSERT INTO `sino_invoice` VALUES ('13', '546464', '2');
INSERT INTO `sino_invoice` VALUES ('14', '87897454216', '2');
INSERT INTO `sino_invoice` VALUES ('14', '87989465465', '2');
INSERT INTO `sino_invoice` VALUES ('14', '13215646465', '2');

-- ----------------------------
-- Table structure for sino_mariner
-- ----------------------------
DROP TABLE IF EXISTS `sino_mariner`;
CREATE TABLE `sino_mariner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'CID号码',
  `name` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '姓名',
  `password` char(32) COLLATE utf8_bin NOT NULL COMMENT '密码',
  `abbreviation` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '简称',
  `english` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '英文名',
  `id_number` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '身份证号码',
  `duty` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '职务',
  `manning_office` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '船员办公室',
  `fleet` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '船队',
  `owner_pool` mediumint(6) NOT NULL COMMENT '船东id',
  `vessel` mediumint(6) NOT NULL COMMENT '船名id',
  `time` datetime NOT NULL,
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用 1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船员表';

-- ----------------------------
-- Records of sino_mariner
-- ----------------------------
INSERT INTO `sino_mariner` VALUES ('31', 'XYS000001', '仲丹琴', '50e0e14e7cf34d92d37622d014366385', 'zdq', 'Zhong Danqin', '36020019840817600X', '船长', '自有船员', 'A队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('32', 'XYS000002', '叶博丹', '34500a93715bb54e057f070ed5ab0a34', 'ybd', 'Ye Bodan', '542226199403221696', '大副', '自有船员', 'A队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('33', 'XYS000003', '韩瑞敏', '5d4745082328f3c6cb0930ee958f2e17', 'hrm', 'Han Ruimin', '141100197808290987', '二副', '自有船员', 'A队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('34', 'XYS000004', '解政宇', 'd7a8188e1e7ce67b12ccf3867e277863', 'jzy', 'Jie Zhengyu', '610631198302087621', '三副', '自有船员', 'B队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('35', 'XYS000005', '窦盼巧', '1daf227a18eefa658929237014018f5c', 'pq', ' Panqiao', '130636199102165821', '大轮管', '自有船员', 'B队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('36', 'XYS000006', '费朵一', 'e1f8bdd18c9e617b34925c2697eadf7b', 'fdy', 'Fei Duoyi', '530522198909134971', '二轮管', '自有船员', 'B队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('37', 'XYS000007', '穆浩恒', '950ec7b03ff21d1a73c8094fb07afd3f', 'mhh', 'Mu Haoheng', '522628197608024153', '三轮管', '自有船员', 'B队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('38', 'XYS000008', '俞新华', 'fa4b3b36f53904944ce1877c3734a18f', 'yxh', 'Yu Xinhua', '542127197901225122', '三轮管', '自有船员', 'B队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('39', 'XYS000009', '童鹤', 'edc04a275e87ba378065dfbfdd05ff26', 'th', 'Tong He', '451481199007124029', '三轮管', '自有船员', 'B队', '24', '10', '2018-06-12 14:42:08', '0');
INSERT INTO `sino_mariner` VALUES ('40', 'XY1000017', '于飞阳', '47ebb89270140389814ec80a6b0f0d07', 'yfy', 'Yu Feiyang', '420525199211055786', '船长', '自由船员', 'A队', '25', '10', '2018-06-12 16:52:58', '0');
INSERT INTO `sino_mariner` VALUES ('41', 'XYS000004', '李现', '7b01934fccae430d830e27af79bb503d', 'lx', 'Li Xian', '421121199307274512', '二轮管', '分包方船员', 'A队', '25', '11', '2018-06-12 16:53:36', '0');
INSERT INTO `sino_mariner` VALUES ('42', 'XY12321312', '张腾', '5bd2026f128662763c532f2f4b6f2476', 'zt', 'Zhang Teng', 'P12345678', '二轮管', '分包方船员', 'A队', '25', '11', '2018-06-14 16:05:44', '0');

-- ----------------------------
-- Table structure for sino_menu
-- ----------------------------
DROP TABLE IF EXISTS `sino_menu`;
CREATE TABLE `sino_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '员工id',
  `title` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '功能名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='常用功能';

-- ----------------------------
-- Records of sino_menu
-- ----------------------------

-- ----------------------------
-- Table structure for sino_node
-- ----------------------------
DROP TABLE IF EXISTS `sino_node`;
CREATE TABLE `sino_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '节点名称 控制器，方法名',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '节点中文名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用状态 1启用  0禁用',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '50' COMMENT '排序值',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父节点id',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '节点类型  1表示模块  2表示控制器 3表示方法',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='节点表';

-- ----------------------------
-- Records of sino_node
-- ----------------------------
INSERT INTO `sino_node` VALUES ('1', '首页', 'index/*', '1', '', '100', '0', '2');
INSERT INTO `sino_node` VALUES ('2', '常用功能', 'index/menuList', '1', '', '50', '1', '3');
INSERT INTO `sino_node` VALUES ('3', '船员统计', '', '1', '', '50', '1', '3');
INSERT INTO `sino_node` VALUES ('4', '社保预算', '', '1', '', '50', '1', '3');
INSERT INTO `sino_node` VALUES ('5', '供应商统计', '', '1', '', '50', '1', '3');
INSERT INTO `sino_node` VALUES ('6', '报销统计', '', '1', '', '50', '1', '3');
INSERT INTO `sino_node` VALUES ('7', '借还款统计', '', '1', '', '50', '1', '3');
INSERT INTO `sino_node` VALUES ('8', '收费统计', '', '1', '', '50', '1', '3');
INSERT INTO `sino_node` VALUES ('9', '船员', 'mariner/*', '1', '', '99', '0', '2');
INSERT INTO `sino_node` VALUES ('10', '添加船员', 'mariner/addmariner', '1', '', '50', '9', '3');
INSERT INTO `sino_node` VALUES ('11', '导入家汇信息', 'mariner/importremittance', '1', '', '50', '9', '3');
INSERT INTO `sino_node` VALUES ('12', '导出人民币汇款', 'mariner/exportremittancecn', '1', '', '50', '9', '3');
INSERT INTO `sino_node` VALUES ('13', '禁用船员', 'mariner/forbidden', '1', '', '50', '9', '3');
INSERT INTO `sino_node` VALUES ('14', '重置船员密码', 'mariner/restpassword', '1', '', '50', '9', '3');
INSERT INTO `sino_node` VALUES ('15', '编辑基础信息', 'mariner/editmariner', '1', '', '50', '9', '3');
INSERT INTO `sino_node` VALUES ('16', '还款', '', '1', '', '50', '9', '3');
INSERT INTO `sino_node` VALUES ('17', '社保', 'social/*', '1', '', '98', '0', '2');
INSERT INTO `sino_node` VALUES ('18', '社保设置', 'social/listarea', '1', '', '50', '17', '3');
INSERT INTO `sino_node` VALUES ('19', '参保人员', 'social/listinsured', '1', '', '50', '17', '3');
INSERT INTO `sino_node` VALUES ('20', '社保信息', 'social/infoinsured', '1', '', '50', '17', '3');
INSERT INTO `sino_node` VALUES ('21', '社保对账', 'social/checkinsured', '1', '', '50', '17', '3');
INSERT INTO `sino_node` VALUES ('22', '报销', '', '1', '', '97', '0', '2');
INSERT INTO `sino_node` VALUES ('23', '船员差旅报销', '', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('24', '员工差旅报销', '', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('25', '办公费用报销', '', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('26', '船员差旅报销记录', 'expenses/listmariner', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('27', '员工差旅报销记录', 'expenses/listuser', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('28', '办公费用报销记录', 'expenses/listoffice', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('29', '差旅报销签批', 'expenses/signuser', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('30', '办公费用报销签批', 'expenses/signoffice', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('31', '船员差旅报销签批', 'expenses/signmariner', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('32', '报销统计', '', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('33', '报销模板', '', '1', '', '50', '22', '3');
INSERT INTO `sino_node` VALUES ('34', '借还款', '', '1', '', '96', '0', '2');
INSERT INTO `sino_node` VALUES ('35', '添加借款', 'borrow/addborrow', '1', '', '50', '34', '3');
INSERT INTO `sino_node` VALUES ('36', '编辑借款', 'borrow/editborrow', '1', '', '50', '34', '3');
INSERT INTO `sino_node` VALUES ('37', '还款', 'borrow/receiptinfo', '1', '', '50', '34', '3');
INSERT INTO `sino_node` VALUES ('38', '意外险', 'borrow/listinsurance', '1', '', '50', '34', '3');
INSERT INTO `sino_node` VALUES ('39', '借还款对账', 'borrow/infoborrow', '1', '', '50', '34', '3');
INSERT INTO `sino_node` VALUES ('40', '收费', '', '1', '', '95', '0', '2');
INSERT INTO `sino_node` VALUES ('41', '添加收款', 'charge/addcharge', '1', '', '50', '40', '3');
INSERT INTO `sino_node` VALUES ('42', '编辑收款', 'charge/editcharge', '1', '', '50', '40', '3');
INSERT INTO `sino_node` VALUES ('43', '还款', 'charge/repaymentinfo', '1', '', '50', '40', '3');
INSERT INTO `sino_node` VALUES ('44', '收款对账', 'charge/infocharge', '1', '', '50', '40', '3');
INSERT INTO `sino_node` VALUES ('45', '供应商', '', '1', '', '94', '0', '2');
INSERT INTO `sino_node` VALUES ('46', '添加供应商', 'supplier/addsupplier', '1', '', '50', '45', '3');
INSERT INTO `sino_node` VALUES ('47', '编辑供应商', 'supplier/editsupplier', '1', '', '50', '45', '3');
INSERT INTO `sino_node` VALUES ('48', '编辑实付金额', 'supplier/editInfo', '1', '', '50', '45', '3');
INSERT INTO `sino_node` VALUES ('50', '员工', '', '1', '', '93', '0', '2');
INSERT INTO `sino_node` VALUES ('51', '添加员工', 'staff/addstaff', '1', '', '50', '50', '3');
INSERT INTO `sino_node` VALUES ('52', '编辑员工', 'staff/editstaff', '1', '', '50', '50', '3');
INSERT INTO `sino_node` VALUES ('53', '权限', '', '1', '', '92', '0', '2');
INSERT INTO `sino_node` VALUES ('54', '权限代理', 'privilege/agentlist', '1', '', '50', '53', '3');
INSERT INTO `sino_node` VALUES ('55', '角色管理', 'privilege/listrole', '1', '', '50', '53', '3');
INSERT INTO `sino_node` VALUES ('56', '账号管理', 'privilege/accountlist', '1', '', '50', '53', '3');
INSERT INTO `sino_node` VALUES ('49', '员工管理', 'staff/liststaff', '1', '', '50', '0', '3');
INSERT INTO `sino_node` VALUES ('57', '供应商列表', 'supplier/listsupplier', '1', '', '50', '45', '3');
INSERT INTO `sino_node` VALUES ('58', '导出船员数据', 'mariner/exportmariner', '1', '', '50', '9', '3');

-- ----------------------------
-- Table structure for sino_office_expense
-- ----------------------------
DROP TABLE IF EXISTS `sino_office_expense`;
CREATE TABLE `sino_office_expense` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL COMMENT '报销时间',
  `month` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '月份',
  `user_id` int(11) NOT NULL COMMENT '报销人id',
  `shipowner_id` int(10) NOT NULL COMMENT '客户id',
  `address` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '报销地点',
  `borrow` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '借款',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '转账方式 1现金 2转账',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '报销合计',
  `really` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '应补报销金额',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '签核状态 0待签核 1已通过 2未通过',
  `warn` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '通过提醒 1提醒 0不提醒',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='办公室报销表';

-- ----------------------------
-- Records of sino_office_expense
-- ----------------------------
INSERT INTO `sino_office_expense` VALUES ('10', '2018-06-14', '2018-06', '1', '24', '上海', '200.00', '2', '0.00', '3100.00', '1', '0');
INSERT INTO `sino_office_expense` VALUES ('11', '2018-06-14', '2018-06', '1', '24', '北京', '0.00', '1', '0.00', '0.00', '0', '0');
INSERT INTO `sino_office_expense` VALUES ('12', '2018-06-13', '2018-06', '1', '25', '天津', '0.00', '1', '450.00', '450.00', '1', '0');
INSERT INTO `sino_office_expense` VALUES ('13', '2018-06-14', '2018-06', '1', '25', '武汉', '0.00', '1', '631.00', '0.00', '0', '0');
INSERT INTO `sino_office_expense` VALUES ('14', '2018-06-15', '2018-06', '1', '24', '武汉', '0.00', '1', '660.00', '0.00', '0', '0');

-- ----------------------------
-- Table structure for sino_office_expense_data
-- ----------------------------
DROP TABLE IF EXISTS `sino_office_expense_data`;
CREATE TABLE `sino_office_expense_data` (
  `pid` int(11) NOT NULL COMMENT '办公室报销主表id',
  `project` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '报销项目',
  `explain` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '说明',
  `explain_cost` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '费用说明',
  `number` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '电子发票编号',
  `total` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='办公室报销数据';

-- ----------------------------
-- Records of sino_office_expense_data
-- ----------------------------
INSERT INTO `sino_office_expense_data` VALUES ('12', '办公费', 'adsf', 'asdfasdf', '', '450.00');
INSERT INTO `sino_office_expense_data` VALUES ('10', '招待餐费', 'test', 'tse', '123', '100.00');
INSERT INTO `sino_office_expense_data` VALUES ('11', '办公用品', 'aa', 'ss', '', '500.00');
INSERT INTO `sino_office_expense_data` VALUES ('13', '办公费', '说明', '属性', '609876543', '520.00');
INSERT INTO `sino_office_expense_data` VALUES ('14', '室内通讯费', '说明', '费用', '87897454216', '110.00');
INSERT INTO `sino_office_expense_data` VALUES ('14', '办公费', '说明', '费用', '87989465465', '220.00');
INSERT INTO `sino_office_expense_data` VALUES ('14', '资产购置', '说明', '费用', '13215646465', '330.00');

-- ----------------------------
-- Table structure for sino_principal
-- ----------------------------
DROP TABLE IF EXISTS `sino_principal`;
CREATE TABLE `sino_principal` (
  `pid` int(10) NOT NULL COMMENT '船东id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '财务负责人id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船东-财务负责人表';

-- ----------------------------
-- Records of sino_principal
-- ----------------------------
INSERT INTO `sino_principal` VALUES ('24', '1');

-- ----------------------------
-- Table structure for sino_remittance
-- ----------------------------
DROP TABLE IF EXISTS `sino_remittance`;
CREATE TABLE `sino_remittance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '船员id',
  `bank` varchar(100) COLLATE utf8_bin DEFAULT '' COMMENT '银行名称',
  `short_name` varchar(50) COLLATE utf8_bin DEFAULT '' COMMENT '银行简称',
  `english` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '英文',
  `number` varchar(25) COLLATE utf8_bin DEFAULT '' COMMENT '银行卡号',
  `name_cn` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '收款人中文姓名',
  `name_en` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '收款人英文姓名',
  `birthday` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '生日',
  `relation` varchar(10) COLLATE utf8_bin DEFAULT '' COMMENT '关系',
  `telnumber` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '手机号码',
  `swift` varchar(50) COLLATE utf8_bin DEFAULT '' COMMENT 'swifit',
  `line_number` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '联行号',
  `changer` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '变更人',
  `change_time` datetime NOT NULL COMMENT '变更时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='家汇信息表';

-- ----------------------------
-- Records of sino_remittance
-- ----------------------------
INSERT INTO `sino_remittance` VALUES ('20', '34', '建设银行', 'BCB', '阿萨德', '', '', '', '', '', '', '12312123', '', '刘丽娜', '2018-06-15 00:00:00');
INSERT INTO `sino_remittance` VALUES ('21', '37', '招商银行', null, null, null, null, null, null, null, null, null, null, '刘丽娜', '2018-06-15 00:00:00');

-- ----------------------------
-- Table structure for sino_role
-- ----------------------------
DROP TABLE IF EXISTS `sino_role`;
CREATE TABLE `sino_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名',
  `pid` smallint(6) DEFAULT NULL COMMENT '父角色id',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '启用状态 默认1  0取消',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of sino_role
-- ----------------------------
INSERT INTO `sino_role` VALUES ('1', '系统管理员', null, '1', '超级管理员', '2018-06-12 11:54:56');
INSERT INTO `sino_role` VALUES ('6', '人事管理员', null, '0', '负责人事', '2018-06-12 12:05:09');
INSERT INTO `sino_role` VALUES ('7', '报销管理员', null, '0', '管理报销事宜', '2018-06-12 15:08:02');
INSERT INTO `sino_role` VALUES ('8', '社保管理员', null, '0', '管理社保数据', '2018-06-12 15:08:39');

-- ----------------------------
-- Table structure for sino_role_user
-- ----------------------------
DROP TABLE IF EXISTS `sino_role_user`;
CREATE TABLE `sino_role_user` (
  `role_id` mediumint(9) unsigned NOT NULL COMMENT '角色id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色中间表';

-- ----------------------------
-- Records of sino_role_user
-- ----------------------------
INSERT INTO `sino_role_user` VALUES ('1', '1');
INSERT INTO `sino_role_user` VALUES ('6', '24');
INSERT INTO `sino_role_user` VALUES ('7', '46');
INSERT INTO `sino_role_user` VALUES ('8', '45');
INSERT INTO `sino_role_user` VALUES ('7', '43');
INSERT INTO `sino_role_user` VALUES ('7', '42');
INSERT INTO `sino_role_user` VALUES ('7', '41');
INSERT INTO `sino_role_user` VALUES ('7', '44');

-- ----------------------------
-- Table structure for sino_shipowner
-- ----------------------------
DROP TABLE IF EXISTS `sino_shipowner`;
CREATE TABLE `sino_shipowner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '船东名称',
  `alias` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '简称',
  `attribute` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '船东属性',
  `group` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '业务分组',
  `develop_date` date DEFAULT NULL COMMENT '发展日期',
  `time` date DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船东表';

-- ----------------------------
-- Records of sino_shipowner
-- ----------------------------
INSERT INTO `sino_shipowner` VALUES ('24', 'Sinochem', 'snio', '境内客户', 'A', '2018-04-02', '2018-06-12');
INSERT INTO `sino_shipowner` VALUES ('25', 'Misuga', 'mis', '境外客户', 'A', '2018-04-02', '2018-06-12');

-- ----------------------------
-- Table structure for sino_sign_mariner
-- ----------------------------
DROP TABLE IF EXISTS `sino_sign_mariner`;
CREATE TABLE `sino_sign_mariner` (
  `pid` int(11) NOT NULL COMMENT '船员报销id',
  `first_id` int(11) NOT NULL DEFAULT '0' COMMENT '业务主管签批',
  `first_result` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '一级审批结果 1通过 2拒绝',
  `second_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级部门id  金额需大于2000',
  `second_result` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '二级审批结果 1通过 2拒绝',
  `principal` int(11) NOT NULL DEFAULT '0' COMMENT '财务负责人id',
  `principal_result` tinyint(3) NOT NULL DEFAULT '0' COMMENT '财务审批结果 1通过 2拒绝'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船员签核表';

-- ----------------------------
-- Records of sino_sign_mariner
-- ----------------------------
INSERT INTO `sino_sign_mariner` VALUES ('111', '0', '0', '0', '0', '1', '1');
INSERT INTO `sino_sign_mariner` VALUES ('112', '0', '0', '0', '0', '1', '1');
INSERT INTO `sino_sign_mariner` VALUES ('119', '0', '0', '0', '0', '1', '1');
INSERT INTO `sino_sign_mariner` VALUES ('120', '0', '0', '0', '0', '0', '0');
INSERT INTO `sino_sign_mariner` VALUES ('115', '0', '0', '0', '0', '0', '0');
INSERT INTO `sino_sign_mariner` VALUES ('121', '0', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for sino_sign_office
-- ----------------------------
DROP TABLE IF EXISTS `sino_sign_office`;
CREATE TABLE `sino_sign_office` (
  `pid` int(11) NOT NULL,
  `first_id` int(11) NOT NULL DEFAULT '0' COMMENT '部门经理id',
  `first_result` tinyint(255) DEFAULT '0' COMMENT '第一层签批结果',
  `principal` int(11) NOT NULL DEFAULT '0' COMMENT '财务负责人id',
  `principal_result` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '财务签批结果'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of sino_sign_office
-- ----------------------------
INSERT INTO `sino_sign_office` VALUES ('12', '0', '0', '1', '1');
INSERT INTO `sino_sign_office` VALUES ('10', '0', '0', '1', '1');
INSERT INTO `sino_sign_office` VALUES ('11', '0', '0', '0', '0');
INSERT INTO `sino_sign_office` VALUES ('13', '0', '0', '0', '0');
INSERT INTO `sino_sign_office` VALUES ('14', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for sino_sign_user
-- ----------------------------
DROP TABLE IF EXISTS `sino_sign_user`;
CREATE TABLE `sino_sign_user` (
  `pid` int(11) NOT NULL COMMENT '员工报销id',
  `first_id` int(10) NOT NULL DEFAULT '0' COMMENT '部门经理id',
  `first_result` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '一级审批结果 1通过 2拒绝',
  `principal` int(11) NOT NULL DEFAULT '0' COMMENT '财务id',
  `principal_result` tinyint(3) NOT NULL DEFAULT '0' COMMENT '财务审批结果 1通过 2拒绝'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of sino_sign_user
-- ----------------------------
INSERT INTO `sino_sign_user` VALUES ('4', '0', '0', '1', '0');
INSERT INTO `sino_sign_user` VALUES ('5', '0', '0', '1', '0');
INSERT INTO `sino_sign_user` VALUES ('9', '0', '0', '0', '0');
INSERT INTO `sino_sign_user` VALUES ('10', '0', '0', '0', '0');
INSERT INTO `sino_sign_user` VALUES ('7', '0', '0', '0', '0');
INSERT INTO `sino_sign_user` VALUES ('11', '0', '0', '0', '0');
INSERT INTO `sino_sign_user` VALUES ('12', '0', '0', '0', '0');
INSERT INTO `sino_sign_user` VALUES ('13', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for sino_social_info
-- ----------------------------
DROP TABLE IF EXISTS `sino_social_info`;
CREATE TABLE `sino_social_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mariner_id` int(11) unsigned NOT NULL COMMENT '船员id',
  `pay_month` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '缴费年月',
  `first_date` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '参保时间',
  `area` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '参保地区',
  `yanglao_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '养老-公司',
  `yanglao_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '养老-个人',
  `yiliao_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '医疗-个人',
  `yiliao_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '医疗-个人',
  `shiye_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '失业-公司',
  `shiye_person` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gongshang_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '工伤-公司',
  `gongshang_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '工伤-个人',
  `shengyu_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '生育-公司',
  `shengyu_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '生育-个人',
  `add_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '公司添加项',
  `add_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '添加项-个人',
  `else_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '公司其他 利息',
  `else_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '个人其他 利息',
  `amount_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '个人合计',
  `amount_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '个人合计',
  `assume_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '个人承担',
  `social_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '社保合计',
  `final_company` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际-公司',
  `final_person` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际个人合计',
  `final` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '合计',
  `debt` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '欠款金额',
  `receipt` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已收金额',
  `remark` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '备注',
  `pay_type` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '单位承担部分' COMMENT '缴费类型 单位承担部分 个人全额自付 单位全额承担',
  PRIMARY KEY (`id`,`mariner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=499 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='社保信息表';

-- ----------------------------
-- Records of sino_social_info
-- ----------------------------
INSERT INTO `sino_social_info` VALUES ('489', '31', '2018-06', '2018-05', '武汉', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '20.00', '30.00', '0.00', '0.00', '1600.00', '1240.00', '0.00', '2840.00', '1620.00', '1270.00', '2890.00', '1270.00', '0.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('490', '32', '2018-06', '2018-06', '天津', '618.60', '0.00', '280.00', '160.00', '400.00', '400.00', '1186.40', '0.00', '322.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2807.00', '560.00', '-560.00', '3367.00', '3367.00', '0.00', '3367.00', '0.00', '0.00', '', '单位全额承担');
INSERT INTO `sino_social_info` VALUES ('491', '33', '2018-06', '2018-06', '北京', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '20.00', '30.00', '0.00', '0.00', '1600.00', '1240.00', '0.00', '2840.00', '1620.00', '1270.00', '2890.00', '1270.00', '0.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('492', '34', '2018-06', '2018-06', '北京', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '20.00', '30.00', '0.00', '0.00', '1600.00', '1240.00', '0.00', '2840.00', '1620.00', '1270.00', '2890.00', '1270.00', '1270.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('493', '35', '2018-06', '2018-06', '北京', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '20.00', '30.00', '0.00', '0.00', '1600.00', '1240.00', '0.00', '2840.00', '1620.00', '1270.00', '2890.00', '0.00', '1270.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('494', '36', '2018-06', '2018-06', '北京', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '20.00', '30.00', '0.00', '0.00', '1600.00', '1240.00', '0.00', '2840.00', '1620.00', '1270.00', '2890.00', '1170.00', '100.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('495', '37', '2018-06', '2018-06', '北京', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '20.00', '30.00', '0.00', '0.00', '1600.00', '1240.00', '0.00', '2840.00', '1620.00', '1270.00', '2890.00', '1270.00', '270.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('496', '38', '2018-06', '2018-06', '上海', '618.60', '0.00', '280.00', '160.00', '400.00', '400.00', '1186.40', '0.00', '322.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2807.00', '560.00', '0.00', '3367.00', '2807.00', '560.00', '3367.00', '560.00', '500.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('497', '39', '2018-06', '2018-06', '上海', '618.60', '0.00', '280.00', '160.00', '400.00', '400.00', '1186.40', '0.00', '322.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2807.00', '560.00', '0.00', '3367.00', '2807.00', '560.00', '3367.00', '560.00', '560.00', '', '单位承担部分');
INSERT INTO `sino_social_info` VALUES ('498', '31', '2018-06', '2018-04', '武汉', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '320.00', '310.00', '20.00', '30.00', '200.00', '0.00', '1800.00', '1240.00', '0.00', '2840.00', '1820.00', '1270.00', '3090.00', '1270.00', '0.00', '', '单位承担部分');

-- ----------------------------
-- Table structure for sino_social_info_receipt
-- ----------------------------
DROP TABLE IF EXISTS `sino_social_info_receipt`;
CREATE TABLE `sino_social_info_receipt` (
  `pid` int(11) NOT NULL,
  `area` varchar(30) NOT NULL DEFAULT '' COMMENT '地区',
  `month` varchar(30) NOT NULL DEFAULT '' COMMENT '还款月份',
  `receipt` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '还款金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='社保还款';

-- ----------------------------
-- Records of sino_social_info_receipt
-- ----------------------------
INSERT INTO `sino_social_info_receipt` VALUES ('492', '', '2018-06', '1270.00');
INSERT INTO `sino_social_info_receipt` VALUES ('495', '', '2018-06', '270.00');
INSERT INTO `sino_social_info_receipt` VALUES ('497', '', '2018-07', '560.00');
INSERT INTO `sino_social_info_receipt` VALUES ('496', '', '2018-07', '500.00');
INSERT INTO `sino_social_info_receipt` VALUES ('493', '北京', '2018-07', '1270.00');
INSERT INTO `sino_social_info_receipt` VALUES ('494', '北京', '2018-07', '100.00');

-- ----------------------------
-- Table structure for sino_social_info_sure
-- ----------------------------
DROP TABLE IF EXISTS `sino_social_info_sure`;
CREATE TABLE `sino_social_info_sure` (
  `month` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '对账月份',
  `area` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '地区',
  `debt` decimal(8,2) NOT NULL COMMENT '欠款金额',
  `receipt` decimal(8,2) NOT NULL COMMENT '收款金额',
  `time` datetime NOT NULL COMMENT '对账时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='社保对账信息';

-- ----------------------------
-- Records of sino_social_info_sure
-- ----------------------------
INSERT INTO `sino_social_info_sure` VALUES ('2018-06', '北京', '6350.00', '1540.00', '2018-06-13 19:29:21');
INSERT INTO `sino_social_info_sure` VALUES ('2018-06', '天津', '0.00', '0.00', '2018-06-13 19:30:11');
INSERT INTO `sino_social_info_sure` VALUES ('2018-06', '上海', '1120.00', '0.00', '2018-06-13 19:30:13');
INSERT INTO `sino_social_info_sure` VALUES ('2018-06', '武汉', '2540.00', '0.00', '2018-06-13 19:30:14');

-- ----------------------------
-- Table structure for sino_social_security
-- ----------------------------
DROP TABLE IF EXISTS `sino_social_security`;
CREATE TABLE `sino_social_security` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `area` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '地区',
  `starttime` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '开始月份',
  `endtime` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '结束月份',
  `formula_mode` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '金额计算方式 1四舍五入 2见分进角',
  `remark` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'remark',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='社保设置表';

-- ----------------------------
-- Records of sino_social_security
-- ----------------------------
INSERT INTO `sino_social_security` VALUES ('82', '北京', '2018-01', '2019-01', '1', '北京');
INSERT INTO `sino_social_security` VALUES ('83', '武汉', '2018-01', '2019-01', '2', '北京');
INSERT INTO `sino_social_security` VALUES ('84', '上海', '2018-02', '2019-02', '1', '备注一下');
INSERT INTO `sino_social_security` VALUES ('85', '天津', '2018-03', '2019-03', '1', '备注一下');

-- ----------------------------
-- Table structure for sino_social_security_remark
-- ----------------------------
DROP TABLE IF EXISTS `sino_social_security_remark`;
CREATE TABLE `sino_social_security_remark` (
  `mariner_id` int(11) unsigned NOT NULL COMMENT '船员id',
  `remark` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`mariner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='社保备注';

-- ----------------------------
-- Records of sino_social_security_remark
-- ----------------------------

-- ----------------------------
-- Table structure for sino_social_security_set
-- ----------------------------
DROP TABLE IF EXISTS `sino_social_security_set`;
CREATE TABLE `sino_social_security_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '项目名称',
  `pid` int(11) unsigned NOT NULL,
  `base_company` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '公司基数',
  `base_person` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '个人基数',
  `rate_company` float(4,1) NOT NULL COMMENT '公司费率',
  `rate_person` float(4,1) NOT NULL COMMENT '个人费率',
  `amount_company` decimal(8,2) NOT NULL COMMENT '公司固定金额',
  `amount_person` decimal(8,2) NOT NULL COMMENT '个人固定金额',
  `total_person` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '个人合计',
  `total_company` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '公司合计',
  `is_five` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否是五险  1是 0不是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='参保项目表';

-- ----------------------------
-- Records of sino_social_security_set
-- ----------------------------
INSERT INTO `sino_social_security_set` VALUES ('179', '养老保险', '82', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('180', '生育保险', '82', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('181', '失业保险', '82', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('182', '工伤保险', '82', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('183', '医疗保险', '82', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('184', '暖气费', '82', '100.00', '100.00', '20.0', '30.0', '0.00', '0.00', '30.00', '30.00', '0');
INSERT INTO `sino_social_security_set` VALUES ('185', '养老保险', '83', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('186', '生育保险', '83', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('187', '失业保险', '83', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('188', '工伤保险', '83', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('189', '医疗保险', '83', '3000.00', '3000.00', '10.0', '10.0', '20.00', '10.00', '310.00', '320.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('190', '暖气费', '83', '100.00', '100.00', '20.0', '30.0', '0.00', '0.00', '30.00', '30.00', '0');
INSERT INTO `sino_social_security_set` VALUES ('191', '生育保险', '84', '3220.00', '3220.00', '13.0', '0.0', '200.00', '0.00', '0.00', '1186.40', '1');
INSERT INTO `sino_social_security_set` VALUES ('192', '失业保险', '84', '3220.00', '3220.00', '10.0', '0.0', '0.00', '0.00', '0.00', '322.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('193', '养老保险', '84', '3000.00', '3000.00', '10.0', '10.0', '100.00', '100.00', '400.00', '400.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('194', '工伤保险', '84', '3220.00', '3220.00', '12.0', '0.0', '800.00', '0.00', '0.00', '1186.40', '1');
INSERT INTO `sino_social_security_set` VALUES ('195', '医疗保险', '84', '2000.00', '2000.00', '8.0', '8.0', '120.00', '0.00', '160.00', '280.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('196', '生育保险', '85', '3220.00', '3220.00', '13.0', '0.0', '200.00', '0.00', '0.00', '1186.40', '1');
INSERT INTO `sino_social_security_set` VALUES ('197', '失业保险', '85', '3220.00', '3220.00', '10.0', '0.0', '0.00', '0.00', '0.00', '322.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('198', '养老保险', '85', '3000.00', '3000.00', '10.0', '10.0', '100.00', '100.00', '400.00', '400.00', '1');
INSERT INTO `sino_social_security_set` VALUES ('199', '工伤保险', '85', '3220.00', '3220.00', '12.0', '0.0', '800.00', '0.00', '0.00', '1186.40', '1');
INSERT INTO `sino_social_security_set` VALUES ('200', '医疗保险', '85', '2000.00', '2000.00', '8.0', '8.0', '120.00', '0.00', '160.00', '280.00', '1');

-- ----------------------------
-- Table structure for sino_supplier
-- ----------------------------
DROP TABLE IF EXISTS `sino_supplier`;
CREATE TABLE `sino_supplier` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '供应商名称',
  `attribute` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '属性 如：飞机票代理',
  `develop_date` date NOT NULL COMMENT '发展日期',
  `remark` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '备注',
  `time` datetime NOT NULL COMMENT '系统时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='供应商表';

-- ----------------------------
-- Records of sino_supplier
-- ----------------------------
INSERT INTO `sino_supplier` VALUES ('15', 'test1-1', 'test1-1', '2018-06-10', 'test1-1', '2018-06-13 08:47:15');
INSERT INTO `sino_supplier` VALUES ('16', 'test2', 'test2', '2018-06-11', 'test2', '2018-06-13 08:47:30');
INSERT INTO `sino_supplier` VALUES ('17', 'test3', 'test3', '2018-06-12', 'test3', '2018-06-13 08:47:39');

-- ----------------------------
-- Table structure for sino_supplier_info
-- ----------------------------
DROP TABLE IF EXISTS `sino_supplier_info`;
CREATE TABLE `sino_supplier_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '供应商id',
  `date` date NOT NULL COMMENT '费用发生时间',
  `month` varchar(30) NOT NULL DEFAULT '' COMMENT '月份',
  `pay_before` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '应付金额',
  `pay_after` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实付金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='供应商费用明细表';

-- ----------------------------
-- Records of sino_supplier_info
-- ----------------------------
INSERT INTO `sino_supplier_info` VALUES ('9', '16', '2018-06-13', '2018-06', '1000.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('10', '16', '2018-06-13', '2018-06', '201.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('11', '16', '2018-06-13', '2018-06', '301.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('12', '17', '2018-06-13', '2018-06', '201.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('13', '16', '2018-06-13', '2018-06', '201.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('14', '15', '2018-06-13', '2018-06', '201.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('15', '17', '2018-06-13', '2018-06', '301.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('16', '16', '2018-06-13', '2018-06', '100.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('17', '16', '2018-06-13', '2018-06', '100.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('18', '15', '2018-06-13', '2018-06', '100.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('19', '16', '2018-06-13', '2018-06', '100.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('20', '15', '2018-06-13', '2018-06', '100.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('21', '17', '2018-06-13', '2018-06', '100.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('22', '16', '2018-06-13', '2018-06', '101.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('23', '16', '2018-06-13', '2018-06', '101.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('24', '17', '2018-06-13', '2018-06', '101.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('25', '16', '2018-06-13', '2018-06', '101.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('26', '15', '2018-06-13', '2018-06', '101.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('27', '17', '2018-06-13', '2018-06', '101.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('28', '15', '2018-06-13', '2018-06', '1000.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('29', '16', '2018-06-13', '2018-06', '220.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('30', '17', '2018-06-13', '2018-06', '410.00', '0.00');
INSERT INTO `sino_supplier_info` VALUES ('31', '16', '2018-06-13', '2018-06', '2000.00', '0.00');

-- ----------------------------
-- Table structure for sino_user
-- ----------------------------
DROP TABLE IF EXISTS `sino_user`;
CREATE TABLE `sino_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fn` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '档案编号',
  `username` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '姓名',
  `gender` varchar(3) COLLATE utf8_bin DEFAULT '' COMMENT '性别',
  `abbreviation` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '别名',
  `english` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '英文名',
  `password` char(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `department` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '部门',
  `shipowner` int(9) DEFAULT NULL COMMENT '船东id',
  `duty` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '职务',
  `appoint_duty` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '任命职务',
  `appoint_date` varchar(30) COLLATE utf8_bin DEFAULT NULL COMMENT '任命时间',
  `working_date` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '到司时间',
  `dimission_date` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '离职日期',
  `assume_office_date` tinyint(3) DEFAULT '0' COMMENT '司龄',
  `id_number` varchar(25) COLLATE utf8_bin DEFAULT '' COMMENT '身份证号码',
  `birthday` date DEFAULT NULL COMMENT '出生日期',
  `age` tinyint(3) unsigned DEFAULT NULL COMMENT '年龄',
  `edu_background` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '学历',
  `degree` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '学位',
  `major` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '专业',
  `school` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '毕业院校',
  `graduation_date` varchar(30) COLLATE utf8_bin DEFAULT NULL COMMENT '毕业时间',
  `sign_start` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '劳动合同起始日',
  `sign_end` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '劳动合同结束时间',
  `professional_skill` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '专业职称',
  `qualification` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '船上任职资格',
  `residence` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '户口地',
  `political_status` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '政治面貌',
  `marry` varchar(5) COLLATE utf8_bin DEFAULT '' COMMENT '婚否',
  `work_date` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '参加工作日期',
  `birthplace` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '出生地',
  `regular_date` varchar(30) COLLATE utf8_bin DEFAULT NULL COMMENT '转正日期',
  `address` varchar(50) COLLATE utf8_bin DEFAULT '' COMMENT '住址',
  `phone_number` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '电话号码',
  `telnumber` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '手机号码',
  `email` varchar(30) COLLATE utf8_bin DEFAULT '' COMMENT '邮箱',
  `logintime` date DEFAULT NULL COMMENT '最后登录时间',
  `loginip` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '最后登录ip',
  `remark` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '备注信息',
  `url` varchar(150) COLLATE utf8_bin DEFAULT '' COMMENT '头像',
  `principal` varchar(5) COLLATE utf8_bin DEFAULT '否' COMMENT '是否财务负责人  是或否',
  `is_delete` tinyint(3) unsigned DEFAULT '0' COMMENT '是否禁用 1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='员工表';

-- ----------------------------
-- Records of sino_user
-- ----------------------------
INSERT INTO `sino_user` VALUES ('1', 'XX00001', '刘丽娜', '女', 'lln', 'Liu Lina', '21232f297a57a5a743894a0e4a801fc3', '财务部', '24', '阿斯蒂芬', '55555555', '2018-05-27', '2015-06-03', '', '0', '420116199402192435', '1994-02-19', '24', '', '', '', '', '', '', '', '', '55555', '', '', '', '', '湖北武汉', '', '', '18771142563', '13164627120', 'yolo_me@163.com', '2018-06-15', '192.168.10.111', '', '', '否', '0');
INSERT INTO `sino_user` VALUES ('41', 'XYS00010', '仲丹琴 ', '男', 'zdq', 'Zhong Danqin', '9716e869db3ea989f668681e58cd679b', '武汉-技术部', '24', '技术助理', '技术助理', '2015-07-12', '2018-07-12', '', '0', '36020019840817600X ', '1984-08-17', '34', '本科', '硕士', '机电一体化', '武汉科技大学', '2016-05-11', '2016-05-11', '2019-05-11', '高级电工', '无', '湖北武汉', '中共党员', '已婚', '2015-06-11', '湖北', '2015-10-11', '湖北省武汉市洪山区关南街道光谷理想城5栋1单元', '027-36556555', '13164627120', 'yolo_me@163.com', null, '', '培养员工', '', '否', '1');
INSERT INTO `sino_user` VALUES ('42', 'XYS00011', '叶博丹', '男', 'ybd', 'Ye Bodan', '34500a93715bb54e057f070ed5ab0a34', '武汉-技术部', '25', '技术助理', '技术助理', '2015-07-13', '2018-07-13', '', '0', '542226199403221696', '1984-08-18', '34', '本科', '硕士', '机电一体化', '武汉科技大学', '2016-05-11', '2016-05-11', '2019-05-11', '高级电工', '无', '湖北武汉', '中共党员', '已婚', '2015-06-12', '湖北', '2015-10-12', '湖北省武汉市洪山区关南街道光谷理想城5栋2单元', '027-36556556', '13164627121', 'yolo_me@163.com', null, '', '培养员工', '', '否', '0');
INSERT INTO `sino_user` VALUES ('43', 'XYS00012', '韩瑞敏', '男', 'hrm', 'Han Ruimin', '5d4745082328f3c6cb0930ee958f2e17', '武汉-技术部', '24', '技术助理', '技术助理', '2015-07-14', '2018-07-14', '', '0', '141100197808290987', '1984-08-19', '34', '本科', '硕士', '机电一体化', '武汉科技大学', '2016-05-11', '2016-05-11', '2019-05-11', '高级电工', '无', '湖北武汉', '中共党员', '已婚', '2015-06-13', '湖北', '2015-10-13', '湖北省武汉市洪山区关南街道光谷理想城5栋3单元', '027-36556557', '13164627122', 'yolo_me@163.com', null, '', '培养员工', '', '否', '0');
INSERT INTO `sino_user` VALUES ('44', 'XYS00013', '解政宇', '男', 'jzy', 'Jie Zhengyu', 'd7a8188e1e7ce67b12ccf3867e277863', '武汉-技术部', '25', '技术助理', '技术助理', '2015-07-15', '2018-07-15', '', '0', '610631198302087621', '1984-08-20', '34', '本科', '硕士', '机电一体化', '武汉科技大学', '2016-05-11', '2016-05-11', '2019-05-11', '高级电工', '无', '湖北武汉', '中共党员', '已婚', '2015-06-14', '湖北', '2015-10-14', '湖北省武汉市洪山区关南街道光谷理想城5栋4单元', '027-36556558', '13164627123', 'yolo_me@163.com', null, '', '培养员工', '', '否', '0');
INSERT INTO `sino_user` VALUES ('45', 'XYS00014', '窦盼巧', '男', 'pq', 'Panqiao', '1b8a5483c177d6ed892cfec0b586c3fc', '北京-技术部', '24', '技术助理', '技术助理', '2015-07-16', '2018-07-16', '', '0', '13063619910216582', '1984-08-21', '34', '本科', '硕士', '机电一体化', '武汉科技大学', '2016-05-11', '2016-05-11', '2019-05-11', '高级电工', '无', '湖北武汉', '中共党员', '已婚', '2015-06-15', '湖北', '2015-10-15', '湖北省武汉市洪山区关南街道光谷理想城5栋5单元', '027-36556559', '13164627124', 'yolo_me@163.com', '2018-06-12', '192.168.10.109', '培养员工', '', '否', '0');
INSERT INTO `sino_user` VALUES ('46', 'XYS00015', '费朵一', '男', 'fdy', 'Fei Duoyi', 'e1f8bdd18c9e617b34925c2697eadf7b', '北京-技术部', '25', '技术助理', '技术助理', '2015-07-17', '2018-07-17', '', '0', '530522198909134971', '1984-08-22', '34', '本科', '硕士', '机电一体化', '武汉科技大学', '2016-05-11', '2016-05-11', '2019-05-11', '高级电工', '无', '湖北武汉', '中共党员', '已婚', '2015-06-16', '湖北', '2015-10-16', '湖北省武汉市洪山区关南街道光谷理想城5栋6单元', '027-36556560', '13164627125', 'yolo_me@163.com', null, '', '培养员工', 'http://192.168.10.121/public/uploads/image/20180612\\6405293aea14039e1076939ae02ab722.png', '否', '0');
INSERT INTO `sino_user` VALUES ('47', 'fn20180612', '王蒙', '女', 'wm', 'Wang Meng', '81ea08244b1a8127e8a22eb292d56d48', '武汉-技术部', '25', '高级It', '', '2018-06-05', '', '', '0', '421114166401231142', '1664-01-23', '0', '', '', '', '', '', '', '', '', '', '', '', '', '2018-06-06', '', '2018-06-11', '', '', '', '', null, '', '', '', '否', '0');

-- ----------------------------
-- Table structure for sino_user_expense
-- ----------------------------
DROP TABLE IF EXISTS `sino_user_expense`;
CREATE TABLE `sino_user_expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '员工id',
  `date` date NOT NULL COMMENT '报销时间',
  `month` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '月份',
  `start_date` date NOT NULL COMMENT '开始时间',
  `end_date` date NOT NULL COMMENT '返程时间',
  `days` smallint(5) NOT NULL DEFAULT '0' COMMENT '天数',
  `shipowner_id` int(10) NOT NULL COMMENT '船东id',
  `vessel_id` int(10) NOT NULL COMMENT '船名id',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '报销总额',
  `address` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '地址',
  `reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '原因',
  `explain` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '事由说明',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '转账类型 1现金 2转账',
  `really` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实际报销金额',
  `over_date` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '财务签批日期',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '签核状态 0待签核 1已通过 2未通过',
  `warn` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '通过提醒 1提醒 0不提醒',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='员工报销主表';

-- ----------------------------
-- Records of sino_user_expense
-- ----------------------------
INSERT INTO `sino_user_expense` VALUES ('4', '1', '2018-06-13', '2018-06', '2018-06-05', '2018-06-07', '2', '24', '11', '1000.00', '北京', '上下船', 'asdf', '1', '0.00', '', '1', '0');
INSERT INTO `sino_user_expense` VALUES ('5', '1', '2018-06-13', '2018-06', '2018-06-01', '2018-06-02', '1', '25', '10', '630.00', '北京', '上下船', 'aaaa', '1', '0.00', '', '1', '0');
INSERT INTO `sino_user_expense` VALUES ('7', '1', '2018-06-14', '2018-06', '2018-06-12', '2018-06-13', '1', '25', '11', '2000.00', '上海', '签证', '啊啊啊11111', '1', '0.00', '', '0', '0');
INSERT INTO `sino_user_expense` VALUES ('9', '1', '2018-06-14', '2018-06', '2018-06-06', '2018-06-13', '7', '25', '11', '400.00', '上海', '培训', 'test', '1', '0.00', '', '0', '0');
INSERT INTO `sino_user_expense` VALUES ('10', '1', '2018-06-14', '2018-06', '2018-06-05', '2018-06-07', '2', '25', '12', '240.00', '武汉', '签证', 'asdf', '1', '0.00', '', '0', '0');
INSERT INTO `sino_user_expense` VALUES ('11', '1', '2018-06-14', '2018-06', '2018-05-29', '2018-06-13', '15', '24', '10', '1000.00', '北京', '面试', 'asdf', '1', '0.00', '', '0', '0');
INSERT INTO `sino_user_expense` VALUES ('12', '1', '2018-06-14', '2018-06', '2018-06-05', '2018-06-07', '2', '25', '12', '0.00', '北京', '上下船', 'aaaa', '1', '0.00', '', '0', '0');
INSERT INTO `sino_user_expense` VALUES ('13', '1', '2018-06-14', '2018-06', '2018-06-11', '2018-06-13', '2', '25', '11', '0.00', '武汉', '签证', 'asdf', '1', '0.00', '', '0', '0');

-- ----------------------------
-- Table structure for sino_user_expense_assume
-- ----------------------------
DROP TABLE IF EXISTS `sino_user_expense_assume`;
CREATE TABLE `sino_user_expense_assume` (
  `pid` int(11) NOT NULL COMMENT '员工报销主表id',
  `shiper_traffic` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收船东交通费',
  `shiper_hotel` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '住宿费用',
  `shiper_city` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市内交通费',
  `shiper_travel` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '差旅补贴',
  `shiper_meal` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '招待餐费',
  `shiper_exchange` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '其他交际费',
  `shiper_office` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '办公杂费',
  `shiper_communication` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '通讯费',
  `shiper_post` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮递费',
  `shiper_partner` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '同行人费用',
  `shiper_else` decimal(10,2) DEFAULT '0.00' COMMENT '其他费用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='员工报销收船东金额';

-- ----------------------------
-- Records of sino_user_expense_assume
-- ----------------------------
INSERT INTO `sino_user_expense_assume` VALUES ('4', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_user_expense_assume` VALUES ('5', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_user_expense_assume` VALUES ('9', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_user_expense_assume` VALUES ('10', '10.00', '101.00', '10.00', '10.00', '10.00', '10.00', '10.00', '10.00', '140.00', '0.00', '10.00');
INSERT INTO `sino_user_expense_assume` VALUES ('7', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_user_expense_assume` VALUES ('11', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_user_expense_assume` VALUES ('12', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `sino_user_expense_assume` VALUES ('13', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');

-- ----------------------------
-- Table structure for sino_user_expense_data
-- ----------------------------
DROP TABLE IF EXISTS `sino_user_expense_data`;
CREATE TABLE `sino_user_expense_data` (
  `pid` int(11) NOT NULL COMMENT '员工报销id',
  `date` date NOT NULL COMMENT '费用发生日期',
  `start_address` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '起始地点',
  `end_address` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '到达地点',
  `traffic_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交通费',
  `hotel_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '住宿费',
  `city_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市内交通费',
  `travel_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '差旅补贴',
  `meal_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '招待餐费',
  `exchange_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '其他交际费',
  `office_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '办公杂费',
  `communication_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '通讯费',
  `post_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮递费',
  `partner_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '同行人',
  `number` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '电子发票编号',
  `else_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '其他费用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='员工报销数据表';

-- ----------------------------
-- Records of sino_user_expense_data
-- ----------------------------
INSERT INTO `sino_user_expense_data` VALUES ('4', '2018-06-05', 'sss', 'ddd', '1000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '0.00');
INSERT INTO `sino_user_expense_data` VALUES ('5', '2018-06-01', 'aaa', 'bbb', '100.00', '200.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '0.00');
INSERT INTO `sino_user_expense_data` VALUES ('5', '2018-06-02', 'ccc', 'ddd', '120.00', '210.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 'fp456', '0.00');
INSERT INTO `sino_user_expense_data` VALUES ('9', '2018-06-12', 'test', 'test', '200.00', '200.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '0.00');
INSERT INTO `sino_user_expense_data` VALUES ('10', '2018-06-05', '湖北', '孝感', '100.00', '100.00', '20.00', '20.00', '20.00', '20.00', '20.00', '20.00', '20.00', '20.00', '', '20.00');
INSERT INTO `sino_user_expense_data` VALUES ('7', '2018-06-12', '单独订', '啊啊', '2000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '0.00');
INSERT INTO `sino_user_expense_data` VALUES ('11', '2018-06-13', 'ss', 'dd', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '1000.00');
INSERT INTO `sino_user_expense_data` VALUES ('12', '2018-06-05', 'sss', 'dddd', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '0.00');
INSERT INTO `sino_user_expense_data` VALUES ('13', '2018-06-11', 'ss', 'dd', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '0.00');

-- ----------------------------
-- Table structure for sino_user_expense_option
-- ----------------------------
DROP TABLE IF EXISTS `sino_user_expense_option`;
CREATE TABLE `sino_user_expense_option` (
  `pid` int(10) NOT NULL COMMENT '员工报销id',
  `traffic` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '交通费',
  `hotel` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '住宿费',
  `city` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '市内交通费',
  `travel` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '差旅补贴',
  `meal` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '招待餐费',
  `exchange` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '其他交际费',
  `office` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '办公杂费',
  `communication` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '通讯费',
  `post` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '邮递',
  `partner` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '同行人',
  `else` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '其他'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='员工报销费用备注';

-- ----------------------------
-- Records of sino_user_expense_option
-- ----------------------------
INSERT INTO `sino_user_expense_option` VALUES ('4', '火车,15', '', '', '', '', '', '', '', '', '', '');
INSERT INTO `sino_user_expense_option` VALUES ('5', '火车,16', '上海,17', '', '', '', '', '', '', '', '', '');
INSERT INTO `sino_user_expense_option` VALUES ('9', '飞机,15', '北京,17', '', '', '', '', '', '', '', '', '');
INSERT INTO `sino_user_expense_option` VALUES ('10', '飞机,16', '北京,16', '滴滴,16', '', '阿三,16', 'test,16', 'test,17', 'tset,16', 'ste,15', 'set', 'set');
INSERT INTO `sino_user_expense_option` VALUES ('7', '火车,16', ',', ',', ',', ',', ',', ',', ',', ',', '', '');
INSERT INTO `sino_user_expense_option` VALUES ('11', '', '', '', '', '', '', '', '', '', '', 'asdf');
INSERT INTO `sino_user_expense_option` VALUES ('12', '', '', '', '', '', '', '', '', '', '', '');
INSERT INTO `sino_user_expense_option` VALUES ('13', '', '', '', '', '', '', '', '', '', '', '');

-- ----------------------------
-- Table structure for sino_user_expense_principal
-- ----------------------------
DROP TABLE IF EXISTS `sino_user_expense_principal`;
CREATE TABLE `sino_user_expense_principal` (
  `pid` int(10) NOT NULL,
  `date` date DEFAULT NULL,
  `project` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `currency` varchar(255) COLLATE utf8_bin NOT NULL,
  `rmb` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '人民币'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='员工报销抵扣表';

-- ----------------------------
-- Records of sino_user_expense_principal
-- ----------------------------
INSERT INTO `sino_user_expense_principal` VALUES ('4', '2018-06-06', '借款', '100', '200.00');
INSERT INTO `sino_user_expense_principal` VALUES ('4', '2018-06-05', '借款', '100', '200.00');
INSERT INTO `sino_user_expense_principal` VALUES ('4', '2018-06-06', '借款', '100', '100.00');
INSERT INTO `sino_user_expense_principal` VALUES ('5', '2018-06-13', '借款', '0', '0.00');
INSERT INTO `sino_user_expense_principal` VALUES ('5', '2018-06-13', '借款', '0', '0.00');
INSERT INTO `sino_user_expense_principal` VALUES ('5', '2018-06-13', '借款', '0', '100.00');

-- ----------------------------
-- Table structure for sino_vessel
-- ----------------------------
DROP TABLE IF EXISTS `sino_vessel`;
CREATE TABLE `sino_vessel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '船名',
  `attribute` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '属性',
  `fleet` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '船队',
  `develop_date` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发展日期',
  `time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='船名表';

-- ----------------------------
-- Records of sino_vessel
-- ----------------------------
INSERT INTO `sino_vessel` VALUES ('10', 'tomrrow', '整船', 'A队', '2016-06-01', '2018-06-12 14:41:54');
INSERT INTO `sino_vessel` VALUES ('11', 'today', '整船', 'A队', '2018-06-11', '2018-06-12 15:57:42');
INSERT INTO `sino_vessel` VALUES ('12', 'one', '整船', 'B队', '2018-06-11', '2018-06-12 16:11:41');
