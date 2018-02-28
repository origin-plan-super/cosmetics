// @ts-nocheck
import Vue from 'vue';
import App from './App.vue';
import VueRouter from "vue-router";
import ElementUI from 'element-ui';
import VueResource from 'vue-resource';
import $ from 'jquery';

// require('font-awesome-webpack');




Vue.use(ElementUI);
Vue.use(VueRouter);
Vue.use(VueResource);

//自定义扩展Vue

var Url = {};
Url.install = function (Vue, options) {

  var server = 'http://cosmetics.com/index.php/';
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
      _url = server + url;
    } else {
      _url = url;
    }
    //取出index.php
    _url = _url.replace('index.php/', '');
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
      success(res) {

        try {

          res = JSON.parse(res);

        } catch (error) {
          console.error(url + '：接口出现错误！');
          console.error(error);
          console.error(res);
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
      success(res) {

        try {

          res = JSON.parse(res);

        } catch (error) {

          console.error(url + '：接口出现错误！');
          console.error(error);
          console.error(res);
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

      }

    });

  }

  Vue.prototype.$getTextCount = function (str) {
    if (str == null || str == undefined) {
      str = '';
    }
    return str.length;
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

// =============

import { EINPROGRESS } from 'constants';

const router = new VueRouter({
  mode: 'history',
  base: __dirname,
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