<?php
namespace Home\Model;
use Think\Model;
class CarouselModel extends Model {
    
    
    public function _initialize (){
    }
    
    public function getList($data){
        
        $carouselList  =  $this
        ->order('sort desc,add_time desc')
        ->where($where)
        ->select();
        return $carouselList;
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