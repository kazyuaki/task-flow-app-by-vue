<!-- components/tasks/TaskDetailCard.vue-->
<!-- タスク詳細カードコンポーネント -->

<script setup lang="ts">
import { computed, ref } from "vue";
import { PRIORITY_CLASS_MAP, STATUS_CLASS_MAP } from "~/constants/task";
import type { TaskDetail } from "~/types/task";

const props = defineProps<{
  task: TaskDetail;
  isDeleting: boolean;
}>();

const emit = defineEmits<{
  delete: [taskId: number];
}>();

const handleDeleteClick = () => {
  emit("delete", props.task.id);
};

// --- クラス名の算出 ---
const statusClass = computed(() => STATUS_CLASS_MAP[props.task.status] || "");

// 優先度はnullやundefinedの場合もあるため、空文字をデフォルトにする
const priorityClass = computed(
  () => PRIORITY_CLASS_MAP[props.task.priority] || "",
);

// --- チェックリストのローカル状態 ---
const localChecklist = ref([...props.task.checklist]);

// チェックリストの完了状態をトグルする関数
const toggleChecklist = (id: number) => {
  const item = localChecklist.value.find((item) => item.id === id);
  if (!item) return;

  item.done = !item.done;
  console.log("チェックリストの状態が変更されました:", localChecklist.value);
};

// --- チェックリストの進捗計算 ---
const checklistProgress = computed(() => {
  const done = localChecklist.value.filter((item) => item.done).length;

  return {
    done,
    total: localChecklist.value.length,
  };
});
</script>

<template>
  <article class="detail-panel">
    <header class="detail-header">
      <p class="detail-id">Task #{{ task.id }}</p>
      <h2>{{ task.title }}</h2>

      <ul class="detail-badges" aria-label="タスク属性">
        <li :class="['status-badge', statusClass]">{{ task.status }}</li>
        <li :class="['priority-badge', priorityClass]">
          優先度 {{ task.priority }}
        </li>
      </ul>
    </header>

    <section class="detail-section" aria-labelledby="detail-descritption">
      <h3 id="detail-description">説明</h3>
      <p>{{ task.description }}</p>
    </section>

    <dl class="detail-list">
      <!-- <dt>担当者</dt>
      <dd>{{ task.assignee }}</dd> -->

      <dt>カテゴリ</dt>
      <dd>{{ task.category }}</dd>

      <dt>期限</dt>
      <dd>{{ task.dueDate }}</dd>

      <dt>作成日</dt>
      <dd>{{ task.createdAt }}</dd>

      <dt>更新日</dt>
      <dd>{{ task.updatedAt }}</dd>
    </dl>

    <section class="detail-section" aria-labelledby="detail-description">
      <header class="checklist-header">
        <h3 id="task-checklist">チェックリスト</h3>
        <p>{{ checklistProgress.done }} / {{ checklistProgress.total }} 完了</p>
      </header>

      <ul class="checklist">
        <li
          v-for="item in localChecklist"
          :key="item.id"
          :class="{ 'checklist-item--done': item.done }"
        >
          <label class="checklist-label">
            <input
              type="checkbox"
              :checked="item.done"
              class="checklist-input"
              @change="toggleChecklist(item.id)"
            />
            <span class="checkmark" aria-hidden="true">
              {{ item.done ? "✔︎" : "" }}
            </span>
            <span>{{ item.label }}</span>
          </label>
        </li>
      </ul>
    </section>

    <footer class="detail-actions">
      <NuxtLink class="secondary-link" :to="`/tasks/${task.id}/edit`">
        編集
      </NuxtLink>

      <button
        class="danger-button"
        type="button"
        :disabled="isDeleting"
        @click="handleDeleteClick"
      >
        {{ isDeleting ? "削除中..." : "削除" }}
      </button>
    </footer>
  </article>
</template>

<style scoped>
.detail-panel {
  display: grid;
  gap: 24px;
  padding: 24px;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
}

