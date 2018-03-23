<template>
  <div id="info" class="info">

    <template v-if="info.isShow">
      <el-alert :title="info.text" :type="info.type" :closable="info.close" :show-icon="info.icon">
      </el-alert>
    </template>

    <el-button type="text" icon="el-icon-back" @click="$router.go(-1)" size="mini"></el-button>
    <el-button type="text" icon="el-icon-refresh" :loading="refreshBtnLoad" @click="update()" size="mini"></el-button>

    <el-tabs type="border-card" v-if="order">

      <el-tab-pane label="订单信息">
        <el-row>
          <el-col :span="24">
            <el-form label-position="left" inline class="table-expand">

              <el-form-item label="名单编号">
                <span>{{ order_id }}</span>
              </el-form-item>

              <el-form-item label="下单时间">
                <span>{{ order.add_time }}</span>
              </el-form-item>

              <el-form-item label="发货时间">
                <!-- <span>{{ order.add_time }}</span> -->
              </el-form-item>

              <el-form-item label="完成时间">
                <!-- <span>{{ order.add_time }}</span> -->
              </el-form-item>

            </el-form>
          </el-col>

        </el-row>

        <!-- 商品列表 -->

        <div class="goods-list">
          <div class="goods-item" v-for="item in order.order_info.goods" :key="item.order_id">
            <img :src="$getUrl(item.img_list[0].src)" alt="图片错误！">

            <div class="goods-info">

              <span class="goods-title">
                {{item.goods_title}}
              </span>

              <br>
              <span class="goods-money text-muted">
                <span>
                  单价：￥{{order.order_info.user_spec[item.goods_id].money}}
                </span>
                -
                <span>
                  数量 {{order.order_info.user_spec[item.goods_id].goods_count}}
                </span>
                <!-- 规格 -->
                <span class="spec-list">

                  <span v-for="(spec,j) in order.order_info.user_spec[item.goods_id]" :key="spec.title">
                    <span v-if="typeof(spec)=='object'" class="spec-item">
                      -{{j}} : {{spec.title}}。
                    </span>
                  </span>

                </span>
              </span>

            </div>

          </div>
        </div>

      </el-tab-pane>
      <el-tab-pane label="包裹信息">
        <el-form label-position="left" inline class="table-expand">

          <el-form-item label="配送方式">
            <span>商家配送</span>
          </el-form-item>

          <el-form-item label="运送方式">
            <span>快递</span>
          </el-form-item>

          <el-form-item label="快递编号">
            <el-input size="mini" v-model="order.express_number" @focus="testValue=order.express_number">
              <el-button size="mini" slot="append" @click="saveExpressNumber()">保存</el-button>
            </el-input>
          </el-form-item>

          <el-form-item label="收货人">
            <span v-if="order.order_info.address">{{order.order_info.address.people }}</span>
          </el-form-item>

          <el-form-item label="联系电话">
            <span v-if="order.order_info.address">{{order.order_info.address.phone }}</span>
          </el-form-item>

          <el-form-item label="收货地址">

            <span v-if="order.order_info.address">{{order.order_info.address.region }}</span>
            <br>
            <span v-if="order.order_info.address">{{order.order_info.address.info }}</span>

          </el-form-item>

        </el-form>
      </el-tab-pane>
      <el-tab-pane label="客户信息">

      </el-tab-pane>
      <el-tab-pane label="支付信息">
        <p>
          <span> 应付款：{{order.money}}</span>
          <span>已收款:{{order.user_money}}</span>
        </p>
      </el-tab-pane>
    </el-tabs>

  </div>
</template>
<script>
export default {
  name: "info",
  data() {
    return {
      order_id: "",
      order: null,
      refreshBtnLoad: false,
      info: {
        type: "",
        text: "",
        isShow: false,
        close: false,
        icon: false
      },
      testValue: ""
    };
  },
  methods: {
    update() {
      this.refreshBtnLoad = true;
      this.$get(
        "order/getList",
        { where: { order_id: this.order_id } },
        res => {
          this.refreshBtnLoad = false;

          if (res.res >= 1) {
            // this.order_id;
            this.order = res.msg[0];
          }
          if (res.res < 0) {
            //订单不存在

            this.info.text = `订单获取失败，请重试！ 订单号：[ ${
              this.order_id
            } ]`;
            this.info.isShow = true;
            this.info.type = "error";

            this.$message({
              showClose: true,
              type: "error",
              message: `订单获取失败，请重试！`
            });
          }
        }
      );
    },
    saveExpressNumber() {
      if (this.order.express_number == this.testValue) return;
      var save = {
        express_number: this.order.express_number
      };

      this.$post(
        "order/save",
        { where: { order_id: this.order_id }, save: save, table: "orderInfo" },
        res => {
          if (res.res >= 1) {
            this.$message({ message: "保存成功！", type: "success" });
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }
      );
    },
    // 保存
    save(item, saveName, isInfo, isValidate) {
      if (isValidate && item[saveName] == this.testValue) return;
      var save = {};
      save[saveName] = item[saveName];

      this.$post(
        "order/save",
        { where: { order_id: item.order_id }, save: save },
        res => {
          if (res.res >= 1 && isInfo) {
            this.$message({ message: "保存成功！", type: "success" });
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }
      );
    }
  },
  mounted() {
    if (!this.$route.params["order_id"]) {
      if (!localStorage.order_id) {
        this.$router.go(-1);
        return;
      } else {
        this.order_id = localStorage.order_id;
      }
    } else {
      this.order_id = this.$route.params["order_id"];
    }
    localStorage.order_id = this.order_id;
    this.update();
  },
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "info.scss";
</style>
<style lang="scss">
.table-expand {
  font-size: 0;
}

.table-expand label {
  width: 90px;
  color: #99a9bf;
}

.table-expand .el-form-item {
  margin-right: 0;
  margin-bottom: 0;
  width: 50%;
}
</style>
