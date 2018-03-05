<?php

use Payment\Notify\PayNotifyInterface;
use Payment\Config;
use Think\Db;
use plugins\weixin\controller\IndexController;

/**
 * @author: helei
 * @createTime: 2016-07-20 18:31
 * @description:
 */

/**
 * 客户端需要继承该接口，并实现这个方法，在其中实现对应的业务逻辑
 * Class TestNotify
 * anthor helei
 */
class TestNotify implements PayNotifyInterface
{
    public function notifyProcess(array $data)
    {
        $channel = $data['channel'];
        if ($channel === Config::ALI_CHARGE) {// 支付宝支付

        } elseif ($channel === Config::WX_CHARGE) {// 微信支付
            $message = new IndexController();

            //根据订单号取出该订单的基本信息
            //根据订单号中第一个下划线后面的一位数字来判断是什么类型的订单（活动1，票务2，音频3，视频4等）
            $type = substr($data['order_no'],stripos($data['order_no'],'_')+1,1);
            Db::startTrans();
            try{
                $info = Db::name('activity_order')->field('good_id,size_id')->where(['order_id'=>$data['order_no']])->find();
                //修改订单状态
                Db::name('activity_order')->where('order_id',$data['order_no'])->update(['order_finish_time'=>strtotime($data['pay_time']),'order_status'=>1]);
                if(intval($type) == 1){
                    //活动
                }elseif(intval($type) == 2){
                    //票务
                    //修改指定时间段票务的库存  -1
                    Db::name('ticket_stock')->where('id',$info['size_id'])->setDec('stock');
                }elseif(intval($type) == 3){

                }
                $message->sendTemptele();

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        } elseif ($channel === Config::CMB_CHARGE) {// 招商支付

        } elseif ($channel === Config::CMB_BIND) {// 招商签约

        } else {
            // 其它类型的通知
        }

        // 执行业务逻辑，成功后返回true
        return true;
    }
}
