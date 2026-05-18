<!-- components/tasks/TaskCreateFormPanel.vue -->
<!-- タスク作成フォームの入力レイアウト -->
<script setup lang="ts">
import BaseSelectField from "~/components/common/BaseSelectField.vue";
import BaseTextField from "~/components/common/BaseTextField.vue";
import BaseTextareaField from "~/components/common/BaseTextareaField.vue";
import TaskCreateChecklistFields from "~/components/tasks/TaskCreateChecklistFields.vue";
import type {
  TaskCategory,
  TaskCreateForm,
  TaskPriority,
  TaskStatus,
} from "~/types/task";

defineProps<{
  form: TaskCreateForm;
  statuses: readonly TaskStatus[];
  priorities: readonly TaskPriority[];
  categories: readonly TaskCategory[];
  errors: Record<string, string[]>;
  touched: Record<string, boolean>;
  submitted: boolean;
  generalError: string;
  isSubmitting: boolean;
  canSubmit: boolean;
  dueDateMin: string;
}>();

const emit = defineEmits<{
  (e: "submit"): void;
  (e: "clear-field", field: string): void;
  (e: "touch-field", field: string): void;
  (e: "add-checklist-item"): void;
  (e: "remove-checklist-item", index: number): void;
}>();
</script>

<template>
  <form class="form-panel" @submit.prevent="emit('submit')">
    <p v-if="generalError" class="form-alert">
      {{ generalError }}
    </p>

    <section class="form-section">
      <h2>基本情報</h2>

      <BaseTextField
        v-model="form.title"
        label="タイトル"
        placeholder="タスクのタイトルを入力"
        :errors="touched.title || submitted ? errors.title : []"
        @input="emit('clear-field', 'title')"
        @blur="emit('touch-field', 'title')"
      />

      <BaseTextareaField
        v-model="form.description"
        label="説明"
        placeholder="作業内容や完了条件を入力"
        :rows="5"
        :maxlength="1000"
        :errors="touched.description || submitted ? errors.description : []"
        @input="emit('clear-field', 'description')"
        @blur="emit('touch-field', 'description')"
      />
    </section>

    <section class="form-section">
      <h2>管理情報</h2>

      <fieldset class="form-grid">
        <BaseSelectField
          v-model="form.status"
          label="ステータス"
          :options="statuses"
          :errors="submitted ? errors.status : []"
          @change="emit('clear-field', 'status')"
        />

        <BaseSelectField
          v-model="form.priority"
          label="優先度"
          :options="priorities"
          :errors="submitted ? errors.priority : []"
          @change="emit('clear-field', 'priority')"
        />

        <BaseSelectField
          v-model="form.category"
          label="カテゴリ"
          :options="categories"
          :errors="submitted ? errors.category : []"
          @change="emit('clear-field', 'category')"
        />

        <BaseTextField
          v-model="form.dueDate"
          label="期限"
          type="date"
          :min="dueDateMin"
          :errors="touched.dueDate || submitted ? errors.dueDate : []"
          @input="emit('clear-field', 'dueDate')"
          @blur="emit('touch-field', 'dueDate')"
        />
      </fieldset>
    </section>

    <TaskCreateChecklistFields
      :checklist="form.checklist"
      :errors="errors"
      :touched="touched"
      :submitted="submitted"
      @add-item="emit('add-checklist-item')"
      @remove-item="emit('remove-checklist-item', $event)"
      @clear-field="emit('clear-field', $event)"
      @touch-field="emit('touch-field', $event)"
    />

    <footer class="form-actions">
      <NuxtLink class="cancel-link" to="/tasks">キャンセル</NuxtLink>
      <button class="submit-button" type="submit" :disabled="!canSubmit">
        {{ isSubmitting ? "作成中..." : "作成する" }}
      </button>
    </footer>
  </form>
</template>

<style scoped>
.form-panel {
  box-sizing: border-box;
  display: grid;
  gap: 24px;
  width: 100%;
  min-width: 0;
  padding: 24px;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
}

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

.form-grid {
  box-sizing: border-box;
  display: grid;
  grid-template-columns: repeat(2, minmax(180px, 1fr));
  gap: 16px;
  min-width: 0;
  margin: 0;
  padding: 0;
  border: 0;
}

.form-alert {
  margin: 0;
  padding: 12px 14px;
  border: 1px solid #fda29b;
  border-radius: 6px;
  color: #b42318;
  font-size: 14px;
  font-weight: 700;
  background: #fffbfa;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding-top: 8px;
}

.cancel-link,
.submit-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 42px;
  padding: 0 16px;
  border-radius: 8px;
  font: inherit;
  font-weight: 700;
}

.cancel-link {
  border: 1px solid #d0d5dd;
  color: #344054;
  text-decoration: none;
  background: #fff;
}

.submit-button {
  border: 0;
  color: #fff;
  background: #2d6a4f;
  box-shadow: 0 8px 18px rgba(45, 106, 79, 0.22);
  cursor: pointer;
  transition:
    transform 0.2s ease,
    background 0.2s ease,
    box-shadow 0.2s ease;
}

.submit-button:hover:not(:disabled) {
  background: #24563f;
  transform: translateY(-1px);
  box-shadow: 0 10px 22px rgba(45, 106, 79, 0.28);
}

.submit-button:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

@media (max-width: 860px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}
</style>
