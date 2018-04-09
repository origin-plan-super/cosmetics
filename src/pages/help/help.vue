<template>
    <div id="help">

        <template>
            <router-view></router-view>
        </template>
    </div>

</template>

<script>
export default {
  name: "help",
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
@import "help.scss";
</style>