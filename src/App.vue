<template>
  <div id="app">

    <el-container style="height: 100vh">
      <el-aside style="width:auto">
        <el-menu class="left-menu" :collapse="isCollapse" router :default-active='defaultActive' :background-color="style.backgroundColor" :text-color="style.textColor" :active-text-color="style.activeTextColor">
          <!-- -->
          <div class="switch" @click="_switch" :style="{borderColor:style.switch.borderColor}">
            <i class="el-icon-arrow-left" v-if='!isCollapse'></i>
            <i class="el-icon-arrow-right" v-if='isCollapse'></i>
          </div>

          <el-menu-item-group>

            <el-menu-item index='/index'>
              <i class="fa fa-home"></i>
              <span slot="title">首页</span>
            </el-menu-item>

          </el-menu-item-group>

          <el-submenu index="/renovation" :show-timeout='0' :hide-timeout='0'>

            <template slot="title">
              <i class="fa fa-shopping-bag"></i>
              <span slot="title">店铺装修</span>
            </template>

            <el-menu-item-group>

              <span slot="title">页面</span>

              <el-menu-item index="/renovation/carousel">
                <span>首页轮播</span>
              </el-menu-item>

            </el-menu-item-group>

          </el-submenu>

          <el-submenu index="/goods" :show-timeout='0' :hide-timeout='0'>

            <template slot="title">
              <i class="fa fa-inbox"></i>
              <span slot="title">商品管理</span>
            </template>

            <el-menu-item-group>
              <span slot="title">分类管理</span>
              <el-menu-item index="/class">
                <span>分类列表</span>
              </el-menu-item>
            </el-menu-item-group>

            <el-menu-item-group>

              <span slot="title">商品</span>

              <el-menu-item index="/goods/edit">
                <span>添加商品</span>
              </el-menu-item>
              <el-menu-item index="/goods/list">
                <span>商品列表</span>
              </el-menu-item>

            </el-menu-item-group>

          </el-submenu>

          <el-submenu index="/order" :show-timeout='0' :hide-timeout='0'>

            <template slot="title">
              <i class="fa fa-paste"></i>
              <span slot="title">订单管理</span>
            </template>

            <el-menu-item-group>

              <span slot="title">订单</span>

              <el-menu-item index="/order/list">
                <span>订单管理</span>
              </el-menu-item>

            </el-menu-item-group>

          </el-submenu>

          <el-submenu index="/feedback" :show-timeout='0' :hide-timeout='0'>

            <template slot="title">
              <i class="el-icon-service"></i>
              <span slot="title">其他管理</span>
            </template>

            <el-menu-item-group>

              <span slot="title">用户反馈</span>

              <el-menu-item index="/feedback/list">
                <span>反馈列表</span>
              </el-menu-item>

            </el-menu-item-group>

          </el-submenu>

          <el-submenu index="/fork" :show-timeout='0' :hide-timeout='0'>

            <template slot="title">
              <i class="fa fa-code-fork"></i>
              <span slot="title">分销管理</span>
            </template>

            <el-menu-item-group>

              <el-menu-item index="/user/list">
                <span>客户管理</span>
              </el-menu-item>
              <el-menu-item index="/fork/seller">
                <span>分销商管理</span>
              </el-menu-item>

            </el-menu-item-group>

          </el-submenu>

        </el-menu>
      </el-aside>

      <el-container>
        <el-main>
          <el-header style="font-size: 12px">

            <el-menu class="top-menu float-left" mode="horizontal" v-if="$route.meta!=null">

              <el-menu-item :index="$route.fullPath">
                <span slot="title">{{$route.meta.name}}</span>
              </el-menu-item>

            </el-menu>

            <el-menu class="top-menu float-right" mode="horizontal" router>

              <el-submenu index="1" :show-timeout='0' :hide-timeout='0'>

                <template slot="title">
                  系统
                </template>

                <el-menu-item index="/admin/list">
                  管理员列表
                </el-menu-item>

                <el-menu-item index='/ctos'>
                  <span slot="title">CTOS</span>
                </el-menu-item>

              </el-submenu>

              <el-submenu index="2" :show-timeout='0' :hide-timeout='0'>
                <template slot="title">{{admin_name}}</template>
                <el-menu-item index="/admin/info">
                  账户设置
                </el-menu-item>
                <el-menu-item index="/login" @click='sinOut()'>
                  退出登录
                </el-menu-item>
              </el-submenu>
            </el-menu>

          </el-header>
          <router-view class="view"></router-view>
        </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<script>
export default {
  name: "app",
  data() {
    return {
      isCollapse: false,
      defaultActive: "",
      admin_name: localStorage.admin_name,
      style: {
        //导航条的颜色
        // backgroundColor: "#333",
        // textColor: "#eee",
        // activeTextColor: "#ffd04b",
        backgroundColor: "",
        textColor: "",
        activeTextColor: "",
        switch: {
          borderColor: "#e6e6e6",
          color: "#ccc"
        }
      }
    };
  },
  methods: {
    _switch: function() {
      this.isCollapse = !this.isCollapse;
      localStorage["app_nav_isCollapse"] = this.isCollapse ? "1" : "0";
    },
    sinOut: function() {
      this.$get("index/sinOut", {}, res => {
        localStorage.clear();
        this.$get("index/isLogin", {}, res => {});
      });
    }
  },
  watch: {
    $route() {
      this.admin_name = localStorage.admin_name;
    }
  },
  computed: {},
  mounted: function() {
    if (localStorage["app_nav_isCollapse"] == null) {
      this.isCollapse = true;
    } else {
      this.isCollapse =
        localStorage["app_nav_isCollapse"] == "1" ? true : false;
    }

    var path = this.$router.currentRoute;
    this.defaultActive = path.path;
  }
};
</script>

<style lang='scss' >
@import "pages.scss";
// @import url("//unpkg.com/element-ui/lib/theme-chalk/index.css");
</style>

<style lang='scss' scoped>
@import "App.scss";
</style>
