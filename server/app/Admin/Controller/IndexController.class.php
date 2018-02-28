<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    
    public function index(){
        
        echo '<h1>CTOS检测中心</h1>';
        dump(session());
    }
    
    public function get(){
        
        $arr=[];
        
        for ($i=0; $i < 200; $i++) {
            $arr[]=$i;
        }
        
        $get=I('get.');
        $post=I('post.');
        
        $res['get']=$get;
        $res['post']=$post;
        $res['arr']=$arr;
        
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
        
    }
    
    public function test(){
        
    }
    
    public function login(){
        
        // if(!empty(I('post.token'))){
        //     $token=I('post.token');
        //     $model=M('admin');
        //     $where=[];
        //     $where['token']=$token;
        //     $model->where($where)->delete();
        // }
        
        $admin_code=I('post.admin_code','false');
        $admin_pwd=I('post.admin_pwd','false');
        $admin_id=I('post.admin_id','false');
        
        $res=[];
        
        if(isCode($admin_code)){
            //正确
            //验证账户密码
            $result= login('admin',$admin_id,$admin_pwd);
            if($result){
                //账户和密码正确
                //留存用户信息
                session('admin_id',$admin_id);
                session('admin_name',$result['admin_name']);
                session('admin_head_img',$result['admin_head_img']);
                session('admin_pwd',$result['admin_pwd']);
                
                //创建token，并发送给客户端
                $token=md5(time().__KEY__);
                $save['token']=$token;
                $save['edit_time']=time();
                
                $model=M('admin');
                $where['admin_id'] = $admin_id;
                $model->where($where)->save($save);
                
                $res['res']=1;
                $res['msg']['token']=$token;
                $res['msg']['admin_id']=$admin_id;
                
            }else{
                //账户和密码不正确
                $res['res']=-1;
                $res['msg']['admin_id']=$admin_id;
            }
            
        }else{
            //验证码不正确
            $res['res']=-2;
            $res['msg']=session();
        }
        
        
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
    }
    
    /**
    * 获得验证码
    */
    public function getCode(){
        getCode();
    }
    /**
    * 判断是否登录
    */
    public function islogin(){
        
        
        if(!empty(I('token'))){
            
            $token=I('token');
            $admin_id=I('admin_id');
            $model=M('admin');
            $where['admin_id']=$admin_id;
            
            $result=$model->where($where)->find();
            
            if($result['token']==$token){
                //验证成功
                
                //再验证时间是否过期
                $toTome=time();
                if( ($result['edit_time']+99999999)>$toTome ){
                    //未到期
                    $res['res']=1;
                    echo json_encode($res);
                }else{
                    //到期了，同样是token不正确
                    $res['res']=-992;
                    $res['msg']='超时';
                    echo json_encode($res);
                    exit;
                }
                
                
            }else{
                //验证失败，因为token不正确
                $res['res']=-992;
                $res['msg']='token不正确';
                echo json_encode($res);
                exit;
            }
            
        }else{
            
            // 验证失败，因为没有token
            
            $res['res']=-991;
            echo json_encode($res);
            exit;
        }
        
    }
    public function sinOut(){
        session(null);
        $token=I('token');
        $admin_id=I('admin_id');
        $model=M('admin');
        $where['admin_id']=$admin_id;
        $save['token']='';
        $result=$model->where($where)->save($save);
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
    
}