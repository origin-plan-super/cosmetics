<template>
  <div id="msg">

    <template>
      <router-view></router-view>
    </template>
  </div>

</template>

<script>
export default {
  name: "msg",
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
@import "msg.scss";
</style>