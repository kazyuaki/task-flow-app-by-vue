<!-- components/common/BaseTextField.vue -->
<!-- 汎用的なテキスト入力コンポーネント -->
<script setup lang="ts">
defineProps<{
  modelValue: string;
  label: string;
  type?: "text" | "date";
  placeholder?: string;
  min?: string;
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

    <input
      :value="modelValue"
      :type="type || 'text'"
      :placeholder="placeholder"
      :min="min"
      :maxlength="maxlength"
      :aria-invalid="Boolean(errors?.length)"
      @input="event => {
        emit('update:modelValue', (event.target as HTMLInputElement).value);
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

.form-field input {
  box-sizing: border-box;
  width: 100%;
  min-height: 42px;
  padding: 0 12px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  color: #172033;
  font: inherit;
  background: #fff;
}

.form-field input:focus {
  border-color: #2d6a4f;
  outline: 3px solid rgba(45, 106, 79, 0.12);
}

.form-field input[aria-invalid="true"] {
  border-color: #d92d20;
}

.form-field input[aria-invalid="true"]:focus {
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
