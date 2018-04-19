<?php
namespace Home\Model;
use Think\Model;
class CouponModel extends Model {
    
    
    public function _initialize (){}
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where=[];
        $where['user_id']=session('user_id');
        $couponList  =  $this
        ->order('add_time desc')
        // ->limit(($page-1)*$limit,$limit)
        ->where($where)
        ->select();
        
        //过期检测
        for ($i=0; $i <count($couponList) ; $i++) {
            $item=$couponList[$i];
            
            if(time() > $item['end_at']){
                //如果 + $end_time 大于现在的时间，就是没过期
                //如果 + $end_time 秒小于或者等于现在的时间，就是过期了
                //过期了
                // 使用状态
                // 0：已过期
                // 1：未使用
                // 2：已使用
                $couponList[$i]['state']=0;
            }
            // dump(time() > $item['end_at']);
            // dump($item['state']);
            // dump($item['value']);
            // echo '<hr>';
        }
        
        $couponList=toTime($couponList);
        $couponList=toTime2($couponList,'Y-m-d',['end_at','start_at']);
        
        return $couponList;
    }
    
    /**
    * 获得优惠券减去的价格，当优惠券的信息存在，此函数会将优惠券信息写入到关联表中
    * @param String $coupon_id 优惠券的id
    * @param String $snapshot_id 快照id，用于条件判断
    * @param String $order_id 订单id，如果存在，就写入到关联表中，如果为false，就只是取得数据不写入
    * @return 返回促销活动可以减去的价格，如果不满足条件就返回0，否则返回可优惠的价格
    */
    public function getCouponPrice($coupon_id,$snapshot_id,$order_id){
        if(!$coupon_id)return 0;// 没有优惠券id
        
        // ===================================================================================
        // 创建模型
        
        // ===================================================================================
        // 获得优惠券的数据
        
        // ===================================================================================
        // 判断优惠券的条件
        
        // ===================================================================================
        // 返回优惠券可优惠的价格
        
        // ===================================================================================
        // 将优惠券写入到数据库中，如果$order_id存在，且不为false
        
        // ===================================================================================
        // 返回优惠券优惠数据
        return 0;
    }
    
    
}