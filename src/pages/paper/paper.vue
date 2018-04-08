<template>
  <div id="paper">

    <template>
      <router-view></router-view>
    </template>
  </div>

</template>

<script>
export default {
  name: "paper",
  data() {
    return {};
  },
  methods: {
    update() {}
  },
  mounted: function() {
    this.update();
  },
  components: {},
  watch: {}
};
</script>

<style lang="scss" scoped>
@import "paper.scss";
</style>