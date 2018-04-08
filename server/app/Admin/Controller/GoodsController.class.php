<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月6日10:46:01
* 最新修改时间：2018年2月6日10:46:01
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
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends CommonController{
    
    /**
    * 新增
    */
    public function add(){
        
        $add=I('add','',false);
        $goods_id=getMd5('goods');
        
        $imgList=$add['img_list'];
        
        //添加图片
        addGoodsSku($goods_id,$add);
        
        $Goods=M('goods');
        
        $goodsAdd=[];
        $goodsAdd['goods_id']=$goods_id;
        $goodsAdd['goods_title']=$add['goods_title'];
        $goodsAdd['logistics']=$add['logistics'];
        $goodsAdd['is_up']=$add['is_up'];
        $goodsAdd['goods_class']=$add['goods_class'];
        $goodsAdd['goods_content']=$add['goods_content'];
        
        $goodsAdd['add_time']=time();
        $goodsAdd['edit_time']=time();
        
        
        $result=$Goods->add($goodsAdd);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    //获得一个
    public function get(){
        
        
        $model=M('goods');
        $goods_id=I('goods_id');
        
        $where=[];
        $where['goods_id']=$goods_id;
        
        $goods=$model->where($where)->find();
        
        
        $goods=getGoodsSku($goods);
        
        if($goods){
            $res['res']=1;
            $res['msg']=$goods;
        }else{
            $res['res']=-1;
            $res['msg']=$goods;
        }
        echo json_encode($res);
        
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
    
    public function up(){
        
        $save=I('save','',false);
        $goods_id=$save['goods_id'];
        
        $Goods=M('goods');
        $goodsSave=[];
        $goodsSave['is_up']                 =               $save['is_up'];
        
        $where=[];
        $where['goods_id']=$goods_id;
        $Goods->where($where)->save($goodsSave);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
    
    public function saveInfo(){
        
        $Goods=M('goods');
        
        $save=I('save','',false);
        unset($save['goods_id']);
        unset($save['add_time']);
        
        
        $where=I('where');
        $Goods->where($where)->save($save);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function save(){
        
        $Goods=M('goods');
        
        $save=I('save','',false);
        $goods_id=$save['goods_id'];
        
        unset($save['goods_id']);
        unset($save['add_time']);
        
        addGoodsSku($goods_id,$save);
        
        //保存商品
        $goodsSave=[];
        $goodsSave['goods_title']           =               $save['goods_title'];
        $goodsSave['logistics']             =               $save['logistics'];
        $goodsSave['is_up']                 =               $save['is_up'];
        $goodsSave['goods_class']           =               $save['goods_class'];
        $goodsSave['goods_content']         =               $save['goods_content'];
        $goodsSave['edit_time']             =               time();
        
        $where=[];
        $where['goods_id']=$goods_id;
        $Goods->where($where)->save($goodsSave);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
}