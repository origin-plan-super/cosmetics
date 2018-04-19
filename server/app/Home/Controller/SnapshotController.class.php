<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月15日09:48:49
* 最新修改时间：2018年4月15日09:48:49
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品快照控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class SnapshotController extends CommonController{
    
    public function saveCoupon(){
        
        $Snapshot=D('Snapshot');
        $result=$Snapshot->saveCoupon(I('snapshot_id'),I('coupon_id'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
}