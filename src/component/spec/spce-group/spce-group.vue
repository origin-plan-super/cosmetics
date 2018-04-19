<template>
  <div class="spce-group" v-if="group" v-loading="isLoading">
    <div class="spec-group-head">
      <span class="group-title">{{groupTitle}}</span>
      <el-button type="text" @click="isAll=!isAll" :disabled="disabled">全选</el-button>
      <el-button type="text" @click="edit(true)" :disabled="disabled">编辑</el-button>
      <el-button type="text" @click="delGroup()" :disabled="disabled">删除</el-button>

    </div>

    <div class="spec-list-edit" v-if="isEdit">

      <!-- <el-form-item label="规格名称">
        <el-input style="width:300px;" v-model="groupTitle" placeholder="请输入规格名称">
          <i slot="suffix" style="padding-right:5px" :class="{'text-danger':groupTitle.length>5}">{{groupTitle.length}}/5</i>
        </el-input>
      </el-form-item> -->

      <el-form-item label="规格值">
        <el-input style="width:300px" placeholder="输入回车即可添加" @keyup.enter.native="addProp()" v-model="nweProp.title">
          <i slot="suffix" style="padding-right:5px" :class="[{'text-danger':nweProp.title.length>30}]">{{nweProp.title.length}}/30</i>
        </el-input>
        <el-button size="mini" @click="addProp()">添加</el-button>
        <div v-if="message.isShow">
          <div :class="'text-'+message.type">
            {{message.msg}}
          </div>
        </div>
      </el-form-item>

      <el-form-item label=" " v-if="node.length>0">
        <el-tag style="margin:0 5px 5px 0" :disable-transitions="true" v-for="(item,i) in node" :key="item.sku_group_prop_name" closable @close="delProp(item,i,node)">{{item.sku_group_prop_name}}</el-tag>
      </el-form-item>

      <el-form-item label=" ">
        <el-button size="mini" @click="confirm()">确定</el-button>
      </el-form-item>

    </div>

    <div class="spec-prop-list" v-if="!isEdit">
      <label class="spec-prop-item" v-for="(item) in node" :key="item.sku_group_prop_name">
        <el-checkbox v-model="item.check" @change="change"></el-checkbox>
        <span class="spec-prop-item-title">
          {{item.sku_group_prop_name}}
        </span>
      </label>
    </div>

  </div>
</template>
<script>
export default {
  name: "spce-group",
  props: {
    //标题
    title: {
      type: String,
      default: ""
    },
    // 原始数据，用于保存数据
    group: {
      type: Object,
      default: null
    },
    disabled: {
      type: Boolean,
      default: false
    },
    //组所在的列表
    list: {
      type: Array,
      default: []
    },
    //组的在列表中的索引
    index: {
      type: Number,
      default: -1
    },
    node: {
      type: Array,
      default: []
    },
    tree: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      //是否是编辑模式
      isEdit: false,
      //当前组的标题
      groupTitle: "",
      //添加专用
      nweProp: {
        title: ""
      },
      //消息提示体
      message: {
        type: "",
        msg: ``,
        isShow: false
      },
      isAll: false,
      isLoading: false
    };
  },
  methods: {
    //提示
    msg(type, msg) {
      if (!type) {
        this.message.isShow = false;
        return;
      }
      this.message.type = type;
      this.message.msg = msg;
      this.message.isShow = true;
    },
    //添加规格下的属性
    addProp() {
      var isok = true;
      var title = this.nweProp.title + "";
      //判断长度
      if (title.length <= 0 || title.length > 30) {
        this.msg("error", `请输入规格值，长度不要超过30个字！`);
        return;
      }
      //检查是否重名

      if (
        this.node.filter(item => {
          return item.sku_group_prop_name == title;
        }).length > 0
      ) {
        this.msg("error", `输入的规格值已存在！`);
        return;
      }
      this.isLoading = true;
      //ajax交互
      this.$post(
        "skuGroupProp/add",
        {
          add: {
            sku_group_prop_name: title,
            sku_group_id: this.group.sku_group_id
          }
        },
        res => {
          this.isLoading = false;
          if (res.res >= 1) {
            this.node.push(this.getNewProp(res.msg));
            this.nweProp.title = "";
            this.msg(false);
            return;
          }
          this.msg("error", `添加失败！请重试`);
          this.$error("添加失败！请重试~");
        },
        error => {
          this.isLoading = false;
        }
      );
    },
    getNewProp(data) {
      var prop = data;
      prop.check = false;
      // sku_group_prop_name
      return prop;
    },
    edit(edit) {
      this.isEdit = edit;
      this.$emit("on-edit", this.isEdit);
    },
    delProp(item, i, list) {
      this.$post(
        "SkuGroupProp/del",
        {
          where: {
            sku_group_prop_id: item.sku_group_prop_id
          }
        },
        res => {
          if (res.res >= 1) {
            list.splice(i, 1);
            return;
          }
          this.$error("删除失败！请重试~");
        }
      );
    },
    delGroup() {
      this.$confirm(
        "提示：删除后，正在使用相应规格的其他商品被编辑时，商品的规格内容将受到影响， 是否继续?",
        `确定删除规格：${this.groupTitle}，及其所属的规格吗？`,
        { confirmButtonText: "确定", cancelButtonText: "取消", type: "warning" }
      )
        .then(() => {
          this.$post(
            "skuGroup/del",
            {
              where: {
                sku_group_id: this.group.sku_group_id
              }
            },
            res => {
              if (res.res >= 1) {
                this.$success("删除成功~");
                this.list.splice(this.index, 1);
                return;
              }
              this.$error("删除失败！请重试~");
            }
          );
        })
        .catch(() => {});
    },
    //规格编辑完毕，确认保存
    confirm() {
      //保存数据
      this.isLoading = true;
      this.$post(
        "skuGroup/save",
        {
          where: {
            sku_group_id: this.group.sku_group_id
          },
          save: {
            sku_group_name: this.groupTitle
          }
        },
        res => {
          this.isLoading = false;
          this.edit(false);
        },
        error => {
          this.isLoading = false;
          this.edit(false);
        }
      );
    },
    //只要选中项发生改变，就调用这个函数
    change() {
      //返回选中的项
      let list = [];
      list = this.node.filter(item => item.check);
      //更新数据
      //触发选中项改变事件，返回选中项
      this.$emit("change", list, this.group);
    },
    initSelect(list) {
      list.forEach(tree => {
        if(tree.k==this.groupTitle){
        
        tree.v.forEach(v => {
          this.node.forEach(item => {
            if (v.name == item.sku_group_prop_name) {
              item.check = true;
            }
          });
        });
        }
        
      });
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.groupTitle = this.title;

    this.$nextTick(() => {
      this.initSelect(this.tree);
    });
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {
    disabled(val) {},
    title(val) {
      this.groupTitle = val;
    },
    node(val) {},
    tree(val) {
      this.initSelect(val);
    },
    isAll(val) {
      this.node.forEach(item => {
        item.check = val;
      });
      this.change();
    }
  },
  components: {}
};
</script>
<style lang="scss" scoped>
@import "spce-group.scss";
</style>