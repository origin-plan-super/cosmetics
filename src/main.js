// @ts-nocheck
import Vue from 'vue';
import App from './App.vue';
import router from './router';

//========================================================================================
//第三方扩展
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import 'font-awesome-webpack';
import VueAreaLinkage from 'vue-area-linkage';
import 'vue-area-linkage/dist/index.css';


Vue.use(VueAreaLinkage);
Vue.use(ElementUI);
Vue.use(require('vue-wechat-title'));
//========================================================================================


//========================================================================================
//自定义扩展
import Ajax from './plugin/ajax.js';
import Url from './plugin/url.js';
import Directive from './plugin/directive.js';
import components from './plugin/components.js';

Vue.use(components);
Vue.use(Ajax);
Vue.use(Url);
Vue.use(Directive);

//========================================================================================


// Vue.config.debug = true;
Vue.http.options.emulateHTTP = true;
Vue.http.options.emulateJSON = true;

//全局选项开启 <=== end ===>

import { EINPROGRESS } from 'constants';

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