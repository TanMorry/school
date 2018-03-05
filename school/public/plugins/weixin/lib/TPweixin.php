<?php
// +----------------------------------------------------------------------
// | 文件说明：适配tp5 wecaht-php-sdk  
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.wuwuseo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com> 
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2017-7-2 https://github.com/yangguangwuwu/thinkcmf5_wechat.git
// +----------------------------------------------------------------------
namespace plugins\weixin\lib;
// require_once __DIR__ . '/wechat.phar';
require_once __DIR__ . '/wechat.class.php';
use think\Log;
use think\Cache;

class TPweixin extends \Wechat
{
    /**
     * log overwrite
     * @see Wechat::log()
     */
    protected function log($log){
        if ($this->debug) {
            if (function_exists($this->logcallback)) {
                if (is_array($log)) $log = print_r($log,true);
                return call_user_func($this->logcallback,$log);
            }elseif (class_exists('Log')) {
                Log::write('wechat：'.$log,'log');
            }
        }
        return false;
    }

    /**
     * 重载设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename,$value,$expired){
        return Cache::set($cachename,$value,$expired);
    }

    /**
     * 重载获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename){
        return Cache::get($cachename);
    }

    /**
     * 重载清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename){
        return Cache::rm($cachename);
    }
}
