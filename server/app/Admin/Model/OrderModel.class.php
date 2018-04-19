<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model {
    
    
    public function _initialize (){}
    
    public function getList($data){
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        // ===================================================================================
        // 创建模型
        $Snapshot=D('Snapshot');
        $OrderAddress=D('OrderAddress');
        
        
        // ===================================================================================
        // 找订单数据
        $orders=$this
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        // ===================================================================================
        // 根据订单找快照、收货地址、
        
        foreach ($orders as $key => $order) {
            $snapshot_id=$order['snapshot_id'];
            $address_id=$order['address_id'];
            
            $snapshot=$Snapshot->get($snapshot_id);
            $address=$OrderAddress->get($address_id);
            
            $order['snapshot']=$snapshot;
            $order['address']=$address;
            $orders[$key]=$order;
        }
        
        
        return $orders;
        
        
    }
    
    public function get($order_id){
        
        
        // ===================================================================================
        // 创建模型
        $Snapshot=D('Snapshot');
        $OrderAddress=D('OrderAddress');
        
        
        // ===================================================================================
        // 查询订单
        $where=[];
        $where['order_id']=$order_id;
        $order=$this
        ->where($where)
        ->find();
        
        if(!$order){
            return null;
        }
        // ===================================================================================
        // 查询详情
        $snapshot_id=$order['snapshot_id'];
        $address_id=$order['address_id'];
        
        $snapshot=$Snapshot->get($snapshot_id);
        $address=$OrderAddress->get($address_id);
        
        $order['snapshot']=$snapshot;
        $order['address']=$address;
        
        return $order;
        
    }
    
    
}