<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends CommonController {
    
    //获得首页数据包
    public function getPacket(){
        $res=[];
        
        //轮播图
        $model=M('carousel');
        $carousel=$model
        ->order('sort asc,add_time desc')
        ->select();
        
        //商品列表
        $model=M('goods');
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=[];
        $where['is_up']=1;
        
        $goods=$model
        ->where($where)
        ->order('sort desc,add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        // =========判断=========
        if($goods){
            $goods=toTime($goods);
            $map=[];
            $map['img_list']=false;
            $map['goods_class']=false;
            $map['spec']=false;
            $goods=arrJsonD($goods,$map);
        }
        
        
        $res['carousel']=$carousel;
        $res['goods']=$goods;
        echo json_encode($res);
        
    }
    
}