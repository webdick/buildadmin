<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('merchant.quick Search Fields') })"
            @action="baTable.onTableHeaderAction"
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" @action="baTable.onTableAction" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { merchant } from '/@/api/controllerUrls'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(["weigh-sort","edit","delete"])
const baTable = new baTableClass(
    new baTableApi(merchant),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('merchant.id'), prop: 'id', align: 'center', width: 70, sortable: 'custom', operator: 'RANGE' },
            { label: t('merchant.name'), prop: 'name', align: 'center' },
            { label: t('merchant.image'), prop: 'image', align: 'center', render: 'image' },
            { label: t('merchant.url'), prop: 'url', align: 'center' },
            { label: t('merchant.score'), prop: 'score', align: 'center', operator: 'RANGE' },
            { label: t('merchant.charges'), prop: 'charges', align: 'center', operator: 'RANGE',render: 'customTemplate',customTemplate:
                    (row: TableRow, field: TableColumn, value: any)=>{
                        return `<b class="ba-text">${value}%</b>`;
                    }
            },
            { label: t('merchant.status'), prop: 'status', align: 'center', render: 'tag', replaceValue: { 1: t('merchant.status 1'), 2: t('merchant.status 2'), 3: t('merchant.status 3') } },
            { label: t('merchant.createtime'), prop: 'createtime', align: 'center', render: 'datetime', sortable: 'custom', operator: 'RANGE', width: 160 },
            { label: t('operate'), align: 'center', width: 140, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined, ],
        defaultOrder: { prop: 'weigh', order: 'desc' },
    },
    {
        defaultItems: {"score":"0.00","charges":"0.02","status":"3","weigh":"0"},
    }
)

provide('baTable', baTable)

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
    name: 'merchant',
})
</script>

<style scoped lang="scss"></style>
