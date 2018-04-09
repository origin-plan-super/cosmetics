<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model {
    
    public  $Goods ;
    
    public function _initialize (){
        $this->Goods=M('goods');
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        
        $goodsList  =  $this
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        //找 sku 和 tree
        
        for ($i=0; $i <count($goodsList) ; $i++) {
            $goods              =     $goodsList[$i];
            $goodsList[$i]      =     getGoodsSku($goods);
        }
        
        
        return $goodsList;
        
        
    }
    
    //获得一个
    public function get($goods_id,$map=['img_list','sku','tree']){
        
        $where=[];
        $where['is_up']=1;
        $where['goods_id']=$goods_id;
        
        $goods=$this->where($where)->find();
        
        $goods=getGoodsSku($goods,$map);
        $goods=toTime([$goods])[0];
        //找是否收藏
        $model=M('collection');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['user_id']=session('user_id');
        $collection=$model->where($where)->find();
        
        $goods['is_collection']=!($collection==null);
        
        
        return $goods;
        
    }
    
    
}