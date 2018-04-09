<template>
  <div id="Vip">
    <el-button @click="update()">刷新</el-button>
    <el-card v-for="item in vips" :key="item.vip_level" style="margin-bottom:25px" shadow="hover">
      <div slot="header" class="clearfix">
        <span>{{item.vip_level}}</span>
        <div style="float: right; padding: 3px 0">
          <el-button type="text" @click="item.isShow=!item.isShow">收起/展开</el-button>
          <el-button type="text" @click="save(item)">保存</el-button>
        </div>
      </div>
      <el-collapse-transition>
        <template v-if="item.isShow">
          <el-form size="mini">

            <el-form-item label="等级">
              <el-input v-model="item.vip_level"></el-input>
            </el-form-item>

            <el-form-item label="等级名">
              <el-input v-model="item.vip_name"></el-input>
            </el-form-item>

            <el-form-item label="自购省钱%">
              <el-input v-model="item.discount"></el-input>
            </el-form-item>

            <el-form-item label="下级打折省下的钱，返给自己的比率%">
              <el-input v-model="item.discountRebate"></el-input>
            </el-form-item>

            <el-form-item label="当下级卖出去一个商品后，自己可以得到的回扣%">
              <el-input v-model="item.saleMoneyRebate"></el-input>
            </el-form-item>

            <el-form-item label="当下级的下级卖出去一个商品后，自己可以得到的回扣，百分比%">
              <el-input v-model="item.subSaleMoneyRebater"></el-input>
            </el-form-item>

            <el-form-item label="当此用户不是会员的时候，分享商品可以得到的回扣%">
              <el-input v-model="item.shareMoney"></el-input>
            </el-form-item>

            <el-form-item label="邀人得钱奖￥">
              <el-input v-model="item.invitePeople"></el-input>
            </el-form-item>

            <el-form-item label="下级所有银牌团队新增会员奖￥">
              <el-input v-model="item.subSilverInvitePeople"></el-input>
            </el-form-item>

            <el-form-item label="直属团队新增会员的管理奖￥">
              <el-input v-model="item.directlyTeamInvitePeopleManage"></el-input>
            </el-form-item>

            <el-form-item label="直属团队新增会员的奖金￥">
              <el-input v-model="item.directlyTeamInvitePeople"></el-input>
            </el-form-item>

            <el-form-item label="可以得到下级管理奖金的比率%">
              <el-input v-model="item.subManageRatio"></el-input>
            </el-form-item>
          </el-form>
        </template>
      </el-collapse-transition>

    </el-card>
  </div>
</template>
<script>
export default {
  name: "Vip",
  props: {},
  data() {
    return {
      isLoad: false,
      vips: []
    };
  },
  methods: {
    save(item) {
      this.$post(
        "vip/save",
        {
          where: {
            vip_level: item.vip_level
          },
          save: item
        },
        res => {
          if (res.res >= 1) {
            this.$success("保存成功~");
            return;
          }
          this.$success("保存失败~");
        }
      );
    },
    update() {
      this.isLoad = true;
      this.$get("vip/getList", {}, res => {
        console.log(res);
        res.msg.forEach(item => {
          item.isShow = true;
        });
        this.vips = res.msg;
        this.isLoad = false;
      });
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
  components: {}
};
</script>
<style lang="scss" scoped>
@import "vip.scss";
</style>