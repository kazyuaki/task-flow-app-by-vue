<!-- components/tasks/TaskEditForm.vue -->
<!-- タスク編集フォームコンポーネント -->

<script setup lang="ts">
import { reactive } from "vue";
import type { TaskDetail, UpdateTaskPayload } from "~/types/task";

const props = defineProps<{
  task: TaskDetail;
}>();

const emit = defineEmits<{ submit: [payload: UpdateTaskPayload] }>();

/* フォームの状態をリアクティブに管理 */
const form = reactive({
  title: props.task.title,
  description: props.task.description,
  status: props.task.status,
  priority: props.task.priority,
  assignee: props.task.assignee,
  category: props.task.category,
  dueDate: props.task.dueDateInput,
  checklist: [...props.task.checklist],
});

/* チェックリスト項目の追加 */
const addChecklistItem = () => {
  form.checklist.push({
    id: null,
    tempId: Date.now(),
    label: "",
    done: false,
  });
};
/* チェックリスト項目の削除 */
const removeChecklistItem = (id: number | null, tempId?: number) => {
  form.checklist = form.checklist.filter((item) => {
    if (tempId !== undefined) return item.tempId !== tempId;

    return item.id !== id;
  });
};

/* フォーム送信のハンドラー */
const handleSubmit = () => {
  const payload: UpdateTaskPayload = {
    id: props.task.id,
    categoryId: props.task.categoryId,
    title: form.title,
    description: form.description,
    status: form.status,
    priority: form.priority,
    assignee: form.assignee,
    category: form.category,
    dueDate: form.dueDate,
    checklist: form.checklist.map((item) => ({
      id: item.id,
      label: item.label,
      done: item.done,
    })),
  };

  emit("submit", payload);
  console.log("編集フォーム送信:", form);
};
</script>

<template>
  <form class="edit-panel" @submit.prevent="handleSubmit">
    <header class="edit-header">
      <p class="edit-id">Task #{{ task.id }}</p>
    </header>

    <div class="form-grid">
      <label class="form-field">
        <span>タイトル</span>
        <input v-model="form.title" type="text" />
      </label>

      <label class="form-field form-field--full">
        <span>説明</span>
        <textarea v-model="form.description" rows="5" />
      </label>

      <label class="form-field">
        <span>ステータス</span>
        <select v-model="form.status">
          <option value="未着手">未着手</option>
          <option value="進行中">進行中</option>
          <option value="完了">完了</option>
        </select>
      </label>

      <label class="form-field">
        <span>優先度</span>
        <select v-model="form.priority">
          <option value="高">高</option>
          <option value="中">中</option>
          <option value="低">低</option>
        </select>
      </label>

      <label class="form-field">
        <span>担当者</span>
        <input v-model="form.assignee" type="text" />
      </label>

      <label class="form-field">
        <span>カテゴリ</span>
        <input v-model="form.category" type="text" />
      </label>

      <label class="form-field">
        <span>期限</span>
        <input v-model="form.dueDate" type="date" />
      </label>
    </div>

    <div class="checklist-section">
      <div class="checklist-header">
        <span>チェックリスト</span>

        <button
          type="button"
          class="add-checklist-button"
          @click="addChecklistItem"
        >
          + 項目追加
        </button>
      </div>

      <div
        v-for="item in form.checklist"
        :key="item.tempId ?? item.id ?? undefined"
        class="checklist-row"
      >
        <input
          v-model="item.label"
          type="text"
          class="checklist-text-input"
          placeholder="チェック項目を入力"
        />

        <button
          type="button"
          class="remove-button"
          @click="removeChecklistItem(item.id, item.tempId)"
        >
          削除
        </button>
      </div>
    </div>

    <footer class="edit-actions">
      <NuxtLink class="secondary-link" :to="`/tasks/${task.id}`">
        キャンセル
      </NuxtLink>

      <button class="primary-button" type="submit">更新する</button>
    </footer>
  </form>
</template>

<style scoped>
.edit-panel {
  display: grid;
  gap: 24px;
  padding: 24px;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
}

.edit-header {
  padding-bottom: 20px;
  border-bottom: 1px solid #eaecf0;
}

.edit-id {
  margin: 0;
  color: #2d6a4f;
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
}

/* フォームグリッド */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 18px;
}

.form-field {
  display: grid;
  gap: 8px;
}

.form-field--full {
  grid-column: 1 / -1;
}

.form-field span {
  color: #344054;
  font-size: 14px;
  font-weight: 800;
}

.form-field input,
.form-field textarea,
.form-field select {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #d0d5dd;
  border-radius: 8px;
  padding: 12px 14px;
  color: #172033;
  font-size: 15px;
  background: #fff;
}

.form-field textarea {
  resize: vertical;
  line-height: 1.7;
}

/* チェックリスト */
.checklist-section {
  display: grid;
  gap: 16px;
  padding: 20px;
  border: 1px solid #eaecf0;
  border-radius: 12px;
  background: #f9fafb;
}

.checklist-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.checklist-header span {
  color: #344054;
  font-size: 15px;
  font-weight: 800;
}

.checklist-row {
  display: flex;
  gap: 12px;
}

.checklist-text-input {
  flex: 1;
  border: 1px solid #d0d5dd;
  border-radius: 8px;
  padding: 12px 14px;
  color: #172033;
  font-size: 14px;
  background: #fff;
}

.checklist-text-input:focus {
  outline: none;
  border-color: #2d6a4f;
  box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.12);
}

.add-checklist-button {
  border: none;
  border-radius: 8px;
  padding: 10px 14px;
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  background: #2d6a4f;
  cursor: pointer;
}

.remove-button {
  flex-shrink: 0;
  border: none;
  border-radius: 8px;
  padding: 10px 14px;
  color: #fff;
  font-size: 13px;
  font-weight: 700;
  background: #d92d20;
  cursor: pointer;
  transition: background 0.2s;
}

.remove-button:hover {
  background: #b42318;
}

/* フッターアクション */
.edit-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding-top: 4px;
}

.secondary-link,
.primary-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 42px;
  padding: 0 16px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 800;
  text-decoration: none;
}

.secondary-link {
  border: 1px solid #d0d5dd;
  color: #344054;
  background: #fff;
}

.primary-button {
  border: none;
  color: #fff;
  background: #2d6a4f;
  cursor: pointer;
}

.primary-button:hover {
  background: #24573f;
}

/* レスポンシブ */
@media (max-width: 640px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .checklist-row {
    flex-direction: column;
  }

  .remove-button {
    width: 100%;
  }
}
</style>
