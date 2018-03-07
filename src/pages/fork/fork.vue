<template>
    <div id="fork">

        <template>
            <router-view></router-view>
        </template>
    </div>

</template>

<script>
export default {
  name: "fork",
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
@import "fork.scss";
</style>