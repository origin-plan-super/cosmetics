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
namespace Admin\Controller;
use Think\Controller;
class MsgController extends CommonController{
    
    public function getList(){
        $Msg=M('Msg');
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $res['count']=$Msg->where($where)->count()+0;
        $msgs=$Msg->where($where)->limit(($page-1)*$limit,$limit)->select();
        $msgs=toTime($msgs);
        
        if($msgs){
            $res['res']=count($msgs);
            $res['msg']=$msgs;
        }else{
            $res['res']=-1;
            $res['msg']=$msgs;
        }
        echo json_encode($res);
        
    }
    
    public function add(){
        $add=I('add');
        $Msg=D('Msg');
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['msg_id']=getMd5('msg');
        $result=  $Msg->add($add);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        
        $msg_id=I('msg_id');
        $Msg=D('Msg');
        $where=[];
        $where['msg_id']=['in',$msg_id];
        $result=$Msg->where($where)->delete();
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