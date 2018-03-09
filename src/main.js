// @ts-nocheck
import Vue from 'vue';
import App from './App.vue';
import VueRouter from "vue-router";
import ElementUI from 'element-ui';
import VueResource from 'vue-resource';
import 'element-ui/lib/theme-chalk/index.css'
import 'font-awesome-webpack'
import $ from 'jquery';

Vue.use(ElementUI);
Vue.use(VueRouter);
Vue.use(VueResource);


//自定义组件
import load from "./component/load/load.vue";
import OImg from "./component/OImg/OImg.vue";
Vue.component('load', load)
Vue.component('OImg', OImg)

//自定义扩展Vue

var Url = {};
Url.install = function (Vue, options) {

  var server = 'http://cosmetics.com/index.php/';
  // var server = 'http://120.78.162.200:12138/index.php/';

  var serverAdmin = server + 'Admin/';
  var serverHome = server + 'Home/';


  //获得处理过的地址，主要用于获得图片的地址
  Vue.prototype.$getUrl = function (url) {

    if (!url) {
      console.warn('【url为空】：' + url);
      return '';
    }
    //开始判断是不是http开头，如果是就不再添加头了
    var _url;
    if (url.indexOf('http') == -1) {
      var head = server;
      //取出index.php
      head = head.replace('index.php/', '');
      _url = head + url;
    } else {
      _url = url;
    }
    return _url;

  }
  //服务器地址
  Vue.prototype.$server = server;
  Vue.prototype.$serverAdmin = serverAdmin;
  Vue.prototype.$serverHome = serverHome;
  //上传文件地址
  Vue.prototype.$serverUpFile = serverAdmin + "Use/upFile";


}
//起源插件
var Origin = {};
Origin.install = function (Vue, options) {

  //是否登录

  Vue.prototype.$get = function (url, data, success, error) {

    if (data.token == null) {
      data.token = localStorage.token ? localStorage.token : '';
    }
    if (data.admin_id == null) {
      data.admin_id = localStorage.admin_id ? localStorage.admin_id : '';
    }

    if (url.indexOf("http") == -1) {
      //没有http
      url = this.$serverAdmin + url;
    }

    $.ajax({
      url: url,
      type: 'get',
      data: data,
      xhrFields: {
        withCredentials: true
      },
      success: res => {
        if (res == '' || res == null || res == undefined) {
          this.$message({ type: "warning", message: '接口并没有返回任何数据！' })
          console.warn("接口并没有返回任何数据！");
          return;
        }

        try {

          res = JSON.parse(res);

        } catch (e) {
          this.$message({ type: "error", message: '数据在转换为JSON时出现错误！' })
          if (error) {
            error(false, error);
          }
          return false;
        }

        //登录验证
        if (res.res == -992 || res.res == -991) {
          //登录失败跳转登录页
          router.push("/login");
        } else {
          if (success) {
            success(res);
          }
        }
      },
      error: () => {
        this.$message({ type: "error", message: '接口错误！' })
      }

    });

  }

  Vue.prototype.$post = function (url, data, success, error) {

    if (data.token == null) {
      data.token = localStorage.token ? localStorage.token : '';
    }
    if (data.admin_id == null) {
      data.admin_id = localStorage.admin_id ? localStorage.admin_id : '';
    }
    if (url.indexOf("http") == -1) {
      //没有http
      url = this.$serverAdmin + url;
    }
    $.ajax({
      url: url,
      type: 'post',
      data: data,
      xhrFields: {
        withCredentials: true
      },
      // crossDomain: true,
      success: res => {
        if (res == '' || res == null || res == undefined) {
          console.warn("接口并没有返回任何数据！");
          this.$message({ type: "warning", message: '接口并没有返回任何数据！' })
          return;
        }

        try {

          res = JSON.parse(res);

        } catch (e) {
          this.$message({ type: "error", message: '数据在转换为JSON时出现错误！' })
          if (error) {
            error(false, error);
          }
          return false;

        }

        //登录验证
        if (res.res == -992 || res.res == -991) {
          //登录失败跳转登录页
          router.push("/login");
        } else {
          if (success) {
            success(res);
          }
        }

      },
      error: () => {
        this.$message({ type: "error", message: '接口错误！' })
      }

    });

  }

  Vue.prototype.$getTextCount = function (str) {
    if (str == null || str == undefined) {
      str = '';
    }
    return str.length;
  }


  //二次封装饿了么的消息插件

  Vue.prototype.$warn = function (msg) {
    this.$message({ type: "warning", message: msg });
  }

  Vue.prototype.$error = function (msg) {
    this.$message({ type: "error", message: msg });
  }

  Vue.prototype.$info = function (msg) {
    this.$message({ type: "info", message: msg });
  }

  Vue.prototype.$success = function (msg) {
    this.$message({ type: "success", message: msg });
  }




}


