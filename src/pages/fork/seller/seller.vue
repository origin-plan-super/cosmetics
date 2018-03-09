<template>
  <div id="seller">
    <div class="frame">

      <el-tabs type="border-card" :value="tab.active" @tab-click="tabClick">

        <el-tab-pane label="分销商列表" name="分销商列表">

          <seller-list></seller-list>

        </el-tab-pane>

        <el-tab-pane label="分销商审核" name="分销商审核">

        </el-tab-pane>

        <el-tab-pane label="等级管理" name="等级管理">

          <seller-star></seller-star>

        </el-tab-pane>

      </el-tabs>

    </div>

  </div>
</template>
<script>
import sellerList from "../../../component/fork/seller/list/list.vue";
import sellerStar from "../../../component/fork/seller/star/star.vue";

export default {
  name: "seller",
  props: {},
  data() {
    return {
      tab: {
        active: localStorage["fork_seller_tab_active"]
      }
    };
  },
  methods: {
    tabClick(tab) {
      localStorage["fork_seller_tab_active"] = tab.name;
    }
  },
  computed: {},
  mounted() {},
  components: { sellerList, sellerStar },
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "seller.scss";
</style>
<style>

</style>