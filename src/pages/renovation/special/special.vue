<template>
  <div id="special">
    <div class="frame" style="width:500px">
      <el-button @click="isShowAdd=!isShowAdd" size="small">新建专题</el-button>
      <el-form ref="form" :model="add" label-width="100px" size="small" :rules="rules" v-if="isShowAdd">
        <el-form-item label="专题标题" prop="special_title">
          <el-input v-model="add.special_title"></el-input>
        </el-form-item>
        <el-form-item label="专题二级标题">
          <el-input v-model="add.special_title_2"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="small" @click="onSubmit">新建专题</el-button>
          <el-button @click="isShowAdd=false" size="small">取消</el-button>
        </el-form-item>
      </el-form>
    </div>

    <div class="frame">
      <template v-for="item in specials">
        <special-card :key="item.special_id" :bg="item.special_head" :special-id="item.special_id" :title="item.special_title" :info="item.special_title_2"></special-card>
      </template>

    </div>
    <!--  -->

  </div>
</template>
<script>
// special_goods_id
// special_id
// good_id
// add_time
// edit_time
import specialCard from "../../../component/special-card/special-card.vue";

export default {
  name: "special",
  props: {},
  data() {
    return {
      isShowAdd: false,
      specials: [],
      add: {
        special_title: "",
        special_title_2: ""
      },
      rules: {
        special_title: [
          { required: true, message: "请输入专题标题", trigger: "blur" },
          {
            min: 3,
            max: 255,
            message: "长度在 3 到 255 个字符",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    onSubmit() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          this.$post(
            "special/add",
            {
              add: this.add
            },
            res => {
              if (res.res >= 1) {
                this.$success("添加成功！");
                this.update();
                return;
              }
              this.$error("添加失败！请重试~");
            }
          );
        } else {
          return false;
        }
      });
    },
    update() {
      this.$get("special/getList", {}, res => {
        if (res.res >= 1) {
          this.specials = res.msg;
        }
      });
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.update();
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {},
  components: {
    specialCard
  }
};
</script>
<style lang="scss" scoped>
@import "special.scss";
</style>