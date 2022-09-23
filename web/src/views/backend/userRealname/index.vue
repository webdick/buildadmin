<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('userRealname.quick Search Fields') })"
            @action="baTable.onTableHeaderAction"
        >
            <el-button v-if="!_.isEmpty(state.userInfo)" v-blur class="table-header-operate" style="margin-left: 10px;">
                <span class="table-header-operate-text">{{
                        state.userInfo.nickname + '(ID:' + state.userInfo.id + ') '
                    }}</span>
            </el-button>
        </TableHeader>

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" @action="baTable.onTableAction" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import {ref, provide, onMounted, reactive, watch} from 'vue'
import baTableClass from '/@/utils/baTable'
import { userRealname } from '/@/api/controllerUrls'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'
import {useRoute} from "vue-router";
import _ from "lodash";
import {add} from "/@/api/backend/user/scoreLog";

const { t } = useI18n()
const tableRef = ref()
const route = useRoute()
const defalutUser = (route.query.uid ?? '') as string
const state: {
    userInfo: anyObj
} = reactive({
    userInfo: {},
})

const optButtons = defaultOptButtons(["edit","delete"])
const baTable = new baTableClass(
    new baTableApi(userRealname),
    {
        pk: 'uid',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('userRealname.uid'), prop: 'uid', align: 'center', operator: '=' },
            { label: t('userRealname.name'), prop: 'name', align: 'center' },
            { label: t('userRealname.idcard'), prop: 'idcard', align: 'center' },
            { label: t('userRealname.status'), prop: 'status', align: 'center', render: 'tag', replaceValue: { 1: t('userRealname.status 1'), 2: t('userRealname.status 2'), 3: t('userRealname.status 3') } },
            { label: t('userRealname.createtime'), prop: 'createtime', align: 'center', render: 'datetime', sortable: 'custom', operator: 'RANGE', width: 160 },
            { label: t('operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined ],
        defaultOrder: { prop: 'uid', order: 'desc' },
    },
    {
        defaultItems: {uid: defalutUser,},
    },{

    },
    {
        onSubmit: () => {
            getUserInfo(baTable.comSearch.form.uid)
        }
    }
)

provide('baTable', baTable)

const getUserInfo = (userId: string) => {
    if (userId && _.parseInt(userId) > 0) {
        add(userId).then((res) => {
            state.userInfo = res.data.user
        })
    } else {
        state.userInfo = {}
    }
}

getUserInfo(baTable.comSearch.form.uid)


onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'userRealname',
})
</script>

<style scoped lang="scss"></style>
