<?php
namespace Admin\Model;
use Think\Model;
class CouponModel extends Model {
    
    
    public function _initialize (){}
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        
        $couponList  =  $this
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $User=D('User');
        //找用户信息
        for ($i=0; $i <count($couponList) ; $i++) {
            if($couponList[$i]['user_id']){
                $user_id=$couponList[$i]['user_id'];
                $where=[];
                $where['user_id']=$user_id;
                $userInfo=  $User->where($where)->find();
                $couponList[$i]['userInfo']=$userInfo;
            }else{
                $couponList[$i]['userInfo']=null;
            }
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