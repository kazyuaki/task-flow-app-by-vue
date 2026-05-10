<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import {
  PRIORITY_CLASS_MAP,
  PRIORITY_LABELS,
  STATUS_CLASS_MAP,
  STATUS_LABELS,
} from "~/constants/task";

type ApiTaskDetail = {
  id: number;
  user_id: number;
  category_id: number | null;
  title: string;
  description: string | null;
  status: number;
  priority?: number | null;
  due_date?: string | null;
  created_at: string;
  updated_at: string;
};

type DisplayTask = {
  id: number;
  title: string;
  description: string;
  status: string;
  priority: string;
  category: string;
  dueDate: string;
  assignee: string;
  createdAt: string;
  updatedAt: string;
  checklist: {
    label: string;
    done: boolean;
  }[];
};

const route = useRoute();

const taskId = Number(route.params.id);

const config = useRuntimeConfig();

const apiBaseUrl = process.server
  ? "http://nginx"
  : String(config.public.apiBaseUrl);

const {
  data: task,
  pending,
  error,
} = await useFetch<ApiTaskDetail>(`/api/tasks/${taskId}`, {
  baseURL: apiBaseUrl,
});

// --- ヘルパー関数 ---
const formatDate = (date?: string | null) => {
  if (!date) return "未設定";

  return new Date(date).toLocaleDateString("ja-JP");
};

// --- 表示用のデータ整形 ---
const displayTask = computed<DisplayTask | null>(() => {
	if (!task.value) return null;

	return {
		id: task.value.id,
		title: task.value.title,
		description: task.value.description || "説明は未設定です。",
		status: STATUS_LABELS[task.value.status] || "不明",
		priority:
      task.value.priority === null || task.value.priority === undefined
        ? "未設定"
        : PRIORITY_LABELS[task.value.priority] || "不明",
    category: task.value.category_id ? `カテゴリ #${task.value.category_id}` : "未分類",
		dueDate: formatDate(task.value.due_date),
    assignee: `ユーザー #${task.value.user_id}`,
		createdAt: formatDate(task.value.created_at),
		updatedAt: formatDate(task.value.updated_at),
		checklist: [
      { label: "詳細APIからタスクを取得する", done: true },
      { label: "表示項目を確認する", done: true },
      { label: "編集画面への導線を検討する", done: false },
    ],
	};
});

// --- クラス名の算出 ---
const statusClass = computed(() => {
  if (!displayTask.value) return "";

  return STATUS_CLASS_MAP[displayTask.value.status] || "";
});

// 優先度はnullの可能性があるため、未設定の場合は特別に扱う
const priorityClass = computed(() => {
  if (!displayTask.value) return "";

  return PRIORITY_CLASS_MAP[displayTask.value.priority] || "";
});

// --- チェックリストの進捗計算 ---
const checklistProgress = computed(() => {
  if (!displayTask.value) return { done: 0, total: 0 };

  const done = displayTask.value.checklist.filter((item) => item.done).length;

  return {
    done,
    total: displayTask.value.checklist.length,
  };
});
</script>

<template>
  <main class="page">
    <AppHeader title="タスク詳細" />

    <PageTitle
      eyebrow="Task Details"
      title="タスク詳細"
      action-label="一覧へ戻る"
      action-to="/tasks"
      action-variant="secondary"
    />

    <p v-if="pending" class="empty-message">読み込み中...</p>

    <section v-else-if="error" class="empty-message">
      <h2>タスクの取得に失敗しました</h2>
      <p>{{ error.message }}</p>
      <NuxtLink class="secondary-link" to="/tasks">一覧へ戻る</NuxtLink>
    </section>

    <article v-else-if="displayTask" class="detail-panel">
      <header class="detail-header">
        <p class="detail-id">Task #{{ displayTask.id }}</p>
        <h2>{{ displayTask.title }}</h2>

        <ul class="detail-badges" aria-label="タスク属性">
          <li :class="['status-badge', statusClass]">{{ displayTask.status }}</li>
          <li :class="['priority-badge', priorityClass]">
            優先度 {{ displayTask.priority }}
          </li>
        </ul>
      </header>

      <section class="detail-section" aria-labelledby="detail-description">
        <h3 id="detail-description">説明</h3>
        <p>{{ displayTask.description }}</p>
      </section>

      <dl class="detail-list">
        <dt>担当者</dt>
        <dd>{{ displayTask.assignee }}</dd>

        <dt>カテゴリ</dt>
        <dd>{{ displayTask.category }}</dd>

        <dt>期限</dt>
        <dd>{{ displayTask.dueDate }}</dd>

        <dt>作成日</dt>
        <dd>{{ displayTask.createdAt }}</dd>

        <dt>更新日</dt>
        <dd>{{ displayTask.updatedAt }}</dd>
      </dl>

      <section class="checklist-section" aria-labelledby="task-checklist">
        <header class="checklist-header">
          <h3 id="task-checklist">チェックリスト</h3>
          <p>
            {{ checklistProgress.done }} / {{ checklistProgress.total }} 完了
          </p>
        </header>

        <ul class="checklist">
          <li
            v-for="item in displayTask.checklist"
            :key="item.label"
            :class="{ 'checklist-item--done': item.done }"
          >
            <span class="checkmark" aria-hidden="true">
              {{ item.done ? "✓" : "" }}
            </span>
            <span>{{ item.label }}</span>
          </li>
        </ul>
      </section>

      <footer class="detail-actions">
        <NuxtLink class="primary-link" to="/tasks/create">新規作成</NuxtLink>
      </footer>
    </article>

    <section v-else class="empty-message">
      <h2>タスクが見つかりません</h2>
      <p>指定されたIDのダミータスクはまだ用意されていません。</p>
      <NuxtLink class="secondary-link" to="/tasks">一覧へ戻る</NuxtLink>
    </section>
  </main>
</template>

<style scoped>
.page {
  min-height: 100vh;
  padding: 32px;
  background:
    radial-gradient(
      circle at top left,
      rgba(45, 106, 79, 0.12),
      transparent 32%
    ),
    linear-gradient(135deg, #f8fbfa 0%, #eef6f2 100%);
}

.detail-panel,
.empty-message {
  padding: 24px;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
}

.detail-panel {
  display: grid;
  gap: 24px;
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

.detail-section h3,
.empty-message h2 {
  margin: 0;
  color: #172033;
  font-size: 16px;
}

.detail-section p,
.empty-message p {
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

.primary-link {
  color: #fff;
  background: #2d6a4f;
  box-shadow: 0 8px 18px rgba(45, 106, 79, 0.22);
}

.empty-message {
  display: grid;
  gap: 14px;
}

@media (max-width: 640px) {
  .detail-header h2 {
    font-size: 22px;
  }

  .detail-actions {
    justify-content: stretch;
    flex-direction: column;
  }

  .secondary-link,
  .primary-link {
    width: 100%;
  }
}
</style>
