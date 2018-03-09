<template>
  <div class="set-user-super">
    <!-- //设置一个用户的身份 -->

    <el-dialog :visible.sync="isShow" width="50%" v-if="user!=null">

      <template slot="title">
        指定
        <el-tag>{{user.user_name}}</el-tag>
        的上级
      </template>
      <el-form ref="form" :rules="rules" size="mini" :model="data" label-width="80px">
        <el-form-item label="原上级">
          <span class="text-info">
            {{customary}}
          </span>
        </el-form-item>

        <el-form-item label="指定上级" prop="user_id">

          <el-select :loading="isLoad" v-loading="isLoad" v-model="data.super_id" placeholder="请选择一个上级">

            <el-option-group v-for="group in forks" :key="group.star.star_id" :label="group.star.star_name">

              <el-option v-for="item in group.node" :key="item.user_id" :value="item.user_id" :label="item.user_name" :disabled="item.user_id==data.user_id">

                <span style="float: left">{{ item.user_name }}</span>
                <span style="float: right; color: #8492a6; font-size: 13px">{{ item.star_name }}</span>

              </el-option>

            </el-option-group>

          </el-select>

        </el-form-item>
        <el-alert type="warning" title="修改客户的上级后，客户下单将按照新的上级关系链进行分佣，分佣人将改变。"></el-alert>
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
        super_id: "",
        user_id: ""
      },
      //原来的
      customary: "",
      forks: [],
      user: null,
      rules: {
        user_id: [{ required: true, message: "请选择上级！", trigger: "blur" }]
      }
    };
  },
  methods: {
    update() {
      this.isLoad = true;
      this.$get("fork/getForkList", {}, res => {
        // 分组处理

        if (res.res >= 1) {
          var list = {};
          for (let i = 0; i < res.msg.length; i++) {
            var item = res.msg[i];
            if (list[item.star_id] == null) {
              list[item.star_id] = {
                star: {
                  star_name: item.star_name,
                  star_id: item.star_id
                },
                node: []
              };
            }
            list[item.star_id].node.push(item);
          }
          this.forks = list;
        } else {
          this.forks = [];
        }
        this.isLoad = false;
      });
    },
    show(item) {
      this.update();
      this.user = item;
      this.data.user_id = item.user_id;
      if (item.super_star_name) {
        this.customary = item.super_star_name + "：" + item.super_name;
      } else {
        this.customary = "";
      }
      this.isShow = true;
    },
    submitForm(foemName) {
      this.$refs[foemName].validate(valid => {
        if (valid) {
          var add = this.data;

          this.$post("fork/setSuper", { add: add }, res => {
            console.log(res);
            if (res.res >= 1) {
              this.isShow = false;
              this.$message({ type: "success", message: "操作成功！" });
              this.$emit("on-success");
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
        this.data.super_id = "";
        this.data.user_id = "";
        this.$refs["form"].resetFields();
      }
    }
  }
};
</script>
<style lang="scss" scoped>
@import "setUserSuper.scss";
</style>
<style>

</style>