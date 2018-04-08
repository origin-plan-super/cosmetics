<template>
  <div id="selectImg">
    <div>
      <span class="text-info">
        <span :class="{'text-error':img_list_beyond}">({{img_list.length}}/10)</span>
        尺寸640x640像素以上，大小2M以下 默认显示第1张，最多10张
        <!-- (可拖拽图片调整显示顺序 ) -->
      </span>
    </div>

    <div class="img-list">

      <transition-group name="img-list">

        <div class="img-item" v-for="(item,index) in img_list" :key="item.key">

          <div class="remove" @click="remove(item,index,img_list)">
            <i class="el-icon-close"></i>
          </div>

          <img :src="$getUrl(item.src)" alt="图片错误!">

        </div>

      </transition-group>

      <div class="img-item upload" v-if="!img_list_beyond">

        <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :limit="fileMax" :on-exceed="onExceed" :data="parame" list-type="picture-card" multiple :on-error="error" :on-success="success">
          <i class="el-icon-plus"></i>
        </el-upload>

      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "selectImg",
  props: {
    value: Array,
    src: {
      type: String,
      default: "admin"
    }
  },
  data() {
    return {
      img_list: [],
      parame: {
        token: localStorage.token,
        admin_id: localStorage.admin_id,
        src: ""
      },
      count: 0,
      fileMax: 10
    };
  },
  methods: {
    remove(item, index, list) {
      list.splice(index, 1);
      this.$emit("on-remove", item);
    },
    // 文件超出个数限制时的钩子
    onExceed() {
      this.$message({
        message: `还可以上传${10 - this.img_list.length}个文件！`,
        type: "warning"
      });
    },

    // 文件上传成功时的钩子
    success(response, file, fileList) {
      // clearFiles
      this.count++;

      if (this.count == fileList.length) {
        this.$refs.upload.clearFiles();
        this.count = 0;
      }

      this.img_list.push({
        src: response.msg.src,
        key: Math.random() + new Date().getTime()
      });

      this.$emit("on-success", response);
    },
    //失败
    error(response, file, fileList) {
      this.$message({
        message: `文件上传失败！`,
        type: "error"
      });
      console.log("失败");
      console.log(fileList);
    }
  },
  computed: {
    img_list_beyond() {
      return this.img_list.length >= 10;
    }
  },
  watch: {
    img_list() {

      let count = 10 - this.img_list.length;
      this.fileMax = count > 0 ? count : -1;

      var list=[];

      for (let i = 0; i < this.img_list.length; i++) {
        list.push(this.img_list[i].src);
      }

      this.$emit("input", list);

    },
    value(val){
      if(this.img_list.length<=0){
          for (let i = 0; i < val.length; i++) {
            this.img_list.push({
              src:val[i].src,
              key:val[i].img_id,
            });
          }
      }
    } 
  },

  mounted() {
    this.parame.src = this.src;
    this.$nextTick(() => {
      this.img_list = this.value;
    });
  }
};
</script>


<style lang="scss" scoped>
@import "selectImg.scss";
</style>