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
          <div class="goods-item">
            <img :src="$getUrl(order.snapshot.img)" alt="图片错误！">

            <div class="goods-info">

              <div class="goods-title">
                {{order.snapshot.goods_title}}
              </div>

              <div class="goods-money text-muted">
                <span>
                  [ 单价：￥{{order.snapshot.price}} ]
                </span>
                <span>-</span>
                <span>
                  [ 数量 {{order.snapshot.count}} ]
                </span>
                <span>-</span>
                <span>
                  [ 总价：￥{{order.price}} ]
                </span>
                <div class="spec-list">
                  <template v-for="n in 3">
                    <span v-if="order.snapshot['s'+n]" class="order-label" :key="n">{{order.snapshot['s'+n]}}</span>
                  </template>
                </div>
              </div>

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
            <span v-if="order.address">{{order.address.name }}</span>
          </el-form-item>

          <el-form-item label="联系电话">
            <span v-if="order.address">{{order.address.tel }}</span>
          </el-form-item>

          <el-form-item label="收货地址">

            <span v-if="order.address">{{order.address.province }}</span>
            <span v-if="order.address">{{order.address.city }}</span>
            <span v-if="order.address">{{order.address.county }}</span>
            <span>-</span>
            <span v-if="order.address">{{order.address.address_detail }}</span>

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
<script src="./info.js"></script>
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