Vue.use(Origin)
Vue.use(Url)
function focus(el, binding) {
  if (binding.value) {
    $(el).find('input').focus();
  }
}
Vue.directive('focus', {
  inserted: focus,
  update: focus,
  componentUpdated: focus,
})

function img(el, binding) {

  if (binding.value) {
    var img = Vue.prototype.$getUrl(binding.value);
    $(el).attr('src', img);
  }

}
Vue.directive('img', {
  inserted: img,
  update: img,
  componentUpdated: img,
})



//自定义扩展Vue  ==end==

//全局选项开启

// Vue.config.debug = true;
Vue.http.options.emulateHTTP = true;
Vue.http.options.emulateJSON = true;

//全局选项开启 <=== end ===>


//路由文件配置
import index from './pages/index/index.vue';

import login from './pages/login/login.vue';

import ctos from './pages/ctos/ctos.vue';


// 商品
import goods from './pages/goods/goods.vue';
import goods_edit from './pages/goods/edit/edit.vue';
import goods_list from './pages/goods/list/list.vue';


// 分类
import _class from './pages/class/class.vue';


//订单管理
import order from './pages/order/order.vue';
import order_list from './pages/order/list/list.vue';
import order_info from './pages/order/info/info.vue';

// 管理员账户管理

import admin from './pages/admin/admin.vue';
import admin_list from './pages/admin/list/list.vue';
import admin_info from './pages/admin/info/info.vue';



//店铺装修

import renovation from './pages/renovation/renovation.vue';
import renovation_carousel from './pages/renovation/carousel/carousel.vue';

//反馈管理

import feedback from './pages/feedback/feedback.vue';
import feedback_list from './pages/feedback/list/list.vue';
import feedback_info from './pages/feedback/info/info.vue';



// 分销管理
import fork from './pages/fork/fork.vue';
import fork_customer from './pages/fork/customer/customer.vue';
import fork_starGoodsList from './pages/fork/starGoodsList/starGoodsList.vue';



import fork_seller from './pages/fork/seller/seller.vue';



// 用户账户管理
import user from './pages/user/user.vue';
import user_list from './pages/user/list/list.vue';

// =============

import { EINPROGRESS } from 'constants';

const router = new VueRouter({
  // mode: 'history',
  // base: __dirname,
  routes: [
    { path: '/', component: index },
    { path: '/index', component: index, meta: { name: '首页' }, },
    { path: '/login', component: login },
    {
      path: '/goods', component: goods, children: [
        { path: 'edit', name: "/goods/edit", component: goods_edit, meta: { name: '编辑商品' }, },
        { path: 'list', component: goods_list, meta: { name: '商品列表' }, },
      ]
    },
    {
      path: '/order', component: order, children: [
        { path: 'list', name: "/order/list", component: order_list, meta: { name: '订单列表' }, },
        { path: 'info', name: "/order/info", component: order_info, meta: { name: '订单详情' }, },
      ]
    },
    {
      path: '/admin', component: admin, children: [
        { path: 'list', name: "/admin/list", component: admin_list, meta: { name: '管理账户列表' }, },
        { path: 'info', name: "/admin/info", component: admin_info, meta: { name: '账户详情' }, },
      ]
    },
    {
      path: '/renovation', component: renovation, children: [
        { path: 'carousel', name: "/renovation/carousel", component: renovation_carousel, meta: { name: '首页轮播管理' }, },
      ]
    },
    {
      path: '/feedback', component: feedback, children: [
        { path: 'list', name: "/feedback/list", component: feedback_list, meta: { name: '反馈管理' }, },
        { path: 'info', name: "/feedback/info", component: feedback_info, meta: { name: '反馈详情' }, },
      ]
    },
    //分销管理
    {
      path: '/fork', component: fork, children: [
        { path: 'customer', name: "/fork/customer", component: fork_customer, meta: { name: '客户管理' }, },
        { path: 'seller', name: "/fork/seller", component: fork_seller, meta: { name: '分销商管理' }, },
        { path: 'star_goods_list', name: "/fork/starGoodsList", component: fork_starGoodsList, meta: { name: '星级商品管理' }, },
      ]
    },
    //用户管理
    {
      path: '/user', component: user, children: [
        { path: 'list', name: "/user/list", component: user_list, meta: { name: '用户列表' }, },
        // { path: 'info', name: "/user/info", component: user_info, meta: { name: '订单详情' }, },
      ]
    },
    { path: '/ctos', component: ctos },
    { path: '/class', component: _class, meta: { name: '分类列表' }, },
  ]
});

//路由   <===  end  ===>

// 现在我们可以启动应用了！
// 路由器会创建一个 App 实例，并且挂载到选择符 #app 匹配的元素上。
const app = new Vue({
  router: router,
  render: h => h(App),
  mounted: function () {
    this.$nextTick(() => {
      this.$get(this.$serverAdmin + 'index/isLogin', {}, (res) => {

      });
    })
  }
}).$mount('#app')