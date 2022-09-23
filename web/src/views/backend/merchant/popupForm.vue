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
                <FormItem :label="t('merchant.name')" type="string" v-model="baTable.form.items!.name" prop="name" :input-attr="{ placeholder: t('Please input field', { field: t('merchant.name') }) }" />
                <FormItem :label="t('merchant.image')" type="image" v-model="baTable.form.items!.image" prop="image" />
                <FormItem :label="t('merchant.url')" type="string" v-model="baTable.form.items!.url" prop="url" :input-attr="{ placeholder: t('Please input field', { field: t('merchant.url') }) }" />
                <FormItem :label="t('merchant.key')" type="string" v-model="baTable.form.items!.key" prop="key" :input-attr="{ placeholder: t('Please input field', { field: t('merchant.key') }) }" />
                <FormItem :label="t('merchant.api_url')" type="string" v-model="baTable.form.items!.api_url" prop="api_url" :input-attr="{ placeholder: t('Please input field', { field: t('merchant.api_url') }) }" />
                <FormItem :label="t('merchant.score')" type="number" prop="score" v-model.number="baTable.form.items!.score" :input-attr="{ step: '0.01', placeholder: t('Please input field', { field: t('merchant.score') }) }" />
                <FormItem :label="t('merchant.charges')" type="number" prop="charges" v-model.number="baTable.form.items!.charges" :input-attr="{ step: '0.01', placeholder: t('Please input field', { field: t('merchant.charges') }) }" />
                <FormItem :label="t('merchant.status')" type="radio" v-model="baTable.form.items!.status" prop="status" :data="{ content: { 1: t('merchant.status 1'), 2: t('merchant.status 2'), 3: t('merchant.status 3') } }" :input-attr="{ placeholder: t('Please select field', { field: t('merchant.status') }) }" />
                <FormItem :label="t('merchant.weigh')" type="number" prop="weigh" v-model.number="baTable.form.items!.weigh" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('merchant.weigh') }) }" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
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


const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    name: [buildValidatorData('required', t('merchant.name'))],
    url: [buildValidatorData('required', t('merchant.url'))],
    status: [buildValidatorData('required', t('merchant.status'))],
})

</script>

<style scoped lang="scss"></style>
