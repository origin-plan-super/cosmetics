<template>
  <div id="specialEdit">

    <el-card style="margin-bottom:20px">
      <div slot="header">基本信息</div>
      <div class="frame" style="width:70%">
        <el-form ref="form" :model="save" label-width="100px" size="small" :rules="rules">

          <el-form-item label="效果预览">
            <special-card :bg="save.special_head" :special-id="special_id" :title="save.special_title" :info="save.special_title_2" :open="false"></special-card>
          </el-form-item>
          <el-form-item label="专题标题" prop="special_title">
            <el-input v-model="save.special_title"></el-input>
          </el-form-item>

          <el-form-item label="专题二级标题">
            <el-input :maxlength="255" v-model="save.special_title_2"></el-input>
          </el-form-item>

          <el-form-item label="专题介绍">
            <el-input type="textarea" :maxlength="255" placeholder="" :autosize="{ minRows: 5}" v-model="save.special_info"></el-input>
          </el-form-item>

          <el-form-item label="专题配图">
            <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :data="parame" multiple :on-error="error" :on-success="success">
              <el-button icon="el-icon-upload">上传专题配图</el-button>
            </el-upload>
          </el-form-item>

          <el-form-item>
            <el-button type="primary" size="small" @click="onSubmit">保存</el-button>
          </el-form-item>

        </el-form>
      </div>
    </el-card>

    <el-card style="margin-bottom:20px">
      <div slot="header">商品列表</div>
      <el-button size="small" @click="isShowSelectGoodsPanel=!isShowSelectGoodsPanel" style="margin:10px 0">选择商品</el-button>
      <el-button size="small" type="success" @click="saveGoods()" v-if="isShowSelectGoodsPanel">保存商品列表</el-button>
      <goods-select v-model="goods_list" v-if="isShowSelectGoodsPanel"></goods-select>
      <template>

        <div class="text-info" style="margin:10px 0;font-szie:13px">已选择的商品</div>

        <template v-for="(item,i) in goodsList">
          <goods-card :title="item.goods_title" :info="'￥'+item.sku[0].price" :img="item.img_list[0].src" :key="item.goods_id">
            <i class="el-icon-delete" @click="delGoods(item,i,goodsList)"></i>
          </goods-card>
        </template>

      </template>

    </el-card>

  </div>
</template>
<script>
import specialCard from "../../../component/special-card/special-card.vue";
import goodsSelect from "../../../component/goodsSelect/goodsSelect.vue";
import goodsCard from "../../../component/goods-card/goods-card.vue";

export default {
  name: "specialEdit",
  props: {},
  data() {
    return {
      goods_list: [], //用于保存之前的
      goodsList: [], //用于保存最后的
      isShowSelectGoodsPanel: false,
      parame: {
        token: localStorage.token,
        admin_id: localStorage.admin_id,
        src: "special/",
        del_src: ""
      },
      special_id: "",
      save: {
        special_head: "",
        special_logo: "",
        special_title: "",
        special_title_2: "",
        special_info: ""
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
    update() {
      this.$get(
        "special/get",
        {
          special_id: this.special_id
        },
        res => {
          if (res.res >= 1) {
            this.save = res.msg;
            this.goodsList = res.msg.goodsList;
          }
        }
      );
    },
    onSubmit() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          this.$post(
            "special/save",
            {
              where: {
                special_id: this.special_id
              },
              save: this.save
            },
            res => {
              if (res.res >= 1) {
                this.$success("保存成功！");
                this.update();
                return;
              }
              this.$error("保存失败！请重试~");
            }
          );
        } else {
          return false;
        }
      });
    },
    success(res) {
      this.save.special_head = res.msg.src;
    },
    //失败
    error(response, file, fileList) {
      this.$error("文件上传失败！");
    },
    saveGoods() {
      this.isShowSelectGoodsPanel = false;
      var goods_list = this.goods_list;

      if (goods_list.length <= 0) {
        this.$info("未选择商品");
        return;
      }
      var goodsIds = [];

      goods_list.forEach(item => {
        goodsIds.push(item.goods_id);
      });

      this.$get(
        "special/addGoods",
        {
          goodsIds: goodsIds,
          special_id: this.special_id
        },
        res => {
          if (res.res >= 1) {
            this.$success("添加成功");
            this.update();
            return;
          }
          this.$error("添加失败");
        }
      );
    },
    delGoods(item, i, list) {
      this.$post(
        "special/delGoods",
        {
          goods_id: item.goods_id,
          special_id: this.special_id
        },
        res => {
          if (res.res >= 1) {
            this.$success("删除成功！");
            list.splice(i, 1);
            return;
          }
          this.$error("删除失败！");
        }
      );
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.special_id = this.$route.query.special_id;
    this.update();
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {},
  components: {
    specialCard,
    goodsSelect,
    goodsCard
  }
};
</script>
<style lang="scss" scoped>
@import "specialEdit.scss";
</style>