<?php
namespace Home\Model;
use Think\Model;
class OrderModel extends Model {
    
    
    public function _initialize (){}
    
    //创建订单数据
    public function createOrder($snapshot,$address_id,$pay_id){
        $Goods=D('Goods');//商品模型
        $Snapshot=M('Snapshot');//快照模型
        $Logistics=M('Logistics');//物流信息表模型
        $Activity=D('Activity');//活动模型
        $Coupon=D('Coupon');//优惠券模型
        
        // ===================================================================================
        // 基本数据
        $user_id=session('user_id');
        $data=[];
        $data['orderData']=null;
        $data['logisticsData']=null;
        
        // ===================================================================================
        // 基本数据
        $snapshot_id=$snapshot['snapshot_id'];//快照id
        $goods_id=$snapshot['goods_id'];//商品id
        $order_id=date('YmdHis',time()).rand(10000,99999);//创建订单号
        $activity_id=$snapshot['activity_id'];//促销活动的id
        $coupon_id=$snapshot['coupon_id'];//优惠券id
        
        // ===================================================================================
        // 找商品信息
        $where=[];
        $where['goods_id']=$goods_id;
        $goods=$Goods->where($where)->find();
        
        // ===================================================================================
        // 创建物流表
        $logistics=[];
        $logistics_id=getMd5('logistics');//物流信息id
        $logistics['logistics_id']=$logistics_id;//物流信息id
        $logistics['order_id']=$order_id;//订单号
        $logistics['logistics_number']='';//物流号
        $logistics['type']='';//物流类型，圆通、中通等
        $logistics['add_time']=time();//添加时间
        $logistics['edit_time']=time();//编辑时间
        $data['logisticsData']=$logistics;
        // ===================================================================================
        // 这里进入计价环节
        $discount=0;//优惠的价格
        
        // ===================================================================================
        //找促销活动信息
        $discount+=$Activity->getActivityPrice($activity_id,$snapshot_id,$order_id);
        
        // ===================================================================================
        //找优惠券信息，如果优惠券可用，在本次使用后优惠券失效，此次订单只可以使用一次。
        $discount+=$Coupon->getCouponPrice($coupon_id,$snapshot_id,$order_id);
        
        // ===================================================================================
        // 计算价格
        $price=$snapshot['price']*$snapshot['count']-$discount;
        $total+=$price;
        // ===================================================================================
        // 组装订单数据
        $orderData=[];//订单数据
        $orderData['order_id']=$order_id;//订单号
        $orderData['snapshot_id']=$snapshot_id;//快照id
        $orderData['user_id']=$user_id;//买家id
        $orderData['address_id']=$address_id;//地址库id
        $orderData['price']=$price;//应付金额（计算商品总价且优惠后的价格）
        $orderData['state']=1;//状态，默认是1
        $orderData['pay_id']=$pay_id;//支付号
        $orderData['supplier_id']=$goods['supplier_id'];//供货商id，取此时商品设置的数据，留空表示平台发货
        $orderData['add_time']=time();//添加时间
        $orderData['edit_time']=time();//编辑时间
        $data['orderData']=$orderData;
        // ===================================================================================
        // 设置快照的 order_id
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $save=[];
        $save['order_id']=$order_id;
        $Snapshot->where($where)->save($save);
        
        return $data;
    }
    
    
    
    
    public function create($data){
        
        // $order_info_id=date('YmdHis',time()).rand(10000,99999);
        // ===================================================================================
        // 创建支付号，全局使用
        $pay_id=date('YmdHis',time()).rand(10000,99999);
        
        // ===================================================================================
        // 用户数据
        $user_id=session('user_id');
        
        // ===================================================================================
        // 实际需要支付的金额
        $total=0;
        // ===================================================================================
        // 获得基本数据
        $pay_typ=$data['pay_typ'];//支付方式
        $address_id=$data['address_id'];//地址id
        $snapshot_ids=$data['snapshot_id'];//快照id数组
        
        // ===================================================================================
        // 创建模型
        $Sku=M('Sku');//sku模型
        $Address=D('Address');//用户地址库模型
        $Bag=D('Bag');//购物袋模型
        $Activity=D('Activity');//活动模型
        $Coupon=D('Coupon');//优惠券模型
        $Pay=D('Pay');//支付单模型
        $OrderAddress=M('OrderAddress');//地址库模型
        $Snapshot=M('Snapshot');//快照模型
        $Order=M('Order');//订单模型
        $Logistics=M('Logistics');//物流信息表模型
        
        // 测试环境
        $OrderAddress->where('1=1')->delete();
        $save=[];
        $save['order_id']=null;
        $Snapshot->where('1=1')->save($save);
        $Order->where('1=1')->delete();
        $Logistics->where('1=1')->delete();
        $Pay->where('1=1')->delete();
        
        
        // ===================================================================================
        // 找到所有的快照数据
        $where=[];
        $where['snapshot_id']=['in',$snapshot_ids];
        $snapshots=$Snapshot->where($where)->select();
        
        // ===================================================================================
        // 找地址信息
        $where=[];
        $where['user_id']=$user_id;
        $where['address_id']=$address_id;
        $address=$Address->where($where)->find();
        
        //创建新数据
        $address_id=getMd5('address');
        $address['address_id']=$address_id;
        $address['add_time']=time();
        $address['edit_time']=time();
        
        // ===================================================================================
        // 循环遍历快照，然后创建订单
        $orderDatas=[];
        $logisticsDatas=[];
        
        foreach ($snapshots as $key => $snapshot) {
            $data=$this->createOrder($snapshot,$address_id,$pay_id);
            $orderDatas[]=$data['orderData'];
            $logisticsDatas[]=$data['logisticsData'];
        }
        
        // ===================================================================================
        // 创建支付单
        $payData=[];
        $payData['pay_id']=$pay_id;// 支付号
        $payData['user_id']=$user_id;// 买家id
        $payData['price']=$total;// 需要支付的金额，已经优惠后的价格，实际需要支付的价格
        $payData['state']=0;//支付状态,0：未支付，1：已支付
        $payData['pay_type']=$pay_typ;//支付类型，1：支付宝支付，2：微信支付，3：余额支付
        $payData['add_time']=time();
        $payData['edit_time']=time();
        
        
        // ===================================================================================
        // 写入到数据库中
        $Order->addAll($orderDatas);//添加订单数据
        $OrderAddress->add($address);//添加收货地址信息
        $Pay->add($payData);//添加支付单数据
        $Logistics->addAll($logisticsDatas);//添加物流信息表
        
        ec('订单数据');
        dump($orderDatas);
        ec('地址数据');
        dump($address);
        ec('支付数据');
        dump($payData);
        ec('物流数据');
        dump($logisticsDatas);
        die;
        
        
        return $pay_id;  // 返回 pay_id
        
    }
    
