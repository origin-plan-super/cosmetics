<template>
  <div id="nav">
    <div style="margin-bottom: 20px;">
      <el-button size="small" @click="update()">刷新</el-button>
      <el-button size="small" @click="add()">新建导航</el-button>
    </div>
    <el-tabs v-model="activeTab" type="border-card" closable @tab-remove="removeTab">
      <el-tab-pane label="首页" :closable="false">

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
      isShowSelectSpecialPanel: false
    };
  },
  methods: {
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
      console.warn(this.activeTab);
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