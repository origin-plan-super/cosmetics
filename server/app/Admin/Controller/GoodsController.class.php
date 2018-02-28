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
        
        //json 转字符串
        // $add=arrToString($add);
        // $map=['img_list','goods_class','spec'];
        // echo json_encode([$add,stringToArr([$add],$map)[0]['spec']['spec'][0]]);
        // die;
        
        
        
        $map=[];
        $map['img_list']=false;
        $map['goods_class']=false;
        $map['spec']=false;
        
        
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['goods_id']=getMd5();
        
        $model=M('goods');
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
    
    //获得一个
    public function get(){
        
    }
    
    
    
    
    //获得商品列表
    public function getList(){
        
        $model=M('goods');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $result=$model
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        // =========判断=========
        if($result){
            //总条数
            $result=toTime($result);
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$result;
            
            
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