<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日10:25:37
* 最新修改时间：2018年3月2日10:25:37
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户收藏控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CollectionController extends CommonController{
    
    public function change(){
        $model=M('collection');
        
        $where=[];
        $where['goods_id']=I('goods_id');
        $where['user_id']=session('user_id');
        $result=$model->where($where)->find();
        $res=[];
        if($result){
            //已存在，就取消收藏
            $result= $model->where($where)->delete();
            if($result){
                $res['res']=1;
                $res['msg']=0;
            }else{
                $res['res']=-2;
                $res['msg']='在取消收藏时出错';
            }
            
        }else{
            //不存在，就添加收藏
            $add=$where;
            $add['add_time']=time();
            $add['edit_time']=time();
            $result= $model->add($add);
            if($result){
                $res['res']=1;
                $res['msg']=1;
            }else{
                $res['res']=-1;
                $res['msg']='在添加收藏时出错';
            }
        }
        
        echo json_encode($res);
    }
    
    public function getList(){
        $model=M();
        $where=[];
        $where['user_id']=session('user_id');
        
        $result=$model
        ->table('c_collection as t1,c_goods as t2')
        ->where($where)
        ->where('t1.goods_id = t2.goods_id')
        ->order('t1.add_time desc')
        ->select();
        
        if($result){
            
            $map=[];
            $map['img_list']=false;
            $map['goods_class']=false;
            $map['spec']=false;
            
            $result=arrJsonD($result,$map);
            
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
}