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
        
        
        $model=M('order');
        
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $count=$model
        ->order('add_time desc')
        ->where($where)
        ->count();
        $res['count']=$count+0;
        
        
        $order=$model
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        $order= toTime($order);
        
        $orderList=[];
        $model=M('order_info');
        $goods=M('goods');
        
        for ($i=0; $i <count($order) ; $i++) {
            
            $order_id=$order[$i]['order_id'];
            $where=[];
            $where['order_id']=$order_id;
            $order_info=$model->where($where)->find();
            
            $item=[];
            $item=$order[$i];
            $item['order_info']=json_decode($order_info['order_info'],true);
            $item['order_info']['goods']=[];
            
            //找到商品
            foreach ($item['order_info']['goods_id_list'] as $key => $value) {
                
                $where=[];
                $where['goods_id']=$value;
                $re_goods=$goods->where($where)->find();
                
                $map=[];
                $map['img_list']=false;
                $map['goods_class']=false;
                $map['spec']=false;
                $re_goods=arrJsonD([$re_goods],$map);
                
                $item['order_info']['goods'][]=$re_goods[0];
                
            }
            
            $orderList[]=$item;
        }
        
        //=========判断=========
        if($orderList){
            $res['res']=$count+0;
            $res['msg']=$orderList;
        }else{
            $res['res']=-1;
            $res['msg']=$orderList;
        }
        //=========判断end=========
        
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
        
        
    }
    
    
    
    //保存字段
    public function save(){
        
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