<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月5日10:56:44
* 最新修改时间：2018年3月5日10:56:44
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####动态控制器#####
* @author 代码狮
*
*/

namespace Home\Controller;
use Think\Controller;
class DynamicController extends CommonController{
    
    //获得发现页数据包
    public function getPacket(){
        $res=[];
        
        //轮播图
        $model=M('carousel');
        $carousel=$model
        ->order('sort asc,add_time desc')
        ->select();
        
        
        $user_id=session('user_id');
        $res=[];
        $model=M('dynamic');
        $res['count']=$model->count()+0;
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        
        $where=[];
        $where['t1.is_show']= 1;
        
        $dynamic=$model
        ->table("c_dynamic as t1,c_goods as t2,c_user as t3")
        ->field('t1.img_list as dynamic_img_list,t2.img_list as goods_img_list,t1.*,t2.*,t3.*')
        ->where('t1.goods_id = t2.goods_id AND t1.user_id = t3.user_id')
        ->where($where)
        ->order('t1.add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        //=========判断=========
        if($dynamic){
            //转换 img_list 和 goods_list
            $map['dynamic_img_list']=false;
            $map['goods_img_list']=false;
            $dynamic=arrJsonD($dynamic,$map);
        }
        
        
        $res['carousel']=$carousel;
        $res['dynamic']=$dynamic;
        echo json_encode($res);
        
    }
    //添加
    public function add(){
        //要添加的数据
        $add=[];
        //获得发来的要添加的数据
        $add=I('add','',false);
        if(!$add){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        //模型
        $model=M('dynamic');
        
        //设置用户id
        $add['user_id']=session('user_id');
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['dynamic_id']=getMd5('dynamic');
        
        //添加进去
        $result=$model->add($add);
        // 判断
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    //点赞
    public function up(){
        
        //模型
        $model=M('dynamic_up');
        $add['user_id']=session('user_id');
        $add['dynamic_id']=I('dynamic_id');
        
        
        if(!I('dynamic_id')){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        
        
        //判断是否已经点赞
        $is_up=$model->where($add)->find();
        if($is_up!=null){
            $res['res']=-3;
            echo json_encode($res);
            die;
        }
        
        
        $add['edit_time']=time();
        $add['add_time']=time();
        $result=$model->add($add);
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    //取消点赞
    public function delUp(){
        
        $model=M('dynamic_up');
        $where['user_id']=session('user_id');
        $where['dynamic_id']=I('dynamic_id');
        $result=$model->where($where)->delete();
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    public function get(){
        
        $user_id=session('user_id');
        $dynamicModel=M('dynamic');
        $dynamic_id=I('dynamic_id');
        if(!$dynamic_id){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $where=[];
        $where['dynamic_id']= $dynamic_id;
        $where['is_show']=1;
        $dynamic= $dynamicModel->where($where)->find();
        if($dynamic!=null){
            //有数据
            //找评论和对应的用户
            $where=[];
            $where['t1.dynamic_id']= $dynamic_id;
            $where['t1.is_show']=1;
            
            $commentModel=M('comment');
            $comment_list= $commentModel
            ->table('c_comment as t1,c_user as t2')
            ->where($where)
            ->where("t1.user_id = t2.user_id")
            ->field("t1.*,t2.user_name as user_name,t2.user_head as user_head")
            ->order('t1.add_time desc')
            ->select();
            
            $dynamic['comment_list']=$comment_list;
            
            
            //找点赞数
            $where=[];
            $where['dynamic_id']= $dynamic_id;
            $dynamic_upModel=M('dynamic_up');
            $dynamic_upModel->where($where)->count();
            
            //当前用户是否点赞
            $where['user_id']=$user_id;
            if($dynamic_upModel->where($where)->find()){
                $dynamic['is_thumbs_up']=true;
            }else{
                $dynamic['is_thumbs_up']=false;
            }
            
            //转换 img_list 和 goods_list
            $map['img_list']=false;
            $map['goods_list']=false;
            $dynamic=arrJsonD([$dynamic],$map)[0];
            
            //如果有商品，还需要获得商品
            $goods=M('goods');
            if(count($dynamic['goods_list'])>0){
                $where=[];
                $where['goods_id']=array('in',$dynamic['goods_list']);
                $dynamic['goods_list']=$goods->where($where)->select();
            }
            
            $res['res']=1;
            $res['msg']=$dynamic;
            
            
        }else{
            $res['res']=-1;
            $res['msg']=$dynamic;
        }
        
        
        echo json_encode($res);
        
    }
    
    
    //获得列表
    public function getList(){
        
        $user_id=session('user_id');
        $res=[];
        $model=M('dynamic');
        $res['count']=$model->count()+0;
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        
        $where=[];
        $where['is_show']= 1;
        
        $result=$model
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        //=========判断=========
        if($result){
            //转换 img_list 和 goods_list
            $map['img_list']=false;
            $map['goods_list']=false;
            $result=arrJsonD($result,$map);
            
            $model=M('dynamic_up');
            $goods=M('goods');
            
            foreach ($result as $key => $value) {
                $where=[];
                $where['dynamic_id']=$value['dynamic_id'];
                //计算点赞数
                $result[$key]['good_count']=$model->where($where)->count();
                
                $where['user_id']=$user_id;
                
                if($model->where($where)->find()){
                    //此用户点赞了
                    $result[$key]['is_thumbs_up']=true;
                }else{
                    $result[$key]['is_thumbs_up']=false;
                }
                //如果有商品，还需要获得商品
                if(count($value['goods_list'])>0){
                    
                    $where=[];
                    $where['goods_id']=array('in',$value['goods_list']);
                    
                    $result[$key]['goods_list']=$goods->where($where)->select();
                    $map['img_list']=true;
                    $result[$key]['goods_list']=arrJsonD($result[$key]['goods_list'],$map);
                    
                }
                
            }
            
            $res['res']=count($result);
            $res['msg']=$result;
            
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        //=========判断end=========
        
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
        
    }
    
    
}