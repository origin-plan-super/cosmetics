<template>
  <div class="set-user-type">
    <!-- //设置一个用户的身份 -->

    <el-dialog :visible.sync="isShow" width="50%" v-if="user!=null">
      <template slot="title">
        设置
        <el-tag>{{user.user_name}}</el-tag>
        的身份
      </template>
      <el-form ref="form" :rules="rules" size="mini" :model="data" label-width="80px">

        <el-form-item label="原身份">
          <span class="text-info">
            {{customary}}
          </span>
        </el-form-item>

        <el-form-item label="指定身份" prop="user_type">

          <el-select v-model="data.user_type" placeholder="请选择">
            <el-option label="普通客户" :value="0"></el-option>
            <el-option label="分销商" :value="1"></el-option>
            <el-option label="推广员" :value="2"></el-option>
          </el-select>

        </el-form-item>

        <el-form-item label="指定等级" v-if="data.user_type==1" prop="star_id">

          <el-select :loading="isLoad" v-loading="isLoad" v-model="data.star_id" placeholder="请选择一个级别">
            <el-option :label="item.star_name" :value="item.star_id" v-for="item in stars" :key="item.star_id"></el-option>
          </el-select>

        </el-form-item>

      </el-form>

      <span slot="footer" class="dialog-footer">
        <el-button size="mini" @click="isShow = false">取 消</el-button>
        <el-button size="mini" type="primary" @click="submitForm('form')">保存</el-button>

      </span>
    </el-dialog>

  </div>
</template>
<script>
export default {
  props: {},
  data() {
    return {
      isShow: false,
      isLoad: false,
      data: {
        user_type: "",
        star_id: ""
      },
      //原来的
      customary: "",
      stars: [],
      user: null,
      rules: {
        user_type: [
          { required: true, message: "请选择身份！", trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    update() {
      this.isLoad = true;
      //先获得等级列表
      this.$get("star/getList", {}, res => {
        this.stars = res.msg;
        this.isLoad = false;
      });
    },
    show(item) {
      this.update();
      this.user = item;
      this.customary = item.star_name ? item.star_name + "" : "";
      this.isShow = true;
    },
    submitForm(foemName) {
      this.$refs[foemName].validate(valid => {
        if (valid) {
          var where = {
            user_id: this.user.user_id
          };
          var save = {};
          save.user_type = this.data.user_type;

          if (this.data.user_type == 0) {
            //普通客户
            save.star_id = null;
          }

          if (this.data.user_type == 1) {
            //分销商
            save.star_id = this.data.star_id;
          }

          if (this.data.user_type == 2) {
            //推广员
            save.star_id = null;
          }

          //开始保存

          this.$post("user/save", { where: where, save: save }, res => {
            console.log(res);
            if (res.res >= 1) {
              this.$message({ type: "success", message: "保存成功！" });
              this.$emit("on-success");

              this.isShow = false;

              return;
            }
            this.$message({ type: "error", message: "操作失败！请重试~" });
          });
        } else {
          return false;
        }
      });
    }
  },
  computed: {},
  mounted() {
    this.update();
  },
  destroyed() {},
  components: {},
  watch: {
    isShow(val) {
      if (!val) {
        this.data.user_type = "";
        this.data.star_id = "";
        this.$refs["form"].resetFields();
      }
    }
  }
};
</script>
<style lang="scss" scoped>
@import "setUserType.scss";
</style>
<style>

</style>