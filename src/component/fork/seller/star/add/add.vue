<template>
  <div class="star-add">
    <el-card>

      <el-form :model="add" size="mini" :rules="rules" ref="adForm" label-width="100px" class="add-form">

        <el-form-item label="等级名称" prop="star_name">
          <el-input v-model="add.star_name" placeholder="尽量简单，不要超过20字" :maxlength="20">
            <span slot="suffix">{{add.star_name.length}}/20</span>
          </el-input>
        </el-form-item>

        <!-- <el-form-item label="经营范围" prop="star_type">

          <el-radio v-model="add.star_type" :label="0">全部商品</el-radio>
          <el-radio v-model="add.star_type" :label="1">部分商品</el-radio>

        </el-form-item> -->

        <el-form-item>
          <el-button type="primary" @click="submitForm()" :loading="isLoad" :disabled="isLoad">

            <span v-if="isLoad">保存中……</span>
            <span v-if="!isLoad">保存</span>

          </el-button>
        </el-form-item>
      </el-form>

      <goods-select v-model="goods_list" v-if="add.star_type==1"></goods-select>

    </el-card>

  </div>
</template>
<script>
import goodsSelect from "../../../../goodsSelect/goodsSelect.vue";

export default {
  props: {},
  data() {
    return {
      add: {
        star_name: "",
        star_type: ""
      },
      goods_list: [],
      rules: {
        star_name: [
          { required: true, message: "请输入等级名称", trigger: "blur" },
          { max: 20, message: "长度不能超过 20 个字符", trigger: "blur" }
        ]
        // star_type: [
        //   { required: true, message: "请选择 经营范围", trigger: "blur" }
        // ]
      },
      isLoad: false
    };
  },
  methods: {
    submitForm() {
      this.$refs["adForm"].validate(valid => {
        if (valid) {
          //设置提交状态
          //type 0 全部商品
          //type 1 选择商品
          if (this.add.star_type == 1) {
            if (this.goods_list.length <= 0) {
              this.$message({
                type: "warning",
                message: "至少选择一个商品！"
              });
              return;
            }
          }
          this.isLoad = true;

          // ====

          var data = {
            add: this.add
          };

          if (this.add.star_type == 1) {
            var goods_list = this.goods_list;
            var arr = [];

            for (let i = 0; i < goods_list.length; i++) {
              arr.push({ goods_id: goods_list[i].goods_id });
            }

            data.goods_list = arr;
          } else {
            data.goods_list = 0;
          }

          this.$post(
            "star/add",
            data,
            res => {
              this.isLoad = false;
              if (res.res >= 1) {
                this.$message({ type: "success", message: "保存成功" });
                this.$emit("on-success");
                return;
              }
              this.$message({ type: "error", message: "保存失败！请重试" });
            },
            () => {
              this.isLoad = false;
            }
          );
        } else {
          return false;
        }
      });
    },
    next() {
      console.log("下一步");
    }
  },
  computed: {},
  mounted() {
    this.$nextTick(() => {
      this.add.star_type = 0;
    });
  },
  components: { goodsSelect },
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "add.scss";
</style>
<style>

</style>