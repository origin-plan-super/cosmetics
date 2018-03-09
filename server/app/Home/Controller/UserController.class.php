<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月5日11:09:123
* 最新修改时间：2018年3月5日11:09:123
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户控制器#####
* @author 代码狮
*
*/

namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController{
    
    public function save(){
        $model=M('user');
        $save=I('save','',false);
        $where=[];
        $where['user_id']=session('user_id');
        unset($save['user_id']);
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
            $save['edit_time']=time();
            $result=$model->where($where)->save($save);
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function getUserInfo(){
        $field=I('field');
        if(!$field){
            $res['res']=-2;
            echo json_encode($res);
            exit;
        }
        if(gettype($field)!='array'){
            //field不是数组。不能提交
            $res['res']=-4;
            echo json_encode($res);
            exit;
        }
        //权限处理
        //这里是禁止访问的用户字段
        $arr = array(
        'user_pwd',
        );
        //循环检查
        foreach ($field as $key => $value) {
            if(in_array($value,$arr)){
                //当字段数组中出现禁止访问的字段
                $res['res']=-3;
                echo json_encode($res);
                exit;
            }
        }
        
        //到这一步，初步权限检测通过。
        $model=M('user');
        $where=[];
        $where['user_id']=session('user_id');
        $result=$model->where($where)->field($field)->find();
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
}