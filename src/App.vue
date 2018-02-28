<template>
  <div id="app">

    <el-container style="height: 100vh">
      <el-aside style="width:auto">
        <el-menu style="" class="left-menu" :collapse="isCollapse" router :default-active='defaultActive'>
          <div class="switch" @click="_switch">
            <i class="el-icon-arrow-left" v-if='!isCollapse'></i>
            <i class="el-icon-arrow-right" v-if='isCollapse'></i>
          </div>

          <el-menu-item-group>

            <el-menu-item index='/index'>
              <i class="el-icon-setting"></i>
              <span slot="title">首页</span>
            </el-menu-item>

          </el-menu-item-group>

          <el-submenu index="/goods" :show-timeout='0'>

            <template slot="title">
              <i class="el-icon-goods"></i>
              <span slot="title">商品管理</span>
            </template>

            <el-menu-item-group>
              <span slot="title">商品分类</span>
              <el-menu-item index="/class">
                <i class="fa fa-align-justify"></i>
                <span>分类列表</span>
              </el-menu-item>
            </el-menu-item-group>

            <el-menu-item-group>

              <span slot="title">商品</span>

              <el-menu-item index="/goods/edit">
                <i class="el-icon-circle-plus-outline"></i>
                <span>添加商品</span>
              </el-menu-item>
              <el-menu-item index="/goods/list">
                <i class="fa fa-table"></i>
                <span>商品列表</span>
              </el-menu-item>

            </el-menu-item-group>

          </el-submenu>
          <el-submenu index="/order" :show-timeout='0'>

            <template slot="title">
              <i class="fa fa-paste"></i>
              <span slot="title">订单管理</span>
            </template>

            <el-menu-item-group>

              <span slot="title">订单</span>

              <el-menu-item index="/order/list">
                <i class="fa fa-paste"></i>
                <span>订单管理</span>
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
              <el-menu-item index='/ctos'>
                <i class="el-icon-setting"></i>
                <span slot="title">CTOS</span>
              </el-menu-item>

              <el-submenu index="2" :show-timeout='0' :hide-timeout='0'>
                <template slot="title">账户</template>
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
      isCollapse: localStorage["app_nav_isCollapse"] == "1" ? true : false,
      defaultActive: ""
    };
  },
  methods: {
    _switch: function() {
      this.isCollapse = !this.isCollapse;
      localStorage["app_nav_isCollapse"] = this.isCollapse ? "1" : "0";
    },
    sinOut: function() {
      this.$get(this.$serverAdmin + "index/sinOut", {}, res => {
        this.$get(this.$serverAdmin + "index/isLogin", {}, res => {});
      });
    }
  },
  watch: {
    $route() {
      console.log(this.$route);
    }
  },
  computed: {},
  mounted: function() {
    var path = this.$router.currentRoute;
    this.defaultActive = path.path;
  }
};
</script>

<style lang='scss' >
@import "pages.scss";
@import url("//unpkg.com/element-ui/lib/theme-chalk/index.css");
</style>

<style lang='scss' scoped>
@import "App.scss";
</style>
