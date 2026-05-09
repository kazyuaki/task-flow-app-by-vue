<!-- components/common/BaseTextareaField.vue -->
<!-- 汎用的な複数行テキスト入力コンポーネント -->
<script setup lang="ts">
defineProps<{
  modelValue: string;
  label: string;
  placeholder?: string;
  rows?: number;
  maxlength?: number;
  errors?: string[];
}>();

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void;
  (e: "input"): void;
  (e: "blur"): void;
}>();
</script>

<template>
  <label class="form-field">
    <span>{{ label }}</span>

    <textarea
      :value="modelValue"
      :rows="rows || 5"
      :maxlength="maxlength"
      :placeholder="placeholder"
      :aria-invalid="Boolean(errors?.length)"
      @input="event => {
        emit('update:modelValue', (event.target as HTMLTextAreaElement).value);
        emit('input');
      }"
      @blur="emit('blur')"
    />

    <small v-for="message in errors" :key="message" class="field-error">
      {{ message }}
    </small>
  </label>
</template>

<style scoped>
.form-field {
  display: grid;
  gap: 8px;
  min-width: 0;
}

.form-field span {
  color: #475467;
  font-size: 13px;
  font-weight: 700;
}

.form-field textarea {
  box-sizing: border-box;
  width: 100%;
  min-height: 42px;
  padding: 12px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  color: #172033;
  font: inherit;
  line-height: 1.6;
  resize: vertical;
  background: #fff;
}

.form-field textarea:focus {
  border-color: #2d6a4f;
  outline: 3px solid rgba(45, 106, 79, 0.12);
}

.form-field textarea[aria-invalid="true"] {
  border-color: #d92d20;
}

.form-field textarea[aria-invalid="true"]:focus {
  border-color: #d92d20;
  outline-color: rgba(217, 45, 32, 0.12);
}

.field-error {
  color: #b42318;
  font-size: 12px;
  font-weight: 700;
  line-height: 1.5;
}
</style>
