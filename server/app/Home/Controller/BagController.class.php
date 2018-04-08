<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:16:39
* 最新修改时间：2018年3月2日11:16:39
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####购物单管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class BagController extends CommonController{
    
    
    //获得列表
    public function getList(){
        
        $Bag=D('bag');
        $bag=$Bag->getList();
        if($bag){
            $res['res']=count($bag);
            $res['msg']=$bag;
        }else{
            $res['res']=0;
        }
        echo json_encode($res);
        
    }
    
    
    //保存字段
    public function save(){
        
        $save=I('save','',false);
        $model=M('bag');
        $where=[];
        $where['user_id']=session('user_id');
        $where['bag_id']=$save['bag_id'];
        
        unset($save['bag_id']);
        
        $result=$model->where($where)->save($save);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
        }
        
        echo json_encode($res);
        
    }
    
    public function add(){
        
        //相同用户，相同商品，相同sku，组成一个购物车和购物车id
        
        
        $Bag=M('bag');
        
        $data=I('add');
        
        $goods_id=$data['goods_id'];
        $goods_count=$data['goods_count'];
        $sku_id=$data['sku_id'];
        $user_id=session('user_id');
        
        $bag_id=md5($user_id.$goods_id.$sku_id);
        
        $where=[];
        $where['bag_id']=$bag_id;
        //看看有没有已经添加过
        $isAdd=$Bag->where($where)->find();
        if($isAdd){
            //已经添加过
            //追加数据
            
            $where=[];
            $where['bag_id']=$bag_id;
            
            $result=$Bag->where($where)->setInc('goods_count',$goods_count);
            if($result){
                $res['res']=1;
                $res['msg']=$result;
            }else{
                $res['res']=-1;
                $res['msg']=$result;
            }
            
        }else{
            //未添加过
            //组成新数据
            $add=I('add');
            $add['user_id']=$user_id;
            $add['bag_id']=$bag_id;
            $add['add_time']=time();
            $add['edit_time']=time();
            
            $result=$Bag->add($add);
            if($result){
                $res['res']=1;
                $res['msg']=$result;
            }else{
                $res['res']=-1;
                $res['msg']=$result;
            }
            
        }
        
        echo json_encode($res);
        
    }
    
    public function del(){
        
        $Bag=M('bag');
        $where=[];
        $where['user_id']=session('user_id');
        $where['bag_id']=I('bag_id');
        $result=$Bag->where($where)->delete();
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function dels(){
        $Bag=M('bag');
        $where=[];
        $where['user_id']=session('user_id');
        $where['bag_id']=['in',I('ids')];
        $result=$Bag->where($where)->delete();
        
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