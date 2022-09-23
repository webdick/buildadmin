<template>
    <!-- 对话框表单 -->
    <el-dialog
        custom-class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.operate ? true : false"
        @close="baTable.toggleForm"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                <FormItem :label="t('userRealname.name')" type="string" v-model="baTable.form.items!.name" prop="name" :input-attr="{ placeholder: t('Please input field', { field: t('userRealname.name') }) }" />
                <FormItem :label="t('userRealname.idcard')" type="string" v-model="baTable.form.items!.idcard" prop="idcard" :input-attr="{ placeholder: t('Please input field', { field: t('userRealname.idcard') }) }" />
                <FormItem :label="t('userRealname.status')" type="radio" v-model="baTable.form.items!.status" prop="status" :data="{ content: { 1: t('userRealname.status 1'), 2: t('userRealname.status 2'), 3: t('userRealname.status 3') } }" :input-attr="{ placeholder: t('Please select field', { field: t('userRealname.status') }) }" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
                <el-button @click="cardIdVerify" type="primary">腾讯二要素验证</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import type { ElForm, FormItemRule } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'
import createAxios from '/@/utils/axios'
import {ElNotification} from "element-plus";

const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    name: [buildValidatorData('required', t('userRealname.name'))],
    idcard: [buildValidatorData('required', t('userRealname.idcard'))],
    status: [buildValidatorData('required', t('userRealname.status'))],
})

const cardIdVerify = ()=>{
    if(!baTable.form.items!.name||!baTable.form.items!.idcard){
        ElNotification({
            type:'error',
            message:'请先完善二要素信息'
        })
    }
    createAxios({
        url: '/index.php/admin/userRealname/idcardCheck',
        method: 'post',
        data:{
            idcard:baTable.form.items!.idcard,
            name:baTable.form.items!.name
        }
    },{loading:true},{
        text:'云端验证中'
    }).then((res)=>{
        if(res.code===1){
            baTable.form.items!.status = '2';
            ElNotification({
                type:'success',
                message:res.msg
            })
            baTable.onSubmit()
        }else{
            ElNotification({
                type:'error',
                message:res.msg
            })
        }
    });
}
</script>

<style scoped lang="scss"></style>