    //组成支付数据
    public function pay($data){
        //支付
        $OrderInfo=M('OrderInfo');
        $Order=M('Order');
        $Coupon=D('Coupon');
        $Goods=D('Goods');
        $User=D('User');
        
        //=============================================================
        
        $coupon_id=$data['coupon_id'];//优惠码
        $order_info_id=$data['order_info_id'];//优惠码
        // $orderIds=$data['orderIds'];//订单列表
        // $order_info_id=date('YmdHis',time()).rand(10000,99999);//生成订单详情号，支付号、支付流水号
        
        //=============================================================
        
        if($coupon_id){
            //找优惠券
            $where=[];
            $where['user_id']=session();
            $where['coupon_id']=$coupon_id;
            $coupon = $Coupon->where($where)->find();
            //需要验证过期没有，或者是否已经使用
            //然后设置已使用状态
        }
        
        //=============================================================
        //找订单详情
        $where=[];
        $where['order_info_id']=$order_info_id;
        $orderInfo=$OrderInfo->where($where)->find();
        $total=$orderInfo['total'];
        
        //=============================================================
        //找商品快照，如果商品是特殊商品，让此用户成为会员
        $OrderSnapshot=M('OrderSnapshot');
        $where=[];
        $where['order_info_id']=$order_info_id;
        $snapshots=$OrderSnapshot->where($where)->select();
        
        
        
        foreach ($snapshots as $key => $value) {
            
            $goods_id=$value['goods_id'];
            $where=[];
            $where['goods_id']=$goods_id;
            $goods=$Goods->where()->find();
            if($goods['is_unique']==1){
                //是特殊商品
                //让用户成为vip
                $where=[];
                $where['user_id']=session('user_id');
                $save=[];
                
                $User->where($where)->save($save);
                //=============================================================
                //vip得钱
                Vendor('VIP.VIP');
                //初始化vip对象
                $conf=[];
                $conf['userId']=session('user_id');
                $conf['isDebug']=false;
                $vip=new \VIP($conf);
                $vip->setWriteDatabase(true);
                $vip->getSuper()->邀请人得钱奖($vip);
                
            }
            
        }
        
        
        //=============================================================
        
        Vendor('VIP.VIP');
        //初始化vip对象
        $conf=[];
        $conf['userId']=session('user_id');
        $vip=new \VIP($conf);
        $vip->setWriteDatabase(false);
        
        //=============================================================
        //保存订单信息数据
        $save=[];
        //保存支付成功状态
        $save['discount']=(float)$coupon['value'];//优惠券减免的钱
        $save['vip_discount']=$vip->getDiscount($total-$orderInfo['discount']);//vip减免的钱
        $save['paid']=$orderInfo['total']-$orderInfo['discount']-$orderInfo['vip_discount'];//实际需要支付的价格
        
        $OrderInfo->where($where)->save($save);
        return $orderInfo;
    }
    
