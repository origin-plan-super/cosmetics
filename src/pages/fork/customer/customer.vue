<template>
    <div id="customer">

        <template>
            <router-view></router-view>
        </template>
    </div>

</template>

<script>
export default {
  name: "customer",
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
@import "customer.scss";
</style>