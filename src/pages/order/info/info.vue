<template>
    <div id="info" class="info">

    </div>
</template>
<script>
export default {
  name: "info",
  data() {
    return {
      order: null
    };
  },
  methods: {},
  mounted() {
    if (!this.$route.params["order"]) {
      this.$router.go(-1);
    }
    this.order = this.$route.params["order"];
  },
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "info.scss";
</style>