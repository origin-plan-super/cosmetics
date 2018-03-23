<template>

  <img :class="['o-img',{'loading':isLoad}]" v-img="url">

</template>
<script>
import $ from "jquery";
export default {
  name: "o-img",
  props: {
    src: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      isLoad: false,
      loadUrl: "./src/assets/loading.gif",
      errorUrl:
        "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489486509807&di=22213343ba71ad6436b561b5df999ff7&imgtype=0&src=http%3A%2F%2Fa0.att.hudong.com%2F77%2F31%2F20300542906611142174319458811.jpg",
      url: "",
      img: null
    };
  },
  methods: {
    //图片加载错误
    onError() {
      this.isLoad = false;
      this.url = this.errorUrl;
    },
    //图片加载成功
    onSuccess() {
      setTimeout(() => {
        this.url = this.src;
        this.isLoad = false;
      }, 1000);
    },
    update() {
      if (this.src.length <= 0) return;

      this.url = this.loadUrl;

      //让图片状态为加载中
      this.isLoad = true;
      //创建图片元素
      this.img = new Image();
      //设置图片元素地址为传来的地址
      this.img.src = this.$getUrl(this.src);

      this.img.onerror = () => {
        // 图片加载错误时的替换图片
        this.onError();
      };
      this.img.onload = () => {
        // 图片加载成功后把地址给原来的img
        this.onSuccess();
      };
    }
  },
  computed: {},
  filters: {},
  mounted() {
    this.update();
  },
  destroyed() {},
  components: {},
  watch: {
    src(val) {
      this.update();
    }
  }
};
</script>
<style lang="scss" scoped>
@import "OImg.scss";
</style>
<style>

</style>