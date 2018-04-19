// @ts-nocheck
import Vue from 'vue';
import Router from 'vue-router';
// const Home = () => import('@/pages/Home/Home.vue');


//路由文件配置
import index from '@/pages/index/index.vue';
import login from '@/pages/login/login.vue';
import ctos from '@/pages/ctos/ctos.vue';


// 商品
import goods from '@/pages/goods/goods.vue';
import goods_edit from '@/pages/goods/edit/edit.vue';
import goods_list from '@/pages/goods/list/list.vue';

// 分类
import _class from '@/pages/class/class.vue';

//订单管理
import order from '@/pages/order/order.vue';
import order_list from '@/pages/order/list/list.vue';
import order_info from '@/pages/order/info/info.vue';

// 管理员账户管理

import admin from '@/pages/admin/admin.vue';
import admin_list from '@/pages/admin/list/list.vue';
import admin_info from '@/pages/admin/info/info.vue';


//店铺装修

import renovation from '@/pages/renovation/renovation.vue';
import renovation_carousel from '@/pages/renovation/carousel/carousel.vue';
import renovation_nav from '@/pages/renovation/nav/nav.vue';//导航管理
import renovation_special from '@/pages/renovation/special/special.vue';//专题管理
import specialEdit from '@/pages/renovation/special/specialEdit.vue';//专题编辑管理


//反馈管理

import feedback from '@/pages/feedback/feedback.vue';
import feedback_list from '@/pages/feedback/list/list.vue';
import feedback_info from '@/pages/feedback/info/info.vue';



// 分销管理
// import fork from '@/pages/fork/fork.vue';
// import fork_customer from '@/pages/fork/customer/customer.vue';
// import fork_starGoodsList from '@/pages/fork/starGoodsList/starGoodsList.vue';
// import fork_seller from '@/pages/fork/seller/seller.vue';



// 用户账户管理
import user from '@/pages/user/user.vue';
import user_list from '@/pages/user/list/list.vue';


//消息列表
import msg from '@/pages/msg/msg.vue';
import msg_list from '@/pages/msg/list/list.vue';
import msg_add from '@/pages/msg/add/add.vue';
import msg_edit from '@/pages/msg/edit/edit.vue';

//文章
import paper from '@/pages/paper/paper.vue';
import paper_list from '@/pages/paper/list/list.vue';
import paper_add from '@/pages/paper/add/add.vue';


//优惠券

import coupon from '@/pages/coupon/coupon.vue';
import coupon_list from '@/pages/coupon/list/list.vue';
import coupon_add from '@/pages/coupon/add/add.vue';
import coupon_edit from '@/pages/coupon/edit/edit.vue';

// 帮助
import help from '@/pages/help/help.vue';
import help_list from '@/pages/help/list/list.vue';
import help_add from '@/pages/help/add/add.vue';
import help_edit from '@/pages/help/edit/edit.vue';

import vip from '@/pages/vip/vip.vue';

import timeLimit from '@/pages/timeLimit/timeLimit.vue';



//供货商管理
import supplier from '@/pages/supplier/supplier.vue';

import supplier_add from '@/pages/supplier/add/add.vue';
import supplier_edit from '@/pages/supplier/edit/edit.vue';
import supplier_examine from '@/pages/supplier/examine/examine.vue';
import supplier_goodsList from '@/pages/supplier/goodsList/goodsList.vue';
import supplier_info from '@/pages/supplier/info/info.vue';
import supplier_list from '@/pages/supplier/list/list.vue';
import supplier_orderList from '@/pages/supplier/orderList/orderList.vue';




//运费模板

import freight from '@/pages/supplier/supplier.vue';

import freight_add from '@/pages/freight/add/add.vue';
import freight_edit from '@/pages/freight/edit/edit.vue';
import freight_list from '@/pages/freight/list/list.vue';


Vue.use(Router);

