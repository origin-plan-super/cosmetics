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
class GoodsController extends CommonController{
    
    
    /**
    * 新增
    */
    public function add(){
        
        
        $add=I('add','',false);
        $goods_id=getMd5('goods');
        
        //这里有很多逻辑
        
        //创建此商品的sku表
        $Sku=M('sku');
        $skus=$add['sku'];
        for ($i=0; $i < count($skus); $i++) {
            $skus[$i]['goods_id']=$goods_id;
            $skus[$i]['sku_id']=md5($goods_id.$skus[$i]['id']);
            $skus[$i]['add_time']=time();
            $skus[$i]['edit_time']=time();
        }
        
        
        //创建此商品的sku tree 表
        
        $SkuTree=M('sku_tree');
        $SkuTreeV=M('sku_tree_v');
        
        $trees=$add['tree'];
        
        $treeV=[];
        
        for ($i=0; $i < count($trees); $i++) {
            
            $tree=$trees[$i];
            $sku_tree_id=md5($goods_id.$tree['k']);
            $tree['sku_tree_id']=$sku_tree_id;
            $tree['goods_id']=$goods_id;
            $tree['add_time']=time();
            $tree['edit_time']=time();
            
            $trees[$i]=$tree;
            //添加 tree 的 v
            
            for ($j=0; $j <count($tree['v']) ; $j++) {
                $v=$tree['v'][$j];
                $v['v_id']=md5($goods_id.$sku_tree_id.$v['id']);
                $v['goods_id']=$goods_id;
                $v['sku_tree_id']=$sku_tree_id;
                $v['img_url']='';
                $v['add_time']=time();
                $v['edit_time']=time();
                $treeV[]=$v;
            }
            unset($tree['v']);
        }
        
        
        $Sku->addAll($skus);
        $SkuTree->addAll($trees);
        $SkuTreeV->addAll($treeV);
        
        
        $Goods=M('goods');
        
        $goodsAdd=[];
        $goodsAdd['goods_id']=$goods_id;
        $goodsAdd['goods_title']=$add['goods_title'];
        $goodsAdd['logistics']=$add['logistics'];
        $goodsAdd['is_up']=$add['is_up'];
        $goodsAdd['goods_class']=$add['goods_class'];
        $goodsAdd['img_list']=json_encode($add['img_list']);
        $goodsAdd['goods_content']=$add['goods_content'];
        
        $goodsAdd['add_time']=time();
        $goodsAdd['edit_time']=time();
        
        
        $model=M('goods');
        $result=$model->add($goodsAdd);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    //获得一个
    public function get(){
        
    }
    
    
    //获得商品列表
    public function getList(){
        
        $model=M('goods');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $goodsList=$model
        ->table('c_goods')
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        
        //找 sku 和 tree
        
        $Sku=M('sku');
        $SkuTree=M('sku_tree');
        $SkuTreeV=M('sku_tree_v');
        
        for ($i=0; $i <count($goodsList) ; $i++) {
            $goods=$goodsList[$i];
            $goods_id=$goods['goods_id'];
            $where=[];
            $where['goods_id']=$goods_id;
            $skus= $Sku->where($where)->select();
            $goods['sku']=$skus;
            $tree= $SkuTree->where($where)->select();
            
            //找图片
            
            
            for ($j=0; $j <count( $tree) ; $j++) {
                //找 tree 的 v
                $sku_tree_id=$tree[$j]['sku_tree_id'];
                $where['sku_tree_id']=$sku_tree_id;
                $v= $SkuTreeV->where($where)->select();
                $tree[$j]['v']= $v;
            }
            
            $goods['tree']=$tree;
            $goodsList[$i]=$goods;
        }
        
        
        // =========判断=========
        if($goodsList){
            //总条数
            $goodsList=toTime($goodsList);
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$goodsList;
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    public function save(){
        
        $model=M('goods');
        
        $where=I('where');
        $save=I('save','',false);
        
        unset($save['goods_id']);
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
    
}