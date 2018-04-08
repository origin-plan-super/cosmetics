<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model {
    
    public  $Goods ;
    
    public function _initialize (){
        $this->Goods=M('goods');
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:0;
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
    
    
    
}