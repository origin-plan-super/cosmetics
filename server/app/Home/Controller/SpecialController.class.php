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
        $res=[];
        
        $special=[];
        $special['banner']='http://cosmetics.com/Public/Upload/carousel/2018-04-03/5ac39b514b5b4.jpg';
        $special['info']='春季特卖春季特卖春季特卖春季特卖春季特卖春季特卖春季特卖春季特卖春季特卖春季特卖';
        
        $Goods=D('Goods');
        $goodsList  =  $Goods->getList(I());
        $special['goodsList']=$goodsList;
        
        $res['res']=1;
        $res['special']=$special;
        echo json_encode($res);
        
    }
    
    
}