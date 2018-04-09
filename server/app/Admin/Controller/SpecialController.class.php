<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月8日18:50:25
* 最新修改时间：2018年4月8日18:50:25
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
namespace Admin\Controller;
use Think\Controller;
class SpecialController extends CommonController{
    
    public function get(){
        $special_id=I('special_id');
        $Special=D('Special');
        $result=$Special->get($special_id);
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    public function getList(){
        
        
        
        $Special=D('Special');
        $result=$Special->getList(I());
        $res['count']=$Special->count()+0;
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
            $res['I']=I();
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    public function add(){
        
        
        $Special=D('Special');
        $add=I('add');
        $add['special_id']=getMd5('special');
        $add['add_time']=time();
        $add['edit_time']=time();
        $result=$Special->add($add);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
    
    public function save(){
        
        $Special=D('Special');
        $where=I('where');
        $save=I('save');
        $result=$Special->where($where)->save($save);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function addGoods(){
        
        $SpecialGoods=D('SpecialGoods');
        
        $goodsIds=I('goodsIds');
        $special_id=I('special_id');
        $add=[];
        
        
        for ($i=0; $i < count($goodsIds); $i++) {
            $goods_id=$goodsIds[$i];
            $special_goods_id=getMd5('special_goods');
            
            //如果存在就不添加
            $item=[];
            $item['special_id']=$special_id;
            $item['goods_id']=$goods_id;
            
            $isAdd=$SpecialGoods->where($item)->find();
            
            if(!$isAdd){
                //不存在
                $item['special_goods_id']=$special_goods_id;
                $item['add_time']=time();
                $item['edit_time']=time();
                
                $add[]=$item;
            }
        }
        
        if(count($add)>0){
            $result=$SpecialGoods->addAll($add);
        }else{
            $result=1;
        }
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    //删除商品
    public function delGoods(){
        
        $SpecialGoods=D('SpecialGoods');
        
        $goods_id=I('goods_id');
        $special_id=I('special_id');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['special_id']=$special_id;
        $result=$SpecialGoods->where($where)->delete();
        
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