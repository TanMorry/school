<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五五 <15093565100@163.com>
// +----------------------------------------------------------------------
namespace plugins\weixin;
use cmf\lib\Plugin;
use think\Db;

//微信插件
class WeixinPlugin extends Plugin
{

    public $info = [
        'name'        => 'Weixin',
        'title'       => '微信公共平台（服务或订阅号）插件',
        'description' => '微信公共平台（服务或订阅号）插件',
        'status'      => 1,
        'author'      => 'wuwu',
        'version'     => '1.0',
        'demo_url'    => '',
        'author_url'  => 'http://www.wuwuseo.com'
    ];

    public $hasAdmin = 1;//插件是否有后台管理界面

    // 插件安装
    public function install()
    {
        $mysql = config('database');
        $sql = <<<EOF
CREATE TABLE IF NOT EXISTS `{$mysql['prefix']}plugin_passive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `intype` char(20) NOT NULL DEFAULT 'click' COMMENT '接受消息类型',
  `outtype` char(20) NOT NULL DEFAULT 'text' COMMENT '输出消息类型',
  `mark` varchar(30) NOT NULL DEFAULT '' COMMENT '标识',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$mysql['charset']} COMMENT='被动回复规则'
EOF;
        Db::execute($sql);
        return true;//安装成功返回true，失败false
    }

    // 插件卸载
    public function uninstall()
    {
        $mysql = config('database');
        $sql = "DROP TABLE IF EXISTS `{$mysql['prefix']}plugin_passive`";
        Db::execute($sql);       
        return true;//安装成功返回true，失败false       
    }
    
}