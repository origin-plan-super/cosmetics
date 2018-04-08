<?php
namespace Home\Model;
use Think\Model;
class BagModel extends Model {
    
    
    public function getList($where=[]){
        
        $where=$where;
        $where['user_id']=session('user_id');
        
        $bag=$this
        ->where($where)
        ->order('add_time desc')
        ->select();
        
        //构建商品数据
        $Goods=D('goods');
        $Sku=M('sku');
        for ($i=0; $i <count($bag) ; $i++) {
            
            $goods_id  = $bag[$i]['goods_id'];
            $goodsInfo = $Goods->get($goods_id,['img_list']);
            $bag[$i]['goods_info']=$goodsInfo;
            //找选中的sku
            
            $where=[];
            $where['sku_id']= $bag[$i]['sku_id'];
            
            $skus= $Sku->where($where)->find();
            $bag[$i]['sku']=$skus;
            
        }
        $bag=toTime($bag);
        return $bag;
        
    }
    
    
    
}