export default new Router({
  routes: [
    { path: '/', component: index, meta: { title: '随享季商城后台管理系统' }, },
    { path: '/index', component: index, meta: { title: '首页' }, },
    { path: '/login', component: login, meta: { title: '登录' }, },
    {
      path: '/goods', component: goods, children: [
        { path: 'edit', name: "/goods/edit", component: goods_edit, meta: { title: '编辑商品' }, },
        { path: 'list', component: goods_list, meta: { title: '商品列表' }, },
      ]
    },
    {
      path: '/order', component: order, children: [
        { path: 'list', name: "/order/list", component: order_list, meta: { title: '订单列表' }, },
        { path: 'info', name: "/order/info", component: order_info, meta: { title: '订单详情' }, },
      ]
    },
    {
      path: '/admin', component: admin, children: [
        { path: 'list', name: "/admin/list", component: admin_list, meta: { title: '管理账户列表' }, },
        { path: 'info', name: "/admin/info", component: admin_info, meta: { title: '账户详情' }, },
      ]
    },
    {
      path: '/renovation', component: renovation, children: [
        { path: 'carousel', name: "/renovation/carousel", component: renovation_carousel, meta: { title: '首页轮播管理' }, },
        { path: 'nav', name: "/renovation/nav", component: renovation_nav, meta: { title: '首页导航管理' }, },
        { path: 'special', name: "/renovation/special", component: renovation_special, meta: { title: '专题管理' }, },
        { path: 'specialEdit', name: "/renovation/specialEdit", component: specialEdit, meta: { title: '专题编辑' }, },
      ]

    },
    {
      path: '/feedback', component: feedback, children: [
        { path: 'list', name: "/feedback/list", component: feedback_list, meta: { title: '反馈管理' }, },
        { path: 'info', name: "/feedback/info", component: feedback_info, meta: { title: '反馈详情' }, },
      ]
    },
    // //分销管理
    // {
    //   path: '/fork', component: fork, children: [
    //     { path: 'customer', name: "/fork/customer", component: fork_customer, meta: { title: '客户管理' }, },
    //     { path: 'seller', name: "/fork/seller", component: fork_seller, meta: { title: '分销商管理' }, },
    //     { path: 'star_goods_list', name: "/fork/starGoodsList", component: fork_starGoodsList, meta: { title: '星级商品管理' }, },
    //   ]
    // },
    //用户管理
    {
      path: '/user', component: user, children: [
        { path: 'list', name: "/user/list", component: user_list, meta: { title: '用户列表' }, },
      ]
    },
    {
      path: '/msg', component: msg, children: [
        { path: 'list', name: "/msg/list", component: msg_list, meta: { title: '消息列表' }, },
        { path: 'add', name: "/msg/add", component: msg_add, meta: { title: '发布消息' }, },
        { path: 'edit', name: "/msg/edit", component: msg_edit, meta: { title: '发布消息' }, },
      ]
    },

    {
      path: '/paper', component: paper, children: [
        { path: 'list', name: "/paper/list", component: paper_list, meta: { title: '文章列表' }, },
        { path: 'add', name: "/paper/add", component: paper_add, meta: { title: '发布文章' }, },
      ]
    },
    {
      path: '/coupon', component: coupon, children: [
        { path: 'list', name: "/coupon/list", component: coupon_list, meta: { title: '优惠券列表' }, },
        { path: 'add', name: "/coupon/add", component: coupon_add, meta: { title: '添加优惠券' }, },
        { path: 'edit', name: "/coupon/edit", component: coupon_edit, meta: { title: '编辑优惠券' }, },
      ]
    },
    {
      path: '/help', component: help, children: [
        { path: 'list', name: "/help/list", component: help_list, meta: { title: '帮助列表' }, },
        { path: 'add', name: "/help/add", component: help_add, meta: { title: '添加帮助' }, },
        { path: 'edit', name: "/help/edit", component: help_edit, meta: { title: '编辑帮助' }, },
      ]
    },
    // 供货商 管理
    {
      path: '/supplier', component: supplier, children: [
        { path: 'add', name: "/supplier/add", component: supplier_add, meta: { title: '新增供货商' }, },
        { path: 'edit', name: "/supplier/edit", component: supplier_edit, meta: { title: '编辑供货商' }, },
        { path: 'examine', name: "/supplier/examine", component: supplier_examine, meta: { title: '供货商审核' }, },
        { path: 'goodsList', name: "/supplier/goodsList", component: supplier_goodsList, meta: { title: '供货商商品列表' }, },
        { path: 'list', name: "/supplier/list", component: supplier_list, meta: { title: '供货商列表' }, },
        { path: 'orderList', name: "/supplier/supplier_orderList", component: supplier_orderList, meta: { title: '供货商订单列表' }, },
      ]
    },
    // 运费模板

    {
      path: '/freight', component: freight, children: [
        { path: 'add', name: "/freight/add", component: freight_add, meta: { title: '新增运费模板' }, },
        { path: 'edit', name: "/freight/edit", component: freight_edit, meta: { title: '编辑运费模板' }, },
        { path: 'list', name: "/freight/list", component: freight_list, meta: { title: '运费模板' }, },
      ]
    },

    { path: '/ctos', component: ctos },
    { path: '/vip', component: vip, meta: { title: 'vip管理' }, },
    { path: '/timeLimit', component: timeLimit, meta: { title: '限时购' }, },
    { path: '/class', component: _class, meta: { title: '分类列表' }, },
  ]
})



