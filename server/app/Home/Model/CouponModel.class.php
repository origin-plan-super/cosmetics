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
    
    //获得一个
    public function get($goods_id){
        
        return $goods;
    }
    
    
}