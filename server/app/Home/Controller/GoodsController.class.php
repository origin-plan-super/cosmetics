<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:17:23
* 最新修改时间：2018年3月2日11:17:23
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
namespace Home\Controller;
use Think\Controller;
class GoodsController extends CommonController{
    
    
    //获得商品列表
    public function getList(){
        
        $model=M('goods');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=[];
        $where['is_up']=1;
        
        $result=$model
        ->where($where)
        ->order('sort desc,add_time desc')
        // ->limit(($page-1)*$limit,$limit)
        ->select();
        
        // =========判断=========
        if($result){
            //总条数
            $result=toTime($result);
            
            $map=[];
            $map['img_list']=false;
            $map['goods_class']=false;
            $map['spec']=false;
            
            $result=arrJsonD($result,$map);
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    
    public function get(){
        
        
        // 5de29730ce2d36ab744fcf9e70bc6a9f
        $goods_id=I('goods_id');
        if(!$goods_id){
            $res['res']=-2;
            $res['msg']='没有goods_id';
            echo json_encode($res);
            die;
        }
        $where=[];
        $where['goods_id']=$goods_id;
        
        $model=M('goods');
        
        $result=$model
        ->where($where)
        ->find();
        if($result){
            
            $result=toTime([$result])[0];
            $map=[];
            $map['img_list']=false;
            $map['goods_class']=false;
            $map['spec']=false;
            $result=arrJsonD([$result],$map)[0];
            
            //找是否收藏
            $model=M('collection');
            $where['user_id']=session('user_id');
            $collection=$model->where($where)->find();
            
            
            if($collection){
                $result['is_collection']=true;
            }else{
                $result['is_collection']=false;
            }
            
            $res['bag_num']=getBagNum();
            
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function query(){
        $model=M('goods');
        $key = I('key');
        echo json_encode($key);
        
    }
    
    
}