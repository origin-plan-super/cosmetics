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

          <label class="spec-prop-item" v-for="(item,jndex) in group.node" :key="jndex" v-if="!group.isEdit">
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
      </p>

      <el-table :data="sku" style="width: 100%" size='small' border>

        <el-table-column :label="item.k" :prop="'s'+(index+1)" v-for="(item,index) in tree" :key="item.k">

        </el-table-column>

        <el-table-column label="价格">
          <template slot-scope="scope">
            <el-input v-model="scope.row.price"></el-input>
          </template>
        </el-table-column>

        <el-table-column label="库存">
          <template slot-scope="scope">
            <el-input v-model="scope.row.stock_num"></el-input>
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
    tree: {
      type: Array,
      default: []
    },
    sku: {
      type: Array,
      default: []
    }
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
    //批量填充
    batchFilling() {
      for (let i = 0; i < this.sku.length; i++) {
        this.sku[i].price = this.filling.money;
        this.sku[i].stock_num = this.filling.stock;
      }
      this.filling.money = "";
      this.filling.stock = "";
    },
    // 选中一个规格下的属性触发的事件
    changeSpecProp() {
      var spec = this.spec;

      var tree = [];

      for (let i = 0; i < spec.length; i++) {
        let specItem = spec[i];
        let isAdd = false;
        let treeItem = {
          k: specItem.title,
          v: [],
          k_s: "s" + (i + 1)
        };

        for (let j = 0; j < specItem.node.length; j++) {
          let nodeItem = specItem.node[j];
          if (nodeItem.check) {
            isAdd = true;
            treeItem.v.push({
              id: nodeItem.title,
              name: nodeItem.title
            });
          }
        }
        if (isAdd) {
          if (tree.length + 1 <= 3) {
            tree.push(treeItem);
          } else {
            this.$info("只能添加三组！");
          }
        }
      }

      if (tree.length <= 0) {
        this.$emit("update:tree", []);
        this.$emit("update:sku", []);
        return;
      }

      var test = generateGroup(tree);
      var skus = [];
      for (let i = 0; i < test.length; i++) {
        var item = test[i];
        var sku = {
          id: i,
          price: 0,
          stock_num: 0
        };
        for (let j = 0; j < item.length; j++) {
          const s = item[j];
          sku["s" + (j + 1)] = s;
        }

        skus.push(sku);
      }

      this.$emit("update:tree", tree);
      this.$emit("update:sku", skus);
    }
  },
  mounted() {
    this.initSpec();
    this.$nextTick(() => {});
  },
  watch:{
    tree(tree){


      var spec= this.spec;
      
      for (let i = 0; i < tree.length; i++) {
        let item = tree[i];
        for (let j = 0; j < spec.length; j++) {
          let group = spec[j];
          if(item.k==group.title){

            for (let x = 0; x < item.v.length; x++) {
              let v = item.v[x];
                      
          for (let l = 0; l < group.node.length; l++) {
            let node = group.node[l];
if(v.name==node.title){

if(!node.check){
node.check=true;
}
}

          }
            }
      
          }
        }
      }

      
      
    }
  }
};

function generateGroup(arr) {
  //初始化结果为第一个数组
  var result = new Array();
  //字符串形式填充数组
  for (var i = 0; i < arr[0].v.length; i++) {
    result.push(JSON.stringify(arr[0].v[i].id));
  }

  //从下标1开始遍历二维数组

  for (var i = 1; i < arr.length; i++) {
    //使用临时遍历替代结果数组长度(这样做是为了避免下面的循环陷入死循环)
    var size = result.length;
    //根据结果数组的长度进行循环次数,这个数组有多少个成员就要和下一个数组进行组合多少次
    for (var j = 0; j < size; j++) {
      //遍历要进行组合的数组
      for (var k = 0; k < arr[i].v.length; k++) {
        //把组合的字符串放入到结果数组最后一个成员中
        //这里使用下标0是因为当这个下标0组合完毕之后就没有用了，在下面我们要移除掉这个成员
        //组合下一个json字符串
        var temp = result[0] + "," + JSON.stringify(arr[i].v[k].id);
        result.push(temp);
      }
      //当第一个成员组合完毕，删除这第一个成员
      result.shift();
    }
  }

  //转换字符串为json对象
  for (var i = 0; i < result.length; i++) {
    result[i] = JSON.parse("[" + result[i] + "]");
  }
  //打印结果
  return result;
}
</script>

<style lang="scss" scoped>
@import "spec.scss";
</style>