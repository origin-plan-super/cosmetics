<template>
  <div id="goods">

    <template>
      <router-view></router-view>
    </template>

  </div>
</template>

<script>
export default {
  name: "goods",
  data() {
    return {
      // 当前页
    };
  },
  methods: {},
  mounted: function() {}
};
</script>


<style lang='scss' scoped>
@import "goods.scss";
</style>

