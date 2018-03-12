<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    
    public function index(){
        
        echo '<h1>CTOS检测中心</h1>';
        dump(session());
        dump(F());
        
    }
    
    public function get(){
        
        $res['res']=1;
        $res['get']=I('get.');
        $res['post']=I('post.');
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
    }
    
    public function test(){
        echo '<h1>CTOS控制中心</h1>';
        die;
        $model=M('feedback');
        
        $data=$model->select();
        
        for ($i=0; $i < count($data); $i++) {
            $where=[];
            $save['info']='lorem '.($i+1);
            $where['feedback_id']=$data[$i]['feedback_id'];
            $model->where($where)->save($save);
            dump($model->_sql());
        }
        
        
    }
    
    public function login(){
        
        $user_id=I('post.user_id');
        $user_code=I('post.user_code');
        $verifyCode=F(md5($user_id.'_verifyCode'));
        F(md5($user_id.'_verifyCode'),null);
        
        //检查参数
        if(!$verifyCode || !$user_id || !$user_code ){
            //少一样都不行
            $res['res']=-2;
            echo json_encode($res);
            die;
        }else{
            //有验证码
            //验证码加密算法：用户id+验证码+密匙
            
            $isSuccess= $verifyCode==md5($user_id.$user_code.__KEY__);
            
            if($isSuccess){
                //验证码正确
                //生成 token
                //换取token
                $token=md5($user_id.time().rand().__KEY__);
                $model=M('token');
                
                //先删除原本的 token
                // $where=[];//条件
                // $where['user_id']=$user_id;//条件
                // $model->where($where)->delete();//删除
                
                //添加
                $add=[];
                $add['edit_time']=time();
                $add['user_id']=$user_id;
                $add['token']=$token;
                $result=$model->add($add,null,true);
                
                if($result){
                    $res['res']=1;
                    $res['token']=$token;
                }else{
                    $res['res']=-1;
                    $res['msg']=$result;
                }
                
                echo json_encode($res);
                
            }else{
                //验证码不正确
                $res['res']=-3;
                echo json_encode($res);
            }
        }
        
        
    }
    
    /**
    * 获得手机验证码
    */
    public function getCode(){
        
        
        $user_id=I('user_id');
        if(!$user_id){
            //没有传参数
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        //如果没有此用户，那么这个用户在这里就等于注册
        
        $model=M('user');
        $where=[];
        $where['user_id']=$user_id;
        $isUser=$model->where($where)->find();
        if(!$isUser){
            //没有用户，需要注册
            $add=[];
            $add['user_id']=$user_id;
            $add['user_name']='用户'.rand(0,99999);
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['user_type']=0;
            $model->add($add);
        }
        
        //生成短信验证码
        $code=rand(1000,9999);
        //在这里发送短信
        
        //加密验证码
        $verifyCode=md5($user_id.$code.__KEY__);
        //储存验证码
        F(md5($user_id.'_verifyCode'),$verifyCode);
        
        $res['res']=1;
        $res['msg']=$code;
        
        echo json_encode($res);
        
    }
    /**
    * 判断是否登录
    */
    public function islogin(){
        
        $is=isUserLogin();
        
        if($is==1){
            //登录还未过期
            $res['res']=$is;
            $res['msg']='已登录';
            //=========输出json=========
            echo json_encode($res);
            //=========输出json=========
        }
        
        if($is==-991){
            //令牌过期了
            $res['res']=$is;
            $res['msg']='令牌过期了！';
            //=========输出json=========
            echo json_encode($res);
            //=========输出json=========
        }
        if($is==-992){
            //未登录
            $res['res']=$is;
            $res['msg']='未登录！';
            //=========输出json=========
            echo json_encode($res);
            //=========输出json=========
        }
        
        
    }
    
}