.detail-header {
  display: grid;
  gap: 12px;
  padding-bottom: 20px;
  border-bottom: 1px solid #eaecf0;
}

.detail-id {
  margin: 0;
  color: #2d6a4f;
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
}

.detail-header h2 {
  margin: 0;
  color: #172033;
  font-size: 28px;
  line-height: 1.35;
}

.detail-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin: 0;
  padding: 0;
  list-style: none;
}

.status-badge,
.priority-badge {
  display: inline-flex;
  align-items: center;
  min-height: 28px;
  padding: 0 10px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 800;
}

.status--not-started {
  color: #344054;
  background: #f2f4f7;
}

.status--in-progress {
  color: #175cd3;
  background: #eff8ff;
}

.status--completed {
  color: #027a48;
  background: #ecfdf3;
}

.priority--high {
  color: #b42318;
  background: #fffbfa;
}

.priority--medium {
  color: #b54708;
  background: #fffaeb;
}

.priority--low {
  color: #027a48;
  background: #ecfdf3;
}

.detail-section {
  display: grid;
  gap: 10px;
}

.detail-section h3 {
  margin: 0;
  color: #172033;
  font-size: 16px;
}

.detail-section p {
  margin: 0;
  color: #667085;
  font-size: 15px;
  line-height: 1.7;
}

.detail-list {
  display: inline-grid;
  grid-template-columns: auto auto;
  gap: 12px 20px;
  justify-self: start;
  margin: 0;
  padding: 18px 0 0;
  border-top: 1px solid #eaecf0;
}

.detail-list dt {
  color: #667085;
  font-size: 14px;
  font-weight: 700;
}

.detail-list dd {
  margin: 0;
  color: #172033;
  font-size: 14px;
  font-weight: 800;
}

.checklist-section {
  display: grid;
  gap: 14px;
  padding-top: 20px;
  border-top: 1px solid #eaecf0;
}

.checklist-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.checklist-header h3 {
  margin: 0;
  color: #172033;
  font-size: 16px;
}

.checklist-header p {
  margin: 0;
  color: #2d6a4f;
  font-size: 14px;
  font-weight: 800;
}

.checklist {
  display: grid;
  gap: 10px;
  margin: 0;
  padding: 0;
  list-style: none;
}

.checklist li {
  display: flex;
  align-items: center;
  gap: 10px;
  min-height: 42px;
  padding: 10px 12px;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  color: #172033;
  font-size: 15px;
  font-weight: 700;
  background: #fff;
}

.checklist-item--done {
  color: #667085;
  background: #f8fbfa;
}

.checklist-label {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
  cursor: pointer;
}

.checklist-input {
  display: none;
}

.checkmark {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex: 0 0 22px;
  width: 22px;
  height: 22px;
  border: 1px solid #98a2b3;
  border-radius: 999px;
  color: #fff;
  font-size: 13px;
  font-weight: 900;
}

.checklist-item--done .checkmark {
  border-color: #2d6a4f;
  background: #2d6a4f;
}

.detail-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding-top: 4px;
}

.secondary-link,
.primary-link {
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

.danger-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 42px;
  padding: 0 16px;
  border: none;
  border-radius: 8px;
  color: #fff;
  font-size: 15px;
  font-weight: 800;
  background: #b42318;
  cursor: pointer;
  transition:
    background 0.2s ease,
    border-color 0.2s ease,
    transform 0.2s ease;
}

.danger-button:hover {
  background: #912018;
  border-color: #f0a39b;
  transform: translateY(-1px);
}

.danger-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

@media (max-width: 760px) {
  .detail-header h2 {
    font-size: 22px;
  }

  .detail-actions {
    align-items: stretch;
    flex-direction: column;
  }

  .secondary-link,
  .danger-button {
    width: 100%;
    box-sizing: border-box;
  }
}
</style>
