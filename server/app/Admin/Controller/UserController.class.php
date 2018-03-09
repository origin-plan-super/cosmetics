<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月7日10:14:54
* 最新修改时间：2018年3月7日10:14:54
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
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController{
    
    public function getList(){
        
        $model=M('user');
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        
        $key=I('key');
        if($key){
            
            //key存在，添加搜索条件
            //如果存在就查询
            // $key=explode(" ",$key);
            $where['user_id|user_name'] = array(
            'like',
            '%'.$key."%",
            'OR');
            
            $where['_logic'] = 'OR';
            
        }
        
        $result=$model
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $res['count']=$model
        ->where($where)
        ->order('add_time desc')
        ->count()+0;
        
        
        
        
        // =========判断=========
        if($result){
            
            $model=M('user_super');
            
            
            foreach ($result as $key => $value) {
                
                $user_id=$value['user_id'];
                $super_id=null;
                $where=[];
                $where['t1.user_id']=$user_id;
                $user_super=$model
                ->table('c_user_super as t1,c_user as t2,c_star as t3')
                ->where($where)
                ->where('t1.super_id = t2.user_id AND t2.star_id = t3.star_id')
                ->find();
                
                if($user_super){
                    
                    $result[$key]['super_name']=$user_super['user_name'];
                    $result[$key]['super_id']=$user_super['super_id'];
                    $result[$key]['super_star_id']=$user_super['star_id'];
                    $result[$key]['super_star_name']=$user_super['star_name'];
                    // dump($result[$key]);
                    
                }
                
            }
            
            
            $result=toTime($result);
            
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    
    public function add(){
        $add=I('add');
        if(!$add){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        //先看看id有没有重复
        
        $model=M('user');
        $user_id=$add['user_id'];
        
        $where['user_id']=$user_id;
        $isUser=$model->where($where)->find();
        
        
        if(!$isUser){
            //没有
            $add['add_time']=time();
            $add['edit_time']=time();
            $result=$model->add($add);
            if($result){
                //添加成功
                $res['res']=1;
            }else{
                //添加失败
                $res['res']=-1;
                $res['msg']=$result;
            }
            
            
        }else{
            //有了这个用户
            $res['res']=-3;
        }
        echo json_encode($res);
        
    }
    
    
    public function test(){
        echo "<h3>CTOS用户生成器</h3>";
        
        $model=M('user');
        $start=$model->count()+1;
        $max=$start+10;
        $adds=[];
        
        for ($i=$start; $i < $max; $i++) {
            
            $add=[];
            $add['user_id']="1213$i";
            $add['user_pwd']=md5('123'.__KEY__);
            
            $add['user_name']="用户$i";
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['user_head']='https://avatars1.githubusercontent.com/u/20777182';
            
            $add['user_type']=0;
            $add['star_id']=null;
            
            if($i%3==0){
                //分销商
                $add['user_type']=1;
                $add['star_id']="96e64f4cf57c3b88899cc63ad6d7cdb4";
                
            }
            if($i%2==0){
                //推广员
                $add['star_id']=null;
                $add['user_type']=2;
            }
            
            $adds[]=$add;
        }
        
        // $model->addAll($adds);
        dump($adds);
    }
    
    public function save(){
        
        $model=M('user');
        $where=I('where');
        $save=I('save');
        if(!$where || !$save){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        
        $save['edit_time']=time();
        
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
            
            $result=$model->where($where)->save($save);
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    
    
}