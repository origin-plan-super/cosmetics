<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月4日16:12:20
* 最新修改时间：2018年4月4日16:12:20
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####消息控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class MsgController extends CommonController{
    
    //主
    public function getList(){
        
        $Msg=D('Msg');
        $msgs=$Msg->getList(I());
        
        if($msgs){
            $res['res']=count($msgs);
            $res['msg']=$msgs;
        }else{
            $res['res']=-1;
            $res['msg']=$msgs;
        }
        echo json_encode($res);
        
    }
    
    
}