    //余额支付
    public function balancePayment($order_info_id){
        
        $User=M('User');
        $where=[];
        $where['user_id']=session('user_id');
        $userInfo=$User->where($where)->find();
        $user_money=$userInfo['user_money'];
        
        Vendor('VIP.VIP');
        //初始化vip对象
        $conf=[];
        $conf['userId']=session('user_id');
        $vip=new \VIP($conf);
        $vip->setWriteDatabase(true);
        
        //取出数据订单支付数据
        $OrderInfo=M('OrderInfo');
        $where=[];
        $where['order_info_id']=$order_info_id;
        $orderInfo=$OrderInfo->where($where)->find();
        $paid=$orderInfo['paid'];
        $discount=$orderInfo['discount'];
        $total=$orderInfo['total'];
        
        //自购省钱，当此用户购买了一个商品并且支付成功后，调用此函数，调用后上级得到回扣。
        //传入的总价应该为仅减去了优惠券的钱
        $is=$vip->discountRebate($total-$discount);
        
        //让自己减去钱
        $user_money-=$paid;
        //保存钱
        $save=[];
        $save['user_money']=$user_money;
        $where=[];
        $where['user_id']=session('user_id');
        $userInfo=$User->where($where)->save($save);
        
        //设置订单状态
        // 1、待付款
        // 2、待发货
        // 3、待收货
        // 4、交易成功
        // 5、退款/退货
        $save=[];
        $save['state']=2;
        $where=[];
        $where['user_id']=session('user_id');
        $where['order_info_id']=$order_info_id;
        $this->where($where)->save($save);
        
        //设置订单详情状态
        // 订单状态，支付后更新
        // 0：未支付
        // 1：已支付
        $save=[];
        $save['state']=2;
        $where=[];
        $where['user_id']=session('user_id');
        $where['order_info_id']=$order_info_id;
        $OrderInfo->where($where)->save($save);
        return $orderInfo;
        
    }
    
