<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月5日11:09:123
* 最新修改时间：2018年3月5日11:09:123
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户控制器#####
* @author 代码狮
*
*/

namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController{
    
    public function save(){
        $model=M('user');
        $save=I('save','',false);
        $where=[];
        $where['user_id']=session('user_id');
        unset($save['user_id']);
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
            $save['edit_time']=time();
            $result=$model->where($where)->save($save);
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}