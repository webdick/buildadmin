<template>
    <div class="flex">
        <el-tag v-if="props.renderRow.realname===null" type="warning">未实名</el-tag>
        <el-tag v-else-if="props.renderRow.realname.status==='2'">{{props.renderRow.realname.realname_status}}</el-tag>
        <el-tag v-else type="warning">{{props.renderRow.realname.realname_status}}</el-tag>
        <el-tooltip
            content="实名信息"
            placement="top"
        >
            <el-button
                v-blur type="primary"
                @click="ButtonClick" size="small"
                class="table-operate btn ml-1"
            >
                <Icon name="fa fa-user-circle" size="16" color="#fff"/>
            </el-button>
        </el-tooltip>
    </div>
</template>

<script setup lang="ts">
// BuildAdmin将自动为此组件传递三个Props
import router from "/@/router";
import {reactive} from "vue";
import {add} from "/@/api/backend/user/scoreLog";

interface Props {
    renderValue: any // 单元格值
    renderRow: TableRow // 当前行数据
    renderField: TableColumn // 当前列数据
}
const props = defineProps<Props>()
const state: {
    userInfo: anyObj
    after: number
} = reactive({
    userInfo: {},
    after: 0,
})

const ButtonClick = ()=>{
    console.log(props.renderRow.id)
    if (!props.renderRow.id || parseInt(props.renderRow.id) <= 0) {
        return
    }
    add(props.renderRow.id).then((res) => {
        state.userInfo = res.data.user
    })
    router.push({
        name:'userRealname',
        query: {
            uid: props.renderRow.id,
        },
    })
}

</script>

<style scoped lang="scss">
.btn{
    padding: 4px 5px;
    height: auto;
}
.flex{
    display: flex;
    justify-content: center;
    align-items: center;
}
.ml-1{
    margin-left: 5px;
}
</style>
