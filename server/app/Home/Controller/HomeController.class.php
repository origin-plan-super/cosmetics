<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends CommonController {
    
    //获得首页数据包
    public function getPacket(){
        $res=[];
        
        //轮播图
        $model=M('carousel');
        $carousel=$model
        ->order('sort asc,add_time desc')
        ->select();
        //商品列表
        
        $navList=['精选直供','美妆个护','家居生活','蔬果生鲜'];
        
        
        $Goods=D('Goods');
        $goodsList  =  $Goods->getList(I());
        
        $res['carousel']=$carousel;
        $res['goods']=$goodsList;
        $res['navList']=$navList;
        
        echo json_encode($res);
        
    }
    
    
    
    
    
}