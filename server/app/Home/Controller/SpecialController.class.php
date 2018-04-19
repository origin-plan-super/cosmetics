<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月4日18:07:22
* 最新修改时间：2018年4月4日18:07:22
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####专题控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class SpecialController extends CommonController{
    
    //获得专题页数据包
    public function getPacket(){
        $Special=D('Special');
        $special=$Special->get(I('special_id'));
        
        if($special){
            $res['res']=1;
            $res['msg']=$special;
        }else{
            $res['res']=-1;
            $res['msg']=$special;
        }
        echo json_encode($res);
        
    }
    
    //单独获得专题数据
    public function getSmallList(){
        
        $Special=D('Special');
        $specials=$Special->getSmallList(I());
        if($specials){
            $res['res']=count($specials);
            $res['msg']=$specials;
        }else{
            $res['res']=-1;
            $res['msg']=$specials;
        }
        echo json_encode($res);
        
    }
    
}