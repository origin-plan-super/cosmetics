<template>
  <div id="spec">
    <div class="text-size-default">
      <p>选择规格</p>
    </div>
    <div class="spec-list">

      <div class="spec-item" v-for="(group,index) in spec" :key="group.spec_id">
        <div class="spec-title">

          <span class="title">{{group.title}}</span>
          <el-button type="text" @click="selectSpecGroup(group.node,group)" :disabled="isEditModel">全选</el-button>
          <el-button type="text" @click="editSpecGroup(group,index)" :disabled="isEditModel">编辑</el-button>
          <el-button type="text" @click="removeSpecGroup(group,index,spec)" :disabled="isEditModel">删除</el-button>

        </div>

        <div class="spec-list-edit" v-if="group.isEdit">
          <div>
            <el-form-item label="规格名称">
              <el-input style="width:300px;" v-model="group.title" placeholder="请输入规格名称">
                <i slot="suffix" style="padding-right:5px" :class="[{'text-danger':$getTextCount(group.title)>5}]">{{$getTextCount(group.title)}}/5</i>
              </el-input>
            </el-form-item>
          </div>

          <div>
            <el-form-item label="属性值">

              <el-input style="width:300px" placeholder="输入回车即可添加" @keyup.enter.native="addSpecProp(group.node,group)" v-model="addProp.title">
                <i slot="suffix" style="padding-right:5px" :class="[{'text-danger':$getTextCount(addProp.title)>15}]">{{$getTextCount(addProp.title)}}/15</i>
              </el-input>
              <el-button size="small" @click="addSpecProp(group.node,group)">添加</el-button>

              <div v-if="group.message.isShow">

                <div :class="'text-'+group.message.type">
                  {{group.message.message}}
                </div>

              </div>

            </el-form-item>

          </div>

          <div class="spec-list-edit-prop">
            <el-tag class="spec-list-edit-prop-item" :disable-transitions="true" v-for="(item,jndex) in group.node" :key="item.title" closable @close="delSpecProp(item,jndex,group.node,index)">{{item.title}}</el-tag>
          </div>

          <div style="padding-left:150px">

            <el-button size="small" @click="okEditSpecGroup(group, index)">确定</el-button>
            <el-button size="small" @click="removeSpecGroup(group,index,spec)" v-if="group.isAddModel">取消</el-button>

          </div>

        </div>
        <div class="spec-prop-list">

          <label class="spec-prop-item" v-for="(item,jndex) in group.node" v-if="!group.isEdit">
            <el-checkbox v-model="item.check" @change="changeSpecProp()"></el-checkbox>
            <span class="spec-prop-item-title">
              {{item.title}}
            </span>
          </label>
        </div>

      </div>
      <!-- 规格选择结束    ==  end  == -->
      <el-button type="text" @click="addSpecGroup()">添加规格</el-button>
      <div class="text-size-default text-info">规格添加后请勿随意删除，删除后正在使用相应规格的商品在编辑时规格部分将受到影响</div>
    </div>

    <!--  价格&库存 开始-->
    <div class="text-size-default">
      <p>价格&库存</p>
    </div>

    <div class="param-list">
      <p>
        <span class="text-info">批量填充： </span>
        <el-input v-model="filling.money" placeholder="价格" style="width:100px"></el-input>
        <el-input v-model="filling.stock" placeholder="库存" style="width:100px"></el-input>
        <el-button type="primary" @click="batchFilling()">确认</el-button>
        <el-button type="danger" @click="test()">取得测试数据</el-button>
      </p>

      <el-table :data="paramList" style="width: 100%" size='small' border>

        <el-table-column :label="item.title" :prop="'params.'+item.title" v-for="(item,index) in map" :key="item.title">
        </el-table-column>

        <el-table-column label="价格">
          <template slot-scope="scope">
            <el-input v-model="scope.row.money" placeholder="" :disabled="!scope.row.is_show"></el-input>
          </template>
        </el-table-column>

        <el-table-column label="库存">
          <template slot-scope="scope">
            <el-input v-model="scope.row.stock" placeholder="" :disabled="!scope.row.is_show"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">

            <el-tooltip :content="'显示：' + (scope.row.is_show?'开':'关')" placement="right">
              <el-switch v-model="scope.row.is_show" active-color="#13ce66"></el-switch>
            </el-tooltip>

          </template>
        </el-table-column>

      </el-table>
    </div>

  </div>
