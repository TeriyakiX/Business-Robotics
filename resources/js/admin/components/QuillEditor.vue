<template>
    <div class="quill-editor-wrapper">
            <QuillEditor
                v-model:content="content"
                :options="editorOptions"
                content-type="html"
                @update:content="onUpdate"
                :class="{ 'ql-focused': isFocused }"
            />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { QuillEditor, Quill } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

// Регистрируем модуль для изображений с кастомной обработкой
const ImageBlot = Quill.import('formats/image')
ImageBlot.tagName = 'img'
Quill.register(ImageBlot)

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue'])
const content = ref(props.modelValue)
const isFocused = ref(false)

const editorOptions = {
    modules: {
        toolbar: {
            container: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['clean']
            ],
            handlers: {
                image: imageHandler
            }
        },
        clipboard: {
            matchVisual: false
        }
    },
    placeholder: 'Напишите содержание статьи...',
    theme: 'snow'
}

function imageHandler() {
    const input = document.createElement('input')
    input.setAttribute('type', 'file')
    input.setAttribute('accept', 'image/*')
    input.click()

    input.onchange = async () => {
        const file = input.files[0]
        const fileType = file.type

        if (fileType.indexOf('image') < 0) {
            alert('Пожалуйста, выберите изображение')
            return
        }

        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = () => {
            const range = this.quill.getSelection()
            const url = reader.result
            this.quill.insertEmbed(range.index, 'image', url)
        }
    }
}

function onUpdate(value) {
    emit('update:modelValue', value)
}

watch(() => props.modelValue, (newVal) => {
    if (newVal !== content.value) {
        content.value = newVal
    }
})
</script>

<style scoped>
.quill-editor-wrapper {
    background: #283D55;
    border-radius: 12px;
    overflow: hidden;
}

.quill-editor-wrapper :deep(.ql-toolbar) {
    background: rgba(0, 0, 0, 0.2);
    border: none;
    border-bottom: 1px solid rgba(0, 180, 230, 0.22);
    padding: 12px;
}

.quill-editor-wrapper :deep(.ql-toolbar .ql-stroke) {
    stroke: #94B4CC;
}

.quill-editor-wrapper :deep(.ql-toolbar .ql-fill) {
    fill: #94B4CC;
}

.quill-editor-wrapper :deep(.ql-toolbar .ql-picker) {
    color: #94B4CC;
}

.quill-editor-wrapper :deep(.ql-toolbar .ql-picker-options) {
    background: #283D55;
    border-color: rgba(0, 180, 230, 0.22);
}

.quill-editor-wrapper :deep(.ql-container) {
    background: #283D55;
    border: none;
    font-size: 14px;
    line-height: 1.6;
}

.quill-editor-wrapper :deep(.ql-editor) {
    color: #E8F0F8;
    min-height: 400px;
}

.quill-editor-wrapper :deep(.ql-editor.ql-blank::before) {
    color: #5A7A95;
    font-style: normal;
}

.quill-editor-wrapper :deep(.ql-editor h1),
.quill-editor-wrapper :deep(.ql-editor h2),
.quill-editor-wrapper :deep(.ql-editor h3) {
    color: #E8F0F8;
}

.quill-editor-wrapper :deep(.ql-editor a) {
    color: #00CFFF;
}

.quill-editor-wrapper :deep(.ql-editor blockquote) {
    border-left: 3px solid #00CFFF;
    color: #94B4CC;
}

.quill-editor-wrapper :deep(.ql-editor img) {
    max-width: 100%;
    border-radius: 12px;
    margin: 10px 0;
}

.quill-editor-wrapper :deep(.ql-editor pre.ql-syntax) {
    background: #1a2a3a;
    color: #E8F0F8;
    border-radius: 8px;
}
</style>
