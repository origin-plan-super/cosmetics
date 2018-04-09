<template>
  <div id="timeLimit">

    <el-card style="margin-bottom:25px">

      <el-form label-width="100px" size="mini" ref="ruleForm" :rules="rules" :model="add">

        <el-form-item label="时间" prop="time">
          <el-time-select v-model="add.time" :picker-options="{start: '00:00',step: '01:00',end: '23:00'}" placeholder="选择时间">
          </el-time-select>
        </el-form-item>

        <el-form-item label="选择商品" prop="goodsList">
          <goods-select v-model="add.goodsList"></goods-select>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="submitForm()">添加</el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <el-button type="primary" @click="update()" size="mini">刷新</el-button>

    <el-card style="margin-bottom:25px" v-for="(time,x) in times" :key="x">
      <div class="time-panel">

        <div slot="header" class="clearfix">
          <div class="time-title">
            <span>{{time.name}}</span>
            <small>
              {{time.goods.length}}个商品
              <small v-if="time.isOverdue">(已过期)</small>
            </small>
            <div style="float: right; padding: 3px 0;">
              <el-button type="text" @click="time.isShow=!time.isShow">收起/展开</el-button>
              <el-button type="text" @click="del(time)">删除</el-button>
            </div>
          </div>

        </div>

        <el-collapse-transition>
          <template v-if="time.isShow">

            <div class="time-body">
              <goods-card :key="item.goods_id" v-for="item in time.goods" :title="item.goods_title" :info="'￥'+item.sku[0].price" :img="item.goods_head"></goods-card>
            </div>

          </template>
        </el-collapse-transition>
      </div>

    </el-card>

  </div>

</template>
<script>
import goodsCard from "../../component/goods-card/goods-card.vue";
import goodsSelect from "../../component/goodsSelect/goodsSelect.vue";

export default {
  name: "timeLimit",
  props: {},
  data() {
    return {
      add: {
        time: "",
        goodsList: []
      },
      rules: {
        time: [{ required: true, message: "请选择时间", trigger: "blur" }],
        goodsList: [{ required: true, message: "请选择商品", trigger: "blur" }]
      },
      times: {}
    };
  },
  methods: {
    update() {
      this.$get("time/getList", {}, res => {
        if (res.res >= 1) {
          for (let x in res.msg) {
            res.msg[x].isShow = false;
          }
          this.times = this.buliderList(res.msg);
        }
      });
    },
    buliderList(list) {
      return list;
    },
    submitForm() {
      this.$refs["ruleForm"].validate(valid => {
        if (valid) {
          var ids = [];
          this.add.goodsList.forEach(item => {
            ids.push(item.goods_id);
          });
          this.$post(
            "time/add",
            {
              time: this.add.time,
              goods_id: ids
            },
            res => {
              if (res.res >= 1) {
                this.update();
                this.$success("添加成功！");
                return;
              }
              this.$error("添加失败！");
            }
          );
        } else {
          return false;
        }
      });
    },
    del(item) {
      var start_time = item.start_time;
      this.$post(
        "time/del",
        {
          start_time: start_time
        },
        res => {
          if (res.res >= 1) {
            this.$success("删除成功！");
            this.update();
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
  watch: {},
  components: {
    goodsCard,
    goodsSelect
  }
};
</script>
<style lang="scss" scoped>
@import "timeLimit.scss";
</style>