SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ba_user_realname
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__user_realname`;
CREATE TABLE `__PREFIX__user_realname` (
    `uid` int(11) NOT NULL COMMENT '用户ID',
    `name` varchar(11) NOT NULL COMMENT '姓名',
    `idcard` varchar(20) NOT NULL COMMENT '身份证',
    `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '实名状态:1=审核中,2=审核成功,3=审核失败',
    `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户实名表';

SET FOREIGN_KEY_CHECKS = 1;
REPLACE INTO `__PREFIX__config` VALUES (null, 'secretId', 'idcard', '密钥ID', '', 'string', '', null, '', '', '0', '97');
REPLACE INTO `__PREFIX__config` VALUES (null, 'secretKey', 'idcard', '密钥Key', '', 'string', '', null, '', '', '0', '96');