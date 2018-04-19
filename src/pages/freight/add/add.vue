<template>
    <div id="add">
        <el-card shadow="hover">
            <el-form size="mini" :model="data" :rules="rules" ref="form" label-width="180px" class="demo-ruleForm">
                <el-form-item label="模版名称" prop="freight_name" style="max-width:500px">
                    <el-input v-model.trim="data.freight_name"></el-input>
                </el-form-item>

                <el-form-item label="运费计算方式" prop="freight_type" style="max-width:500px">

                    <el-radio-group v-model="data.freight_type">
                        <el-radio :label="1">卖家承担运费</el-radio>
                        <el-radio :label="2">买家承担运费</el-radio>
                           
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="计价方式" prop="value_type" style="max-width:500px">

                    <el-radio-group v-model="data.value_type">
                        <el-radio :label="1">按件数</el-radio>
                        <el-radio :label="2">按重量</el-radio>
                        <el-radio :label="3">按体积</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="设置可配送区域和运费" v-if="data.freight_type==2" prop="freight_type" style="max-width:1300px">
                    <freight-area v-model="data.areas" :value-type="data.value_type" type="edit"></freight-area>
                </el-form-item>

                <el-form-item style="max-width:500px">
                    <el-button type="primary" @click="submitForm">保存</el-button>
                    <el-button @click="resetForm">重置</el-button>
                </el-form-item>
            </el-form>

        </el-card>
        <pre v-html="data"></pre>

    </div>
</template>
<script src="./add.js"></script>
<style lang="scss" scoped>
@import "add.scss";
</style>
