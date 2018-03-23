<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:16:39
* 最新修改时间：2018年3月2日11:16:39
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####购物单管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class BagController extends CommonController{
    
    
    //获得列表
    public function getList(){
        
        $model=M('bag');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=[];
        $where['user_id']=session('user_id');
        
        $result=$model
        ->table('c_bag as t1,c_goods as t2')
        ->field('t1.*,t2.*,t1.spec as user_spec')
        ->where($where)
        ->where('t1.goods_id = t2.goods_id')
        ->order('t1.add_time desc')
        // ->limit(($page-1)*$limit,$limit)
        ->select();
        
        if($result){
            $result=toTime($result);
            
            $map=[];
            $map['spec']=false;
            $map['user_spec']=false;
            $map['img_list']=false;
            
            $result=arrJsonD($result,$map);
            
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    
    
    //保存字段
    public function save(){
        
        $save=I('save','',false);
        $model=M('bag');
        $where=I('where');
        $where['user_id']=session('user_id');
        
        
        if($save['spec']){
            $save['spec']=json_encode($save['spec']);
        }
        
        $result=$model->where($where)->save($save);
        if($result===0){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            if($result){
                $res['res']=$result;
                $res['msg']=$result;
            }else{
                $res['res']=-1;
                $res['msg']=$result;
            }
        }
        
        echo json_encode($res);
        
    }
    
    public function add(){
        
        $add=I('add');
        $model=M('bag');
        $where=[];
        $where['user_id']=session('user_id');
        $where['goods_id']=$add['goods_id'];
        
        $result=$model->where($where)->find();
        //=========判断=========
        if($result){
            
            //追加数量
            $result=$model->where($where)->setInc('goods_count',$add['goods_count']);
            
            if($result){
                
                $res['res']=$result;
                $res['msg']=$result;
                $res['data']=$add;
                
                $save['edit_time']=time();
                $save['spec']=json_encode($add['spec']);
                $result=$model->where($where)->save($save);
                
            }else{
                $res['res']=-1;
                $res['msg']=$result;
                $res['data']=$add;
            }
            
            $res['bag_num']=getBagNum();
            echo json_encode($res);
            
        }else{
            //新增
            
            $add['bag_id']=getMd5('bag');
            $add['user_id']=session('user_id');
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['spec']=json_encode($add['spec']);
            $result=$model->add($add);
            if($result){
                $res['res']=$result;
                $res['msg']=$result;
                $res['data']=$add;
            }else{
                $res['res']=-1;
                $res['msg']=$result;
                $res['data']=$add;
            }
            
            $res['bag_num']=getBagNum();
            echo json_encode($res);
        }
        
        
    }
    
    public function del(){
        
        $model=M('bag');
        $where=[];
        $where['user_id']=session('user_id');
        $where['bag_id']=I('bag_id');
        $result=$model->where($where)->delete();
        
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        $res['I']=I();
        
        echo json_encode($res);
        
        
    }
    
    
}