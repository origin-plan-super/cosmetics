<template>
  <div class="nav-panel">
    <div class="frame">
      <!-- <el-button size="small" @click="update()">刷新面板</el-button> -->
    </div>
    <el-card shadow="hover" style="margin-bottom:50px" v-if="navId!='0'">
      <div slot="header">导航配置</div>

      <el-form ref="form" label-width="100px" size="small" v-if="nav">

        <el-form-item label="导航标题" prop="special_title">
          <el-input v-model="nav.nav_title"></el-input>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" size="small" @click="onSubmit">保存</el-button>
        </el-form-item>

      </el-form>

    </el-card>

    <el-card shadow="hover" style="margin-bottom:50px">

      <div slot="header">商品配置</div>

      <el-button size="small" @click="isShowSelectGoodsPanel=!isShowSelectGoodsPanel" style="margin:10px 0">选择商品</el-button>
      <el-button size="small" type="success" @click="saveGoods()" v-if="isShowSelectGoodsPanel">保存商品列表</el-button>

      <goods-select v-model="goods_list" v-if="isShowSelectGoodsPanel"></goods-select>

      <template v-if="nav">

        <div class="text-info" style="margin:10px 0;font-size:13px">已选择的商品</div>
        <template v-for="(item,i) in nav.goodsList">
          <goods-card :title="item.goods_title" :info="'￥'+item.sku.length>0?item.sku[0].price:'暂无价格'" :img="item.img_list.length>0?item.img_list[0].src:''" :key="item.goods_id">
            <i class="el-icon-delete" @click="delGoods(item,i,nav.goodsList)"></i>
          </goods-card>
        </template>

      </template>

    </el-card>

    <el-card shadow="hover">

      <div slot="header">专题配置</div>

      <el-button size="small" @click="isShowSelectSpecialPanel=!isShowSelectSpecialPanel" style="margin:10px 0">选择专题</el-button>
      <el-button size="small" type="success" @click="saveSpecial()" v-if="isShowSelectSpecialPanel">保存专题列表</el-button>

      <div class="text-info" style="margin:10px 0;font-size:13px">已选择的专题</div>
      <special-select v-model="special_list" v-if="isShowSelectSpecialPanel"></special-select>

      <template v-if="nav">
        <special-card v-for="(item,i) in nav.specials" size="small" :key="item.special_id" :bg="item.special_head" :special-id="item.special_id" :title="item.special_title" :info="item.special_title_2">
          <div class="special-del" @click.stop="delSpecial(item,i,nav.specials)">
            <i class="el-icon-delete"></i>
          </div>
        </special-card>
      </template>
    </el-card>

  </div>

</template>
<script>
import goodsSelect from "../goodsSelect/goodsSelect.vue";
import goodsCard from "../goods-card/goods-card.vue";
import specialCard from "../special-card/special-card.vue";
import specialSelect from "../special-select/special-select.vue";
export default {
  name: "nav-panel",
  props: {
    navId: {
      type: String,
      default: ""
    },
    navTitle: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      nav: null,
      special_list: [],
      goods_list: [],
      isShowSelectGoodsPanel: false,
      isShowSelectSpecialPanel: false
    };
  },
  methods: {
    onSubmit() {
      this.$post(
        "nav/save",
        {
          where: { nav_id: this.navId },
          save: {
            nav_title: this.nav.nav_title
          }
        },
        res => {
          if (res.res >= 1) {
            this.$emit("update:navTitle", this.nav.nav_title);
            this.$success("保存成功！");
          }
        }
      );
    },
    update() {
      this.$get(
        "nav/get",
        {
          nav_id: this.navId
        },
        res => {
          console.log(res.msg);
          this.nav = res.msg;
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
          nav_id: this.navId
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
    saveSpecial() {
      this.isShowSelectSpecialPanel = false;
      var special_list = this.special_list;
      if (special_list.length <= 0) {
        this.$info("未选择专题");
        return;
      }
      var specialIds = [];
      special_list.forEach(item => {
        specialIds.push(item.special_id);
      });
      this.special_list = [];

      this.$get(
        "nav/addSpecial",
        {
          specialIds: specialIds,
          nav_id: this.navId
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
          nav_id: this.navId
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
    },
    delSpecial(item, i, list) {
      this.$post(
        "nav/delSpecial",
        {
          special_id: item.special_id,
          nav_id: this.navId
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
    navId() {
      this.update();
    }
  },
  components: {
    goodsSelect,
    goodsCard,
    specialCard,
    specialSelect
  }
};
</script>
<style lang="scss" scoped>
@import "nav-panel.scss";
</style>