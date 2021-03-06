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
class OrderController extends CommonController{
    
    
    //获得总数
    public function getCount(){
        $model=M('order');
        $count=$model->count();
        $res['res']=$count+0;
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
    }
    
    
    //获得列表
    public function getList(){
        
        
        $Order=D('Order');
        
        $result=$Order->getList(I());
        
        $res['count']=$Order->count()+1;
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
    public function get(){
        $Order=D('Order');
        
        $result=$Order->get(I('order_id'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    //保存字段
    public function save(){
        
        $where=I('where');
        $table=I('table');
        
        
        if(!$where || !$table){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $model=M($table);
        
        $save=I('save','',false);
        
        unset($save['order_id']);
        unset($save['add_time']);
        $save['edit_time']=time();
        
        $save=arrToString($save);
        $result = $model->where($where)->save($save);
        $res['msg']=$result;
        
        //=========判断=========
        if($result===false){
            $res['res']=-1;
        }
        if($result>0){
            $res['res']=1;
        }
        if($result===0){
            $res['res']=0;
        }
        
        //=========判断end=========
        
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
    }
    
    public function add(){
        
        $post=I('post','',false);
        
        //购物车id是个数组
        $bag_id=$post['bag_id'];
        $bag_id=['5985a7bc94a751e4c6c0532b9b31466e','1eec9fe6e61a1a51a6ad764e7158b64c'];
        
        if(!$bag_id){
            $res['res']=-3;
            
            //=========判断end=========
            //=========输出json=========
            echo json_encode($res);
            //=========输出json=========
            die;
        }
        
        
        //取出购物车中的东西
        $model=M('bag');
        $where['bag_id']=array('in',$bag_id);
        
        $result= $model
        ->where($where)
        ->field('t1.*,t2.*,t1.spec as user_spec,t2.spec as goods_spce')
        ->table('c_bag as t1,c_goods as t2')
        ->where('t1.goods_id = t2.goods_id')
        ->select();
        
        $goods_id_list=[];
        $user_spec=[];
        for ($i=0; $i <count($result) ; $i++) {
            $goods_id=$result[$i]['goods_id'];
            $goods_id_list[]=  $goods_id;
            if($result[$i]['user_spec']){
                $sp=[];
                $sp['颜色']='红色';
                $sp['材质']='铁';
                $sp['大小']='10*10';
                $sp['money']=1.1;
                $sp['stock']=1.1;
                $user_spec[$goods_id]=$sp;
            }else{
                $user_spec[$goods_id]=$result[$i]['user_spec'];
            }
        }
        $order_money=0;
        //计价
        for ($i=0; $i <count($result) ; $i++) {
            $count=$result[$i]['goods_count'];
            $money=0;
            if(empty($result[$i]['goods_count']['user_spec'])){
                //没有
                $money=1.1;
            }else{
                //有spce
                $money=$result[$i]['goods_count']['user_spec']['money'];
            }
            $order_money+=($count*$money);
        }
        
        $order_id=date('YmdHis',time()).rand(1000,9999);
        
        $add=[];
        $add['order_id']=$order_id;
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['user_id']='12138';
        $add['money']=$order_money;
        
        $model=M('order');
        $result=$model->add($add);
        $result=true;
        // dump($add);
        if($result){
            //添加成功
            //开始添加订单信息
            $model=M('order_info');
            
            $order_info=[];
            $order_info['goods_id_list']=$goods_id_list;
            $order_info['user_spec']=$user_spec;
            $add=[];
            $add['order_id']=$order_id;
            $add['order_info']=json_encode($order_info);
            // $add['order_info']=$order_info;
            $result=$model->add($add);
            
            // dump($add);
            
            if($result){
                $res['res']=1;
                $res['msg']=$order_id;
            }else{
                $res['res']=-1;
            }
            echo json_encode($res);
            
        }else{
            ///添加失败
            
            $res['res']=-2;
            echo json_encode($res);
            
        }
        
        
    }
    
    
}