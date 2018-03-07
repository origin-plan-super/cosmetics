<template>
  <div id="index">
    <div class="frame">
      <div class="well-list">

        <el-row>

          <el-col :span="6">

            <div class="well" @click="$router.push('/order/list')">
              <i class="fa fa-truck well-icon" style="background-color:#409EFF"></i>
              <div class="well-body">
                <div class="well-count">{{pending}}</div>
                <div class="well-title text-info">待发货订单</div>
              </div>
            </div>

          </el-col>
          <el-col :span="6">
            <div class="well" @click="$router.push('/order/list')">
              <i class="fa fa-legal well-icon" style="background-color:#ffc333"></i>
              <div class="well-body">
                <div class="well-count">{{rights}}</div>
                <div class="well-title text-info">待处理维权</div>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="well">
              <i class="fa fa-paste well-icon" style="background-color:#fb6b5b"></i>
              <div class="well-body">
                <div class="well-count">{{yesterday}}</div>
                <div class="well-title text-info">昨天下单数</div>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="well">
              <i class="fa fa-rmb well-icon" style="background-color:#ccc"></i>
              <div class="well-body">
                <div class="well-count">{{money}}</div>
                <div class="well-title text-info">昨日成交额</div>
              </div>
            </div>
          </el-col>
        </el-row>

      </div>

    </div>

  </div>

</template>

<script>
export default {
  name: "index",
  data() {
    return {
      pending: 0,
      rights: 0,
      yesterday: 0,
      money: 0
    };
  },
  methods: {
    update() {
      this.$get("Info/getIndex", {}, res => {
        if (res.res == 1) {
          this.pending = res.pending;
          this.rights = res.rights;
          this.yesterday = res.yesterday;
          this.money = res.money;
        }
      });
    }
  },
  watch: {},
  mounted: function() {
    this.update();
  }
};
</script>


<style lang='scss' scoped>
@import "index.scss";
</style>

