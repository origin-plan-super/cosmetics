<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2017年11月17日
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####需要登录权限的继承本控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    
    //ThinkPHP提供的构造方法
    public function _initialize() {
        
        
        
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
                if(($result['edit_time']+3600)>$toTome ){
                    
                    // 未到期
                    // 可以继续查操作
                    
                }else{
                    //到期了，同样是token不正确
                    $res['res']=-992;
                    echo json_encode($res);
                    exit;
                }
                
                
            }else{
                //验证失败，因为token不正确
                $res['res']=-992;
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
    
}