    public function getList(){
        
        
        $OrderAddress=M('OrderAddress');
        $OrderSnapshot=M('OrderSnapshot');
        $Order=M('Order');
        return [];
        
        //==================================================
        
        $where=[];
        $where['user_id']=session('user_id');
        $orderInfos=$OrderInfo->order('add_time desc')->where($where)->select();
        
        $list=[];
        for ($i=0; $i < count($orderInfos); $i++) {
            
            $item=[];
            $order_info_id=$orderInfos[$i]['order_info_id'];//订单详情id
            
            //未付款状态的逻辑
            $item['state']=$orderInfos[$i]['state'];//订单支付状态
            $item['total']=$orderInfos[$i]['total'];//订单总价
            $item['order_info_id']=$order_info_id;//订单总价
            $where=[];
            $where['order_info_id']=$order_info_id;
            $orders=$Order->where($where)->order('add_time desc')->select();//查找订单详情对应的所有订单
            //组成每个单品
            $goods_list=[];
            //如果是待付款，就要集合到一起，如果是已付款，就单独分开
            for ($j=0; $j < count($orders); $j++) {
                $goods=[];
                $order_id=$orders[$j]['order_id'];//订单id
                $where=[];
                $where['order_id']=$order_id;
                $goodsSnapshot= $OrderSnapshot->where($where)->find();//找到和这个订单对应的商品快照，一个订单对应一个商品
                $goods['img']=$goodsSnapshot['img'];//一张图
                $goods['title']=$goodsSnapshot['goods_title'];//商品标题
                $goods['info']=$goodsSnapshot['s1'].'-'.$goodsSnapshot['s2'].'-'.$goodsSnapshot['s3'];//商品信息
                $goods['price']=$goodsSnapshot['price'];//商品单价
                $goods['num']=$goodsSnapshot['num']+0;//商品数量
                $goods_list[]=$goods;
            }
            $item['goodsList']=$goods_list;
            $list[]=$item;
            
            
        }
        return $list;
        
    }
    
    //通过订单详情id获取单个订单信息
    public function get($order_info_id){
        $OrderAddress=M('OrderAddress');//收货地址信息
        $OrderSnapshot=M('OrderSnapshot');//商品快照
        $Order=M('Order');//订单
        $OrderInfo=M('OrderInfo');//订单详情
        
        $where=[];
        $where['user_id']=session('user_id');
        $where['order_info_id']=$order_info_id;
        $_orderInfo=$OrderInfo->where($where)->find();
        //组成商品列表数据
        
        $orders=$Order->where($where)->select();//查找订单详情对应的所有订单
        
        $goods_list=[];
        //如果是待付款，就要集合到一起，如果是已付款，就单独分开
        for ($j=0; $j < count($orders); $j++) {
            $goods=[];
            $order_id=$orders[$j]['order_id'];//订单id
            $where=[];
            $where['order_id']=$order_id;
            $goodsSnapshot= $OrderSnapshot->where($where)->find();//找到和这个订单对应的商品快照，一个订单对应一个商品
            $goods['img']=$goodsSnapshot['img'];//一张图
            $goods['title']=$goodsSnapshot['goods_title'];//商品标题
            $goods['info']=$goodsSnapshot['s1'].'-'.$goodsSnapshot['s2'].'-'.$goodsSnapshot['s3'];//商品信息
            $goods['price']=$goodsSnapshot['price'];//商品单价
            $goods['num']=$goodsSnapshot['num']+0;//商品数量
            $goods_list[]=$goods;
        }
        
        
        $orderInfo=[];
        $orderInfo['order_info_id']=$_orderInfo['order_info_id'];
        $orderInfo['state']=$_orderInfo['state'];
        $orderInfo['total']=$_orderInfo['total'];
        
        $orderInfo['goodsList']=$goods_list;
        
        //根据订单详情取得订单数据
        // dump($orderInfo);
        return $orderInfo;
    }
    
}