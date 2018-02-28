<template>
  <div id="index">
    <div class="frame">
      <div>index</div>
      <el-button-group>
        <el-button type="primary" icon="el-icon-refresh" @click='get'>get</el-button>
        <el-button type="primary" icon="el-icon-refresh" @click='post'>post</el-button>
        <el-button type="primary" icon="el-icon-share"></el-button>
        <el-button type="primary" icon="el-icon-delete"></el-button>
      </el-button-group>

    </div>
    <div class="frame">
      <p>get:</p>
      <el-input v-model='getKey'></el-input>
      <p>post:</p>
      <el-input v-model='postKey'></el-input>
    </div>

    <pre v-html='response'></pre>
    <pre v-html='response2'></pre>

  </div>

</template>

<script>
export default {
  name: "index",
  data() {
    return {
      response: {},
      response2: {},
      getKey: "get请求参数",
      postKey: "post请求参数"
    };
  },
  methods: {
    get: function() {
      this.$get(
        this.$serverAdmin + "index/get",
        {
          key: this.getKey
        },
        res => {
          this.response = res;
        }
      );
    },
    post: function() {
      this.$post(
        this.$serverAdmin + "index/get",
        {
          keyPost: this.postKey
        },
        res => {
          this.response2 = res;
        }
      );
    }
  },
  watch: {
    getKey: function() {
      this.get();
    },
    postKey: function() {
      this.post();
    }
  },
  //启动函数
  mounted: function() {}
};
</script>

<style lang='scss'>
@import "../../pages.scss";
</style>

<style lang='scss' scoped>
@import "index.scss";
</style>

