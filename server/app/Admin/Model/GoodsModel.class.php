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
        $where  =   $data['where']?$data['where']:[];
        
        $goodsList  =  $this
        ->order('add_time desc')
        ->where($where)
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
        //没有商品或者没有上架
        
        $goods=getGoodsSku($goods,$map);
        $goods=toTime([$goods])[0];
        //找是否收藏
        return $goods;
    }
    
    //删除事务
    public function del($goods_id){
        //删除关联的东西
        $is=true;
        
        $where=[];
        $where['goods_id']=['in',$goods_id];
        $result=$this->where($where)->delete();
        
        $Models=[];
        
        $Models['NavGoods']=$NavGoods=D('NavGoods');
        $Models['GoodsImg']=$GoodsImg=D('GoodsImg');
        $Models['Sku']=$Sku=D('Sku');
        $Models['SkuTree']=$SkuTree=D('SkuTree');
        $Models['SkuTreeV']=$SkuTreeV=D('SkuTreeV');
        $Models['SpecialGoods']=$SpecialGoods=D('SpecialGoods');
        $Models['Time']=$Time=D('Time');
        //删除阵列
        foreach ($Models as $key => $Model) {
            $is=$Model->where($where)->delete()!==false;
            if($is==false){
                return $is;
            }
        }
        return $is;
    }
    
    
}