</template>

<script>
export default {
  name: "spec",
  props: {
    value: Object
  },
  data() {
    return {
      //是否是编辑模式
      isEditModel: false,
      //用于添加规格下的属性
      addProp: {
        title: ""
      },
      //规格组
      spec: [],
      //价格&库存的列表
      paramList: [],
      //表格map
      map: [],
      //批量填充的变量记录
      filling: {
        money: "",
        stock: ""
      }
    };
  },
  methods: {
    //初始化规格列表
    initSpec() {
      var list = [];
      //模拟规格
      //颜色：
      let group = {
        spec_id: Math.random(),
        title: `颜色`,
        //是否为编辑状态
        isEdit: false,
        //是否为添加状态
        isAddModel: false,
        //子节点，规格项
        node: [
          //子节点1，title是要显示的标题，并且暂时作为value使用
          { title: `红色`, check: false },
          { title: `白色`, check: false },
          { title: `黑色`, check: false }
        ],
        //消息提示
        message: {
          message: "",
          type: "",
          isShow: false
        }
      };

      list.push(group);

      //再添加一个测试的

      group = {
        spec_id: Math.random(),
        title: `大小`,
        //是否为编辑状态
        isEdit: false,
        //是否为添加状态
        isAddModel: false,
        //子节点，规格项
        node: [
          //子节点1，title是要显示的标题，并且暂时作为value使用
          { title: `10*10`, check: false },
          { title: `20*20`, check: false },
          { title: `30*30`, check: false }
        ],
        //消息提示
        message: {
          message: "",
          type: "",
          isShow: false
        }
      };

      list.push(group);
      //再添加一个测试的
      group = {
        spec_id: Math.random(),
        title: `材质`,
        //是否为编辑状态
        isEdit: false,
        //是否为添加状态
        isAddModel: false,
        //子节点，规格项
        node: [
          //子节点1，title是要显示的标题，并且暂时作为value使用
          { title: `金子`, check: false },
          { title: `铁`, check: false },
          { title: `石头`, check: false }
        ],
        //消息提示
        message: {
          message: "",
          type: "",
          isShow: false
        }
      };

      list.push(group);

      this.spec = list;

      // for (let i = 0; i < 3; i++) {
      //   let group = {
      //     spec_id: Math.random(),
      //     title: `规格${i + 1}`,
      //     isEdit: false,
      //     isAddModel: false,
      //     node: [],
      //     message: {
      //       message: "",
      //       type: "",
      //       isShow: false
      //     }
      //   };
      //   list.push(group);

      //   for (let j = 0; j < 5; j++) {
      //     let item = {
      //       title: `密集补水（6片*3盒+好友共享装）${j + 1}`,
      //       check: false
      //     };
      //     group.node.push(item);
      //   }
      // }
    },
    //移除规格组
    removeSpecGroup(group, index, spec) {
      // 确定删除规格：125，及其所属的规格吗？
      if (group.isAddModel) {
        spec.splice(index, 1);
        this.isEditModel = false;
        return;
      }
      this.$confirm(
        "提示：删除后，正在使用相应规格的其他商品被编辑时，商品的规格内容将受到影响， 是否继续?",
        `确定删除规格：${group.title}，及其所属的规格吗？`,
        { confirmButtonText: "确定", cancelButtonText: "取消", type: "warning" }
      )
        .then(() => {
          this.$message({ type: "success", message: "删除成功!" });
          spec.splice(index, 1);
        })
        .catch(() => {
          this.$message({ type: "info", message: "已取消删除" });
        });

      this.isEditModel = false;
    },
    //新增规格组
    addSpecGroup() {
      var group = {
        title: ``,
        isEdit: false,
        isAddModel: true,
        node: [],
        message: {
          message: "",
          type: "",
          isShow: false
        }
      };
      this.spec.push(group);
      this.editSpecGroup(group);
    },
    //编辑规格组
    editSpecGroup(group, index) {
      this.isEditModel = true;
      group.isEdit = true;
    },
    //全选规格组下的属性
    selectSpecGroup(list, group) {
      this.isSelectAllSpec = !this.isSelectAllSpec;
      for (let i = 0; i < list.length; i++) {
        list[i].check = this.isSelectAllSpec;
      }
      this.changeSpecProp();
    },
    //删除规格下属性
    delSpecProp(item, jndex, group, index) {
      group.splice(jndex, 1);
    },
    //添加规格下的属性
    addSpecProp(groupList, group) {
      var prop = {
        title: this.addProp.title,
        check: false
      };
      //先检查是否重名
      var length = this.$getTextCount(prop.title);
      if (length <= 0 || length > 15) {
        group.message = {
          type: "error",
          message: `请输入规格值，长度不要超过15个字`,
          isShow: true
        };
        return;
      }

      for (let i = 0; i < groupList.length; i++) {
        var item = groupList[i];
        if (item.title == prop.title) {
          group.message = {
            type: "error",
            message: `输入的规格值已存在`,
            isShow: true
          };
          return;
        }
      }

      groupList.push(prop);
      this.addProp = {
        title: ""
      };
      group.message.isShow = false;
    },
    //确定编辑规格组
    okEditSpecGroup(group, index) {
      var length = this.$getTextCount(group.title);
      if (length <= 0 || length > 5) {
        group.message = {
          type: "error",
          message: `请输入规格名称，长度不要超过5个字`,
          isShow: true
        };
        return;
      }

      this.isEditModel = false;
      group.isEdit = false;
    },
    test() {
      console.log(this.paramList);
      console.log(this.spec);
      
    },
    //批量填充
    batchFilling() {
      for (let i = 0; i < this.paramList.length; i++) {
        this.paramList[i].money = this.filling.money;
        this.paramList[i].stock = this.filling.stock;
      }
      this.filling.money = "";
      this.filling.stock = "";
    },
    // 选中一个规格下的属性触发的事件
    changeSpecProp() {
      var rootNode = new Node({
        name: "root"
      });
      var spec = this.spec;

      //组成树
      for (let i = 0; i < spec.length; i++) {
        let specGroup = spec[i];
        let groupNode = new Node({
          name: "group"
        });
        for (let j = 0; j < specGroup.node.length; j++) {
          let specItem = specGroup.node[j];

          if (specItem.check) {
            groupNode.add(
              new Node({
                name: specItem.title,
                prop: specItem,
                group: specGroup
              })
            );
          }
        }
        if (groupNode.size() > 0) {
          rootNode.add(groupNode);
        }
      }
      let _node;
      if (rootNode.size() > 0) {
        _node = rootNode.get(0);

        for (let i = rootNode.size() - 1; i >= 1; i--) {
          let leftNode = rootNode.get(i - 1);
          for (let j = 0; j < leftNode.size(); j++) {
            let rightNode = rootNode.get(i);
            for (let l = 0; l < rightNode.size(); l++) {
              leftNode.get(j).add(rightNode.get(l));
            }
          }
        }
      } else {
        return;
      }

      var arr = dfs(_node);
      var paramList = [];
      var map = [];
      for (let i = 0; i < arr.length; i++) {
        var obj = {
          params: {}
        };

        for (let j = 0; j < arr[i].length; j++) {
          let group = arr[i][j].group;
          obj.params[group.title] = arr[i][j].name;

          if (map.indexOf(group) == -1) {
            map.push(group);
          }
        }
        obj.money = 0;
        obj.stock = 0;
        obj.is_show = true;
        paramList.push(obj);
      }

      this.paramList = paramList;
      this.map = map;
      this.$emit("input", {
        paramList: paramList,
        spec: spec,
        map: map
      });
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.paramList = this.value.paramList;
      if (this.value.spec) {
        this.spec = this.value.spec;
      } else {
        this.initSpec();
      }
      this.map = this.value.map;
    });
  }
};

function dfs(tree) {
  var result = [];
  _search(tree, []);
  return result;

  function _search(nodes, path) {
    if (!nodes || nodes.size() <= 0) {
      return result.push(path.slice());
    }

    for (let i = 0; i < nodes.size(); i++) {
      let node = nodes.get(i);
      path.push(node);
      _search(node, path);
      path.pop();
    }
  }
}

var Node = function(conf) {
  this.praentNode = null;
  this.children = [];
  this.name = "";
  if (conf != null) {
    for (var x in conf) {
      this[x] = conf[x];
    }
  }
  this.add = function(node) {
    this.children.push(node);
    node.praentNode = this;
  };
  this.size = function() {
    return this.children.length;
  };
  this.get = function(index) {
    if (index === undefined || index < 0) {
      return this.children;
    }
    return this.children[index];
  };
};
</script>

<style lang="scss" scoped>
@import "spec.scss";
</style>