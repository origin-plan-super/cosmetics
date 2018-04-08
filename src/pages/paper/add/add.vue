<template>
  <div id="add">

    <el-form :model="add" :rules="rules" ref="form" label-width="100px" style="width:90%" size="small">
      <el-form-item label="文章标题" prop="paper_title">
        <el-input type="text" :maxlength="255" placeholder="请输入文章标题" v-model="add.paper_title"></el-input>
      </el-form-item>
      <el-form-item label="文章信息" prop="paper_title">
        <el-input type="text" :maxlength="255" placeholder="请输入文章信息" v-model="add.paper_info"></el-input>
      </el-form-item>
      <el-form-item label="文章配图" prop="paper_head">
        <div class="paper-head">
          <img :src="$getUrl(add.paper_head)" class="paper-head">
        </div>
        <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :data="parame" multiple :on-error="error" :on-success="success">
          <el-button icon="el-icon-upload">上传配图</el-button>
        </el-upload>

      </el-form-item>
      <el-form-item label="文章类型" prop="name">

        <el-select v-model="add.class_id" placeholder="请选择类型">

          <el-option label="新手课堂" value="1"></el-option>
          <el-option label="每日精选" value="2"></el-option>
          <el-option label="每日涨知识" value="3"></el-option>
          <el-option label="客服&物流" value="4"></el-option>

        </el-select>

      </el-form-item>
      <el-form-item label="文章内容">
        <editor v-model="add.paper_content" ref="editor" style="width:100%"></editor>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm">发布</el-button>
      </el-form-item>

    </el-form>

  </div>
</template>
<script>
import editor from "../../../component/editor/editor.vue";

export default {
  name: "add",
  props: {},
  data() {
    return {
      add: {
        paper_title: "",
        paper_info: "",
        paper_head: "",
        class_id: "1",
        paper_content: ""
      },
      rules: {
        paper_title: [
          { required: true, message: "请输入文章标题", trigger: "blur" }
        ],
        paper_info: [
          { required: true, message: "请输入文章信息", trigger: "blur" }
        ],
        paper_head: [
          { required: true, message: "请选择文章配图", trigger: "blur" }
        ],

        paper_content: [
          { required: true, message: "请输入文章内容", trigger: "blur" }
        ]
      },
      parame: {
        token: localStorage.token,
        admin_id: localStorage.admin_id,
        src: "paper/",
        del_src: ""
      }
    };
  },
  methods: {
    submitForm() {
      this.add.paper_content = this.$refs["editor"].getContent();

      this.$refs["form"].validate(valid => {
        if (valid) {
          this.$post(
            "paper/add",
            {
              add: this.add
            },
            res => {
              if (res.res >= 1) {
                this.$success("发布成功");
                setTimeout(() => {
                  this.$router.push("/paper/list");
                }, 300);
                return;
              }
              this.$error("发布失败！请重试~");
            }
          );
        } else {
          return false;
        }
      });
    },
    success(response, file, fileList) {
      this.add.paper_head = response.msg.src;
      this.parame.del_src = response.msg.src;
    },
    //失败
    error(response, file, fileList) {
      this.$error("文件上传失败！");
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {},
  components: {
    editor
  }
};
</script>
<style lang="scss" scoped>
@import "add.scss";
</style>