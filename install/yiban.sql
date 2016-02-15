
CREATE TABLE IF NOT EXISTS `webuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `gj_num` int(2) NOT NULL DEFAULT '0',
  `vip` int(1) NOT NULL DEFAULT '0',
  `regip` varchar(16) NOT NULL DEFAULT '',
  `regua` varchar(225) NOT NULL DEFAULT '',
  `regtime` varchar(30) NOT NULL DEFAULT '',
  `lastip` varchar(16) NOT NULL DEFAULT '',
  `lastua` varchar(225) NOT NULL,
  `lasttime` varchar(30) NOT NULL DEFAULT '',
  `state` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ybuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webuserid` int(11) NOT NULL,
  `ybuser` varchar(20) NOT NULL,
  `ybpass` varchar(60) NOT NULL,
  `ybid` int(11) DEFAULT '0',
  `nick` varchar(225) NOT NULL DEFAULT '',
  `run` int(1) NOT NULL DEFAULT '1',
  `runtime1` int(1) NOT NULL DEFAULT '8',
  `runtime2` int(1) NOT NULL DEFAULT '18',
  `lastruntime` varchar(30) NOT NULL DEFAULT '',
  `state` int(1) NOT NULL DEFAULT '1',
  `dayin` int(1) NOT NULL DEFAULT '0',
  `lastdayintime` varchar(30) NOT NULL DEFAULT '',
  `trends` int(1) NOT NULL DEFAULT '0',
  `trendsMsg` varchar(225) NOT NULL DEFAULT '',
  `lasttrendstime` varchar(30) NOT NULL DEFAULT '',
  `blog` int(1) NOT NULL DEFAULT '0',
  `blogMsg` varchar(225) NOT NULL DEFAULT '',
  `lastblogtime` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
