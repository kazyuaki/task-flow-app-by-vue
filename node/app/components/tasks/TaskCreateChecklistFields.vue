<!-- components/tasks/TaskCreateChecklistFields.vue -->
<!-- タスク作成フォームのチェックリスト入力欄 -->
<script setup lang="ts">
import type { TaskFormChecklistItem } from "~/types/task";

const props = defineProps<{
  checklist: TaskFormChecklistItem[];
  errors: Record<string, string[]>;
  touched: Record<string, boolean>;
  submitted: boolean;
}>();

const emit = defineEmits<{
  (e: "add-item"): void;
  (e: "remove-item", index: number): void;
  (e: "clear-field", field: string): void;
  (e: "touch-field", field: string): void;
}>();

const getFieldErrors = (field: string) => {
  if (!props.touched[field] && !props.submitted) return [];

  return props.errors[field] || [];
};
</script>

<template>
  <section class="form-section">
    <header class="section-heading">
      <div class="section-title">
        <h2>チェックリスト</h2>
        <span class="any-badge">任意</span>
      </div>
      <button class="add-button" type="button" @click="emit('add-item')">
        項目を追加
      </button>
    </header>

    <div class="checklist-fields">
      <label
        v-for="(item, index) in checklist"
        :key="index"
        class="checklist-field"
      >
        <span class="checklist-item-title">項目 {{ index + 1 }}</span>

        <div class="checklist-input-row">
          <input
            v-model="item.label"
            type="text"
            placeholder="例：API仕様を確認する"
            :aria-invalid="
              Boolean(getFieldErrors(`checklist.${index}.label`).length)
            "
            @input="emit('clear-field', `checklist.${index}.label`)"
            @blur="emit('touch-field', `checklist.${index}.label`)"
          />

          <button
            class="remove-button"
            type="button"
            :disabled="checklist.length === 1"
            @click="emit('remove-item', index)"
          >
            削除
          </button>
        </div>

        <small
          v-for="message in getFieldErrors(`checklist.${index}.label`)"
          :key="message"
          class="field-error"
        >
          {{ message }}
        </small>
      </label>
    </div>
  </section>
</template>

<style scoped>
.form-section {
  display: grid;
  gap: 16px;
  min-width: 0;
}

.form-section h2 {
  margin: 0;
  color: #172033;
  font-size: 16px;
}

.section-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 8px;
}

.any-badge {
  display: inline-block;
  padding: 2px 8px;
  border-radius: 4px;
  background: #e0e7ff;
  color: #3730a3;
  font-size: 12px;
  font-weight: 700;
}

.checklist-fields {
  display: grid;
  gap: 12px;
}

.checklist-field {
  display: grid;
  gap: 10px;
  padding: 14px;
  border: 1px solid #eaecf0;
  border-radius: 10px;
  background: #fff;
  transition:
    border-color 0.2s ease,
    box-shadow 0.2s ease,
    transform 0.2s ease;
}

.checklist-field:focus-within {
  border-color: #2d6a4f;
  box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.08);
}

.checklist-field span {
  color: #475467;
  font-size: 13px;
  font-weight: 700;
}

.checklist-input-row {
  display: flex;
  gap: 10px;
}

.checklist-input-row input {
  flex: 1;
  min-height: 42px;
  padding: 0 12px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  font: inherit;
}

.checklist-input-row input[aria-invalid="true"] {
  border-color: #d92d20;
}

.checklist-input-row input[aria-invalid="true"]:focus {
  border-color: #d92d20;
  outline: 3px solid rgba(217, 45, 32, 0.12);
}

.field-error {
  color: #b42318;
  font-size: 12px;
  font-weight: 700;
  line-height: 1.5;
}

.add-button,
.remove-button {
  min-height: 38px;
  padding: 0 12px;
  border-radius: 8px;
  font: inherit;
  font-weight: 700;
  cursor: pointer;
}

.add-button {
  border: 1px solid #2d6a4f;
  color: #2d6a4f;
  background: #fff;
}

.remove-button {
  border: 1px solid #d0d5dd;
  color: #344054;
  background: #fff;
}

.remove-button:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}
</style>
