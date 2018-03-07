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
class AdminController extends CommonController{
    /**
    * 新增
    */
    public function add(){
        
        $add=I('add');
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['admin_pwd']=md5($add['admin_pwd'].__KEY__);
        $model=M('admin');
        $result=$model->add($add,null,true);
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
        $where=I('where');
        if(!$where){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $model=M('admin');
        $result=$model
        ->where($where)
        ->field('admin_id,admin_name,add_time,edit_time')
        ->find();
        if($result){
            
            $result=toTime([$result])[0];
            $res['res']=1;
            $res['msg']=$result;
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getList(){
        
        $model=M('admin');
        $result=$model
        ->order('add_time asc')
        ->select();
        //=========判断=========
        if($result){
            
            $result=toTime($result);
            $res['res']=$result;
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
    
    
    public function del(){
        
        $where=I('where');
        $model=M('admin');
        $result=$model->where($where)->delete();
        
        //=========判断=========
        if($result){
            $res['res']=$result;
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
    public function save(){
        
        $model=M('admin');
        
        $where=I('where');
        $save=I('save','',false);
        
        unset($save['admin_id']);
        unset($save['add_time']);
        unset($save['admin_pwd2']);
        $save['edit_time']=time();
        
        if(isset($save['admin_pwd'])){
            $save['admin_pwd']=md5($save['admin_pwd'].__KEY__);
        }
        
        $save=arrToString($save);
        $result = $model->where($where)->save($save);
        $res['msg']=$result;
        
        if($result===false){
            $res['res']=-1;
        }
        if($result>0){
            $res['res']=1;
        }
        if($result===0){
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
}