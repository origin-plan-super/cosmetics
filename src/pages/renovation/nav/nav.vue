<template>
  <div id="nav">
    <div style="margin-bottom: 20px;">
      <el-button size="small" @click="update()">刷新</el-button>
      <el-button size="small" @click="add()">新建导航</el-button>
    </div>
    <el-tabs v-model="activeTab" type="border-card" closable @tab-remove="removeTab">
      <el-tab-pane label="首页" :closable="false">

        <el-card shadow="hover">

          <el-form label-width="150px" size="mini">

            <el-form-item label="是否显示主会场">
              <el-switch active-value="1" inactive-value="0" v-model="config.main_venue_show" active-color="#13ce66"></el-switch>
            </el-form-item>

            <el-form-item label="主会场配图" v-if="config.main_venue_show=='1'">

              <o-upload src="main/" v-model="config.main_bg_url">
                <el-button icon="el-icon-upload">{{config.main_bg_url.length>0?'重新上传':'上传配图'}}</el-button>
              </o-upload>
              <img :src="$getUrl(config.main_bg_url)" v-if="config.main_bg_url.length>0" class="main-img" alt="图片错误！">

            </el-form-item>

            <el-form-item>
              <el-button type="success" @click="saveConfig()">保存</el-button>
            </el-form-item>

          </el-form>

        </el-card>

        <nav-panel nav-id="0"></nav-panel>

      </el-tab-pane>
      <el-tab-pane v-for="(item) in navTab" :key="item.name" :label="item.title" :name="item.name">
        <nav-panel :nav-id="item.name" :nav-title.sync="item.title"></nav-panel>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
import NavPanel from "../../../component/nav-panel/nav-panel.vue";

import goodsSelect from "../../../component/goodsSelect/goodsSelect.vue";
import goodsCard from "../../../component/goods-card/goods-card.vue";
import specialCard from "../../../component/special-card/special-card.vue";
import specialSelect from "../../../component/special-select/special-select.vue";

export default {
  name: "navCont",
  props: {},
  data() {
    return {
      navTab: [],
      activeTab: localStorage.activeTab,
      special_list: [],
      goods_list: [],
      goodsList: [],
      isShowSelectGoodsPanel: false,
      isShowSelectSpecialPanel: false,
      config: {
        main_venue_show: "1",
        main_bg_url: ""
      }
    };
  },
  methods: {
    saveConfig() {
      this.$post(
        "config/saveData",
        {
          save: this.config
        },
        res => {
          if (res.res >= 1) {
            this.$success("保存成功！");
            return;
          }
          this.$error("保存失败！请重试~");
        }
      );
    },
    update() {
      this.$get("nav/getList", {}, res => {
        if (res.res >= 1) {
          this.navTab = [];
          for (let i = 0; i < res.msg.length; i++) {
            this.addTab(res.msg[i]);
          }
          return;
        }
        this.$error("数据加载失败！");
      });

      this.$get("Config/get", {}, res => {
        if (res.res >= 1) {
          if (res.msg.main_bg_url == null) {
            res.msg.main_bg_url = "";
          }
          this.config = res.msg;
          return;
        }
        this.$error("配置项获取失败！请刷新重试~");
      });
    },
    addTab(nav) {
      //先配置好信息再添加
      this.navTab.push({
        title: nav.nav_title,
        name: nav.nav_id
      });
    },
    //添加询问
    add() {
      this.$prompt("请输入导航标题", "配置", {
        confirmButtonText: "确定",
        cancelButtonText: "取消"
      })
        .then(({ value }) => {
          this.addData({
            nav_title: value
          });
        })
        .catch(() => {
          this.$info("取消输入");
        });
    },
    //添加数据
    addData(add) {
      this.$get("nav/add", { add: add }, res => {
        if (res.res >= 1) {
          this.$success("添加成功~");
          this.addTab(res.msg);
          return;
        }
        this.$error("添加失败！");
      });
    },
    removeTab(targetName) {
      let tabs = this.navTab;
      if (targetName == undefined) {
        this.$info("首页不能删除~");
        return;
      }
      this.$post(
        "nav/del",
        {
          nav_id: targetName
        },
        res => {
          if (res.res >= 1) {
            this.navTab = tabs.filter(tab => tab.name !== targetName);
            this.$success("删除成功~");
            return;
          }
          this.$error("删除失败！");
        }
      );
    },
    saveGoods() {
      this.isShowSelectGoodsPanel = false;
      var goods_list = this.goods_list;
      if (goods_list.length <= 0) {
        this.$info("未选择商品");
        return;
      }
      var goodsIds = [];
      goods_list.forEach(item => {
        goodsIds.push(item.goods_id);
      });

      this.goods_list = [];

      this.$post(
        "nav/addGoods",
        {
          goodsIds: goodsIds,
          nav_id: 0
        },
        res => {
          if (res.res >= 1) {
            this.$success("添加成功");
            this.update();
            return;
          }
          this.$error("添加失败");
        }
      );
    },
    delGoods(item, i, list) {
      this.$post(
        "nav/delGoods",
        {
          goods_id: item.goods_id,
          nav_id: 0
        },
        res => {
          if (res.res >= 1) {
            this.$success("删除成功！");
            list.splice(i, 1);
            return;
          }
          this.$error("删除失败！");
        }
      );
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.update();
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {
    activeTab(val) {
      localStorage.activeTab = val;
    }
  },
  components: {
    [NavPanel.name]: NavPanel,
    goodsSelect,
    goodsCard,
    specialCard,
    specialSelect
  }
};
</script>
<style lang="scss" scoped>
@import "nav.scss";
</style>