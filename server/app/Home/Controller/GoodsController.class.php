<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:17:23
* 最新修改时间：2018年3月2日11:17:23
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class GoodsController extends CommonController{
    
    //取得详情页数据包
    public function getPacket(){
        
    }
    
    
    //获得商品列表
    public function getList(){
        
        $Goods=D('Goods');
        $data=I();
        $goodsList  =  $Goods->getList($data);
        // =========判断=========
        if($goodsList){
            //总条数
            $goodsList      =   toTime($goodsList);
            $res['res']     =   count($goodsList);
            $res['msg']     =   $goodsList;
            $res['count']   =   $Goods->count()+0;
            
        }else{
            $res['res']     =   0;
        }
        echo json_encode($res);
        
    }
    
    public function get(){
        
        // 5de29730ce2d36ab744fcf9e70bc6a9f
        $goods_id=I('goods_id');
        if(!$goods_id){
            $res['res']=-2;
            $res['msg']='没有goods_id';
            echo json_encode($res);
            die;
        }
        
        $Goods=D('Goods');
        $goods=$Goods->get($goods_id);
        
        if($goods){
            $res['res']=1;
            $res['msg']=$goods;
        }else{
            $res['res']=-1;
            $res['msg']=$goods;
        }
        echo json_encode($res);
        
    }
    
    public function query(){
        $model=M('goods');
        $key = I('key');
        echo json_encode($key);
        
    }
    
    
}