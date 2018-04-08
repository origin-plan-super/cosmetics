<?php
namespace Home\Model;
use Think\Model;
class OrderModel extends Model {
    
    
    public function _initialize (){
    }
    
    
    public function create($data){
        
        /**
        * 创建单个商品的订单数据
        * 记录每个订单的收货地址
        */
        //本次订单详情的id,多个订单对应一个订单详情，订单详情用于支付
        $order_info_id=date('YmdHis',time()).rand(10000,99999);
        
        $skus=$data['skus'];
        $payment_type=$data['payment_type'];
        $address_id=$data['address_id'];
        $bags=$data['bags'];
        
        //=============================================================
        $Sku=M('Sku');
        $Address=M('Address');
        $Goods=D('Goods');
        $Bag=D('Bag');
        
        $OrderInfo=M('OrderInfo');
        $OrderAddress=M('OrderAddress');
        $OrderSnapshot=M('OrderSnapshot');
        $Order=M('Order');
        
        //测试环境
        // $OrderAddress->where('1=1')->delete();
        // $OrderSnapshot->where('1=1')->delete();
        // $Order->where('1=1')->delete();
        // $OrderInfo->where('1=1')->delete();
        
        //=============================================================
        
        //取出收货地址数据
        $where=[];
        $where['address_id']=$address_id;
        $address=$Address->where($where)->find();
        $address_id=getMd5('address');
        $address['address_id']=$address_id;
        $address['add_time']=time();
        $address['edit_time']=time();
        
        //=============================================================
        //先找到所有商品
        $where=[];
        $where['bag_id']=['in',$bags];
        $bags=$Bag->getList($where);
        
        //=============================================================
        //遍历生成订单
        $snapshotList=[];
        $orderList=[];
        $orderIds=[];
        $total=0;
        for ($i=0; $i < count($bags); $i++) {
            
            $order_id=date('YmdHis',time()).rand(10000,99999);
            $orderIds[]=$order_id;
            $snapshot_id=getMd5('snapshot');
            
            //=============================================================
            //生成快照
            $goods=$Goods->get($bags[$i]['goods_id']);
            $snapshot=[];
            $snapshot['snapshot_id']=$snapshot_id;
            $snapshot['order_id']=$order_id;
            $snapshot['goods_id']=$goods['goods_id'];
            
            $snapshot['goods_title']=$goods['goods_title'];
            $snapshot['img']=count($goods['img_list'])>0?$goods['img_list'][0]['src']:'';
            
            $snapshot['s1']=$bags[$i]['sku']['s1'];
            $snapshot['s2']=$bags[$i]['sku']['s2'];
            $snapshot['s3']=$bags[$i]['sku']['s3'];
            $snapshot['price']=$bags[$i]['sku']['price'];
            $snapshot['num']=$bags[$i]['goods_count'];
            
            $snapshot['add_time']=time();
            $snapshot['edit_time']=time();
            
            $snapshotList[]=$snapshot;
            
            //=============================================================
            //一个商品一个订单
            $order=[];
            
            $order['order_id']=$order_id;//订单号
            $order['snapshot_id']=$snapshot_id;//商品快照号
            $order['order_info_id']=$order_info_id;//订单详情号，支付使用
            $order['user_id']=session('user_id');//买家id
            $order['address_id']=$address_id;//收货地址id
            $order['pay_type']=$payment_type;//支付方式
            $order['total']= $snapshot['price']* $snapshot['num'];//总价、单价*数量
            $total+=$order['total'];//计算订单详情的总价
            $order['logistics_type']=0;//发货方式
            $order['express_number']='';//物流号
            $order['is_postage']=0;//是否有邮费
            $order['postage']=0;//邮费
            $order['state']=1;//状态
            $order['order_type']=1;//订单类型（499、限时购、分享）
            
            $order['add_time']=time();
            $order['edit_time']=time();
            //=============================================================
            $orderList[]=$order;
            
        }
        //=============================================================
        
        //创建订单详情
        $orderInfo=[];
        $orderInfo['order_info_id']=$order_info_id;
        $orderInfo['total']= $total;//订单总价
        $orderInfo['state']= 1;//订单支付状态
        $orderInfo['user_id']= session('user_id');
        $orderInfo['add_time']=time();
        $orderInfo['edit_time']=time();
        
        //=============================================================
        
        $OrderAddress->add($address);//添加收货地址信息
        $OrderSnapshot->addAll($snapshotList);//添加交易快照信息
        $Order->addAll($orderList);//添加订单
        $OrderInfo->add($orderInfo);//添加订单详情
        
        return $order_info_id;  // 返回 order_info_id
        
    }
    
    //组成支付数据
    public function pay($data){
        //支付
        $OrderInfo=M('OrderInfo');
        $Order=M('Order');
        $Coupon=D('Coupon');
        
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
        $OrderInfo=M('OrderInfo');
        
        //==================================================
        
        $where=[];
        $where['user_id']=session('user_id');
        $orderInfos=$OrderInfo->where($where)->select();
        
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
            $orders=$Order->where($where)->select();//查找订单详情对应的所有订单
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