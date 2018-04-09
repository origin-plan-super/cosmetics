<template>
  <div id="goodsAdd">
    <div class="frame">
      <!-- <h3>
        <span v-if="model=='add'">新增商品</span>
        <span v-if="model=='edit'">编辑商品</span>
      </h3> -->

      <el-form :model="goods" :rules="rules" ref="goodsForm" label-width="150px" size="small" @submit.native.prevent>

        <el-card>

          <div slot="header" class="text-size-default">
              <el-button type="text" icon="el-icon-back" @click="$router.go(-1)" size="mini"></el-button>
            基本信息
          </div>

          <el-form-item prop="goods_title" label="商品名称">
            <el-input v-model="goods.goods_title" :maxlength="255"></el-input>
          </el-form-item>

          <el-form-item label="商品分类" prop="goods_class">

            <el-select v-model="goods.goods_class"  filterable  default-first-option placeholder="请选择商品分类">
              <el-option-group v-for="group in goods_class" :key="group.class_title" :label="group.class_title">
                <el-option v-for="item in group.children" :key="item.class_title" :label="item.class_title" :value="item.class_id">
                </el-option>
              </el-option-group>
            </el-select>

          </el-form-item>

          <el-form-item label='商品规格'>
            <el-button @click='show.spec=!show.spec'>展开/收起选择规格</el-button>
            <el-collapse-transition>
              <spec v-show='show.spec' :tree.sync="goods.tree" :sku.sync="goods.sku"></spec>
            </el-collapse-transition>
            <transition name='el-collapse-transition'>
            </transition>
          </el-form-item>

          <el-form-item label="商品图片">

            <select-img v-model="goods.img_list"></select-img>

          </el-form-item>

          <el-form-item label="商品详情">
            <editor v-model="goods.goods_content" ref="editor" style="width:100%"></editor>
          </el-form-item>

        </el-card>

        <el-card>
          <div slot="header" class="text-size-default">物流信息</div>

          <div class="cube-half">

            <el-form-item prop="logistics" label="物流模板">
              <el-select v-model="goods.logistics" filterable placeholder="请选择">
                <el-option v-for="item in logistics.template" :key="item.title" :label="item.title" :value="item.title">
                </el-option>
              </el-select>
            </el-form-item>

          </div>

        </el-card>

        <el-card>
          <div slot="header" class="text-size-default">其他信息</div>

          <el-form-item label='是否立刻上架' prop='is_up'>
            <div>
              <el-radio v-model="goods.is_up" label="1">立刻上架</el-radio>
            </div>
            <div>
              <el-radio v-model="goods.is_up" label="0">暂不上架</el-radio>
            </div>
          </el-form-item>

        </el-card>

        <el-card>
          <el-form-item>
            <el-button type="primary" @click='submitForm()'>保存</el-button>
            <el-button @click="cancelar()">取消</el-button>
          </el-form-item>
        </el-card>

      </el-form>

    </div>

  </div>
</template>


<script>
import spec from "../../../component/spec/spec.vue";
import editor from "../../../component/editor/editor.vue";
import selectImg from "../../../component/selectImg/selectImg.vue";

export default {
  name: "goodsAdd",
  data() {
    return {
      model: "add",
      show: {
        spec: true
      },
      logistics: {
        template: [{ title: "免邮" } /*{ title: "按体积算" }*/]
      },
      goods_class: [],
      goods: {
        tree:[],
        sku:[],
        //标题
        goods_title: "",
        //物流模板
        logistics: "免邮",
        //是否立刻上架
        is_up: "0",
        //商品分类
        goods_class: '',
        //图片
        img_list: [],
        //详情
        goods_content: ""
      },
      //验证条件
      rules: {
        goods_title: [
          { required: true, message: "请输入商品标题", trigger: "blur" },
          { max: 255, message: "长度小于255个字符", trigger: "blur" }
        ],
        logistics: [
          { required: true, message: "请选择物流模板", trigger: "blur" }
        ],
        goods_class: [
          { required: true, message: "请选择商品分类", trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    //构建分类列表，设置层级关系，组成树
    builderClassList() {
      var tree = [];
      var list = this.goods_class;
      for (let i = 0; i < list.length; i++) {
        const item = list[i];
        //如果没有上级id，代表是顶级
        item.sort = parseInt(item.sort);
        if (!item.super_id) {
          var item = item;
          item.children=[];
          tree.push(item);
        }
      }

      for (let i = 0; i < list.length; i++) {
        const item = list[i];
        //如果上级id，就寻找上级id，并且插入
        item.sort = parseInt(item.sort);

        if (item.super_id) {
          for (let j = 0; j < tree.length; j++) {
            const superItem = tree[j];
            if (item.super_id == superItem.class_id) {
              superItem.children.push(item);
            }
          }
        }
      }
      this.goods_class = tree;
    },
    cancelar() {
      this.$router.replace("/goods/list");
    },
    submitForm() {
      // return;
      this.goods.goods_content = this.$refs["editor"].getContent();
      var goods = this.goods;

      this.$refs["goodsForm"].validate(valid => {
        
        if (valid) {
          //验证成功
          this.$message({ message: "正在提交", type: "info" });
          const loading = this.$loading({
            lock: true,
            text: "正在提交……",
            spinner: "el-icon-loading",
            background: "rgba(0, 0, 0, 0.7)"
          });

          if (this.model == "add") {
            this.$post("goods/add", { add: goods }, res => {
              
              loading.close();
              if (res.res == 1) {
                this.$message({ message: "提交成功", type: "success" });
              }
              if (res.res < 0) {
                this.$message({ message: "提交失败！请重试！", type: "error" });
              }
            });
          }

          if (this.model == "edit") {
            this.$post(
              "goods/save",
              { save: goods },
              res => {
                loading.close();
                if (res.res == 1) {
                  this.$message({ message: "保存成功", type: "success" });
                }
                if (res.res < 0) {
                  this.$message({
                    message: "保存失败！请重试！",
                    type: "error"
                  });
                }
              }
            );
          }
        } else {
          this.$message({ message: "缺少必填项！", type: "warning" });
          return false;
        }
      });
    }
  },
  mounted: function() {
    // 如果路由传来了商品的id，就异步获取商品的信息，并且设置到goods上，
    // 如果路由没有传来任何商品的id，就代表是添加模式。
    // 默认是添加模式


    if (this.$route.query["goods_id"]) {
      this.model = "edit";
      var goods_id = this.$route.query["goods_id"];

      this.$get('goods/get',{
        goods_id:goods_id,
      },res=>{
        this.goods=res.msg;
      });

    }

    //取得商品分类
    this.$get("Class/getList", {}, res => {
      if (res.count > 0) {
        this.goods_class = res.msg;
        this.builderClassList();
      }
    });
  },
  components: {
    spec,
    selectImg,
    editor
  }
};

function arrToString(arr) {
  var _arr = {};
  for (var x in arr) {
    _arr[x] = arr[x];
    var type = typeof _arr[x];
    if (type == "object" || type == "array") {
      _arr[x] = JSON.stringify(_arr[x]);
    }
  }
  return _arr;
}
</script>

<style lang="scss" scoped>
@import "edit.scss";
</style>
<style>
.el-card__header {
  padding: 10px 20px;
}
</style>
