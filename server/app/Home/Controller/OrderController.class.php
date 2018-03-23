<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:17:34
* 最新修改时间：2018年3月2日11:17:34
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####订单管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class OrderController extends CommonController{
    
    
    public function test(){
        
        Vendor('VIP.VIP');
        
        //初始化vip对象
        $conf=[];
        $conf['userId']='12138';
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(false);
        
        //==================省钱回扣功能展示============================
        ec("==================省钱回扣功能展示============================");
        //订单的原本总价`
        $orderMoney=100;
        //自购省钱，当此用户购买了一个商品并且支付成功后，调用此函数，调用后上级得到回扣。
        $vip->discountRebate($orderMoney);
        
        
        // //==================下级列表功能展示============================
        // ec("==================下级列表功能展示============================");
        // //初始化下级列表
        // $vip->initSubList();
        // //获得下级列表
        // $subList=$vip->getSubList();
        // // dump($subList);
        
        //==================销售奖功能展示============================
        ec("==================销售奖功能展示============================");
        
        //订单的原本总价
        $orderMoney=100;
        // 什么时候调用？
        // 当分享出去的商品被购买后，先取得分享人的 vip 对象，然后取得分享人 vip 对象的 super，
        // 然后调用这个 super 的 salesAward() 函数。
        
        //现在，假设分享人的id为 12132，那么调用当前对象的 super的指定函数
        $vip->getSuper()->salesAward($orderMoney);
        
        
        //==================自己发展团队功能展示============================
        // ec("==================自己发展团队功能展示============================");
        
        // //当 12132 这个用户成功支付后，创建这个用户的 vip 对象，然后取得当前用户的 super ，然后调用 super 的 邀请人得钱奖()
        
        // $conf=[];
        // $conf['userId']='12136';
        // $vip=new \VIP($conf);
        // $vip->setDebug(true);
        // $vip->setWriteDatabase(false);
        // $vip->getSuper()->邀请人得钱奖($vip);
        
        for ($i=0; $i < 50; $i++) {
            ec ('');
        }
        
        
    }
    
    //获得列表
    public function getList(){
        
        
        $model=M('order');
        $where=[];
        $where['user_id']=session('user_id');
        $order=$model->where($where)->select();
        
        $order= toTime($order);
        
        $orderList=[];
        $model=M('order_info');
        $goods=M('goods');
        
        for ($i=0; $i <count($order) ; $i++) {
            
            $order_id=$order[$i]['order_id'];
            $where=[];
            $where['order_id']=$order_id;
            $order_info=$model->where($where)->find();
            
            $item=[];
            $item=$order[$i];
            $item['order_info']=json_decode($order_info['order_info'],true);
            $item['order_info']['goods']=[];
            
            //找到商品
            foreach ($item['order_info']['goods_id_list'] as $key => $value) {
                
                $where=[];
                $where['goods_id']=$value;
                $re_goods=$goods->where($where)->find();
                
                $map=[];
                $map['img_list']=false;
                $map['goods_class']=false;
                $map['spec']=false;
                $re_goods=arrJsonD([$re_goods],$map);
                
                $item['order_info']['goods'][]=$re_goods[0];
                
                
            }
            
            $orderList[]=$item;
        }
        
        //=========判断=========
        if($orderList){
            $res['res']=count($orderList);
            $res['msg']=$orderList;
        }else{
            $res['res']=-1;
            $res['msg']=$orderList;
        }
        //=========判断end=========
        
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
        
        
    }
    public function get(){
        
        $model=M('order');
        $order_id=I('order_id');
        if(!$order_id){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $where=[];
        $where['t1.user_id']='12138';
        $where['t1.order_id']=$order_id;
        
        $order=$model
        ->table('c_order as t1,c_order_info as t2')
        ->where($where)
        ->where('t1.order_id = t2.order_id')
        ->find();
        
        $order['order_info']=json_decode($order['order_info'],true);
        $order['order_info']['goods']=[];
        
        $goods=M('goods');
        //找到商品
        foreach ($order['order_info']['goods_id_list'] as $key => $value) {
            
            $where=[];
            $where['goods_id']=$value;
            $re_goods=$goods->where($where)->find();
            
            $map=[];
            $map['img_list']=false;
            $map['goods_class']=false;
            $map['spec']=false;
            $re_goods=arrJsonD([$re_goods],$map);
            
            $order['order_info']['goods'][]=$re_goods[0];
            
        }
        
        
        if($order){
            $res['res']=1;
            $res['msg']=$order;
        }else{
            $res['res']=-1;
            $res['msg']=$order;
        }
        
        echo json_encode($res);
        
    }
    
    
    
    //保存字段
    public function save(){
        
    }
    
    public function add(){
        
        $post=I('post.','',false);
        $address_id=$post['address_id'];
        $payment_type=$post['payment_type'];
        
        
        
        
        //购物车id是个数组
        $bag_id=$post['bag_id'];
        // $bag_id=['84e41c1ff16193ab97c951533f2ecc58','999372773355bedcaedcb80af74ab5b7'];
        
        if(!$bag_id){
            $res['res']=-3;
            echo json_encode($res);
            die;
        }
        
        //取出购物车中的东西
        $model=M('bag');
        $where['bag_id']=array('in',$bag_id);
        
        //购物车、商品联表查询
        $result= $model
        ->where($where)
        ->field('t1.*,t2.*,t1.spec as user_spec,t2.spec as goods_spce')
        ->table('c_bag as t1,c_goods as t2')
        ->where('t1.goods_id = t2.goods_id')
        ->select();
        
        $map=[];
        $map['user_spec']=true;
        $result=arrJsonD($result,$map);
        
        //商品id的列表
        $goods_id_list=[];
        //用户选择的列表
        $user_spec=[];
        //循环购物车信息，并且组成数据
        for ($i=0; $i <count($result) ; $i++) {
            //商品的id
            $goods_id=$result[$i]['goods_id'];
            //将商品的id追加到商品id列表
            $goods_id_list[]=  $goods_id;
            //将用户选择的数据追加到用户选择列表中
            
            $_user_spec=[];
            $_user_spec=$result[$i]['user_spec'];
            $_user_spec['goods_count']=$result[$i]['goods_count']+0;
            
            $user_spec[$goods_id]=$_user_spec;
            
            // $user_spec[$goods_id]['goods_count']=$result[$i]['goods_count'];
            
            
            // 这个判断待定
            // if($result[$i]['user_spec']){
            //     $sp=[];
            //     $sp['颜色']='红色';
            //     $sp['材质']='铁';
            //     $sp['大小']='10*10';
            //     $sp['money']=1.1;
            //     $sp['stock']=1.1;
            //     $user_spec[$goods_id]=$sp;
            // }else{
            // }
            
        }
        
        //此订单的价格
        $order_money=0;
        //计价
        for ($i=0; $i <count($result) ; $i++) {
            
            //取得商品数量
            $count=$result[$i]['goods_count'];
            
            //记录单价，让单价等于用户选择的东西的单价
            
            $money=$result[$i]['user_spec']['money'];
            
            //计算钱 公式： 总价=数量*单价
            $order_money+=($count*$money);
            
            //这个判断待定
            // if(empty($result[$i]['goods_count']['user_spec'])){
            //     //没有
            //     // $money=1.1;
            // }else{
            //     //有spce
            // }
            
        }
        
        //生成订单号
        $order_id=date('YmdHis',time()).rand(1000,9999);
        
        //组装数据
        $add=[];//add数组
        $add['order_id']=$order_id;//订单号
        $add['add_time']=time();//订单创建时间
        $add['edit_time']=time();//订单最后一次编辑时间
        $add['user_id']='12138';//提交订单的用户id
        $add['money']=$order_money;//此订单的总价
        $add['payment_type']=$payment_type;//支付方式
        
        
        //订单模型
        $model=M('order');
        //添加进去
        $result=$model->add($add);
        // $result=true;
        //如果添加成功
        if($result){
            //添加成功
            //开始添加订单信息,订单信息模型
            
            
            //订单信息数组
            $order_info=[];
            $order_info['goods_id_list']=$goods_id_list;//订单的商品的id数组
            $order_info['user_spec']=$user_spec;//订单的用户选择的规格
            
            // 获得收货地址
            $Address=M('address');
            $where=[];
            $where['user_id']=session('user_id');
            $where['address_id']=$address_id;
            $address=$Address->where($where)->find();
            $order_info['address']=$address;//收货地址
            
            
            $add=[];//add数组
            $add['order_id']=$order_id;//订单号
            $add['order_info']=json_encode($order_info);//将订单信息转换为字符串
            
            
            
            $model=M('order_info');
            $result=$model->add($add);//添加到订单信息表中
            
            //如果订单信息添加成功
            if($result){
                //输出1
                $res['res']=1;
                $res['msg']=$order_id;
            }else{
                //如果订单信息表添加失败，输出-1
                $res['res']=-1;
                $res['msg']='订单信息创建失败';
                $res['order_id']=$order_id;
            }
            // 返回接口
            echo json_encode($res);
            die;
        }else{
            //订单创建失败
            $res['res']=-2;
            $res['msg']='订单创建失败';
            // 返回接口
            echo json_encode($res);
            
        }
        
        
    }
    
    
}