<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    
    public function index(){
        echo "<h1>CTOS检测中心 ，项目： ".APP_NAME." ，分组： home </h1>";
        dump(session());
        
    }
    
    
    
    public function test(){
        $res['res']=1;
        $res['get']=I('get.');
        $res['post']=I('post.');
        echo json_encode($res);
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
                
                $token=createToken($user_id);
                
                if($token){
                    $res['res']=1;
                    $res['token']=$token;
                }else{
                    $res['res']=-1;
                    $res['msg']=I();
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
        
        $is=isUserLogin('user');
        if($is<0){
            $res['res']= $is;
        }else{
            $res['res']= 1;
        }
        $res['res']= 1;
        echo json_encode($res);
        
    }
    
    public function sinOut(){
        
        
        //获得传来的token
        $token=I('token');
        //获得传来的id
        $user_id=I('user_id');
        
        //创建token的控制器
        $model=M('token');
        //创建条件
        $where=[];
        $where['login_id']=$user_id;
        //删除token
        $result=$model->where($where)->delete();
        
        //清空session
        session(null);
        
        if($result!==false){
            //退出成功
            $res['res']=-991;
        }else{
            //退出失败
            $res['res']=-1;
        }
        
        echo json_encode($res);
    }
    
}