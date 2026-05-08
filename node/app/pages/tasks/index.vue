<!-- タスク一覧ページ　-->
<script setup lang="ts">
import { computed, ref } from "vue";
import AppHeader from "~/components/layouts/AppHeader.vue";

const keyword = ref("");
const selectedStatus = ref("");
const statuses = ["未着手", "進行中", "完了"];
const sortOrder = ref("asc");
const selectedCategory = ref("");
const categories = ["開発", "UI/UX", "テスト", "ドキュメント"];

const tasks = [
  {
    id: 1,
    title: "Laravel API作成",
    description: "ログインAPIを実装する",
    status: "未着手",
    priority: "高",
    category: "開発",
    dueDate: "2026-05-10",
  },
  {
    id: 2,
    title: "Nuxt画面作成",
    description: "一覧画面を作成する",
    status: "進行中",
    priority: "中",
    category: "UI/UX",
    dueDate: "2026-05-12",
  },
  {
    id: 3,
    title: "認証エラー表示の調整",
    description: "ログイン失敗時のメッセージと入力状態を見直す",
    status: "進行中",
    priority: "高",
    category: "UI/UX",
    dueDate: "2026-05-08",
  },
  {
    id: 4,
    title: "タスク登録APIのバリデーション",
    description: "必須項目と期限日フォーマットの検証を追加する",
    status: "未着手",
    priority: "高",
    category: "開発",
    dueDate: "2026-05-09",
  },
  {
    id: 5,
    title: "一覧画面のレスポンシブ確認",
    description: "スマホ幅で検索フォームとテーブルの表示を確認する",
    status: "未着手",
    priority: "中",
    category: "テスト",
    dueDate: "2026-05-15",
  },
  {
    id: 6,
    title: "READMEのセットアップ手順更新",
    description: "Docker起動からログイン確認までの手順を整理する",
    status: "完了",
    priority: "低",
    category: "ドキュメント",
    dueDate: "2026-05-06",
  },
  {
    id: 7,
    title: "タスク詳細ページの導線確認",
    description: "一覧から詳細へ移動した後の戻り導線を確認する",
    status: "進行中",
    priority: "中",
    category: "テスト",
    dueDate: "2026-05-11",
  },
  {
    id: 8,
    title: "完了タスクの表示ルール整理",
    description: "完了済みタスクの色や並び順の扱いを決める",
    status: "未着手",
    priority: "低",
    category: "UI/UX",
    dueDate: "2026-05-18",
  },
  {
    id: 9,
    title: "APIレスポンス型のメモ作成",
    description: "タスク一覧で使うフィールド名と型をドキュメント化する",
    status: "完了",
    priority: "中",
    category: "ドキュメント",
    dueDate: "2026-05-07",
  },
  {
    id: 10,
    title: "検索条件リセット機能の検討",
    description: "絞り込み条件をまとめて初期化するボタンの配置を考える",
    status: "未着手",
    priority: "低",
    category: "開発",
    dueDate: "2026-05-20",
  },
];

/* タスクのステータス別件数を計算 */
const taskSummary = computed(() => {
  return {
    total: tasks.length,
    notStarted: tasks.filter((task) => task.status === "未着手").length,
    inProgress: tasks.filter((task) => task.status === "進行中").length,
    completed: tasks.filter((task) => task.status === "完了").length,
  };
});

/* キーワード・ステータス・カテゴリ絞り込み ソート */
const filteredTasks = computed(() => {
  return tasks
    .filter((task) => {
      const matchesKeyword =
        !keyword.value ||
        task.title.includes(keyword.value) ||
        task.description.includes(keyword.value);

      const matchesStatus =
        !selectedStatus.value || task.status === selectedStatus.value;

      const matchesCategory =
        !selectedCategory.value || task.category === selectedCategory.value;

      return matchesKeyword && matchesStatus && matchesCategory;
    })
    .sort((a, b) => {
      if (sortOrder.value === "asc") {
        return a.dueDate.localeCompare(b.dueDate);
      }
      return b.dueDate.localeCompare(a.dueDate);
    });
});

/* 期限に応じたラベルを返す関数 */
const getDueDateLabel = (dueDate: string) => {
  const today = new Date();
  const targetDate = new Date(dueDate);

  today.setHours(0, 0, 0, 0);
  targetDate.setHours(0, 0, 0, 0);

  const diffTime = targetDate.getTime() - today.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays < 0) return "期限切れ";
  if (diffDays === 0) return "今日まで";
  if (diffDays === 1) return "明日まで";

  return `あと${diffDays}日`;
};

/* 期限に応じたクラスを返す関数 */
const getDueDateClass = (dueDate: string) => {
  const today = new Date();
  const targetDate = new Date(dueDate);

  today.setHours(0, 0, 0, 0);
  targetDate.setHours(0, 0, 0, 0);

  const diffTime = targetDate.getTime() - today.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays < 0) return "due-label--expired";
  if (diffDays <= 2) return "due-label--warning";

  return "due-label--normal";
};

/* ステータスに応じたクラスを返す関数 */
const getStatusClass = (status: string) => {
  if (status === "進行中") {
    return "status--in-progress";
  }
  if (status === "未着手") {
    return "status--not-started";
  }
  if (status === "完了") {
    return "status--completed";
  }

  return "";
};

/* 優先度に応じたクラスを返す関数 */
const getPriorityClass = (priority: string) => {
  if (priority === "高") return "priority--high";
  if (priority === "中") return "priority--medium";
  if (priority === "低") return "priority--low";

  return "";
};
</script>

<template>
  <main class="page">
    <AppHeader title="タスク一覧" />
    <!-- ページヘッダー　-->
    <section class="page-header">
      <div>
        <p class="eyebrow">Tasks</p>
        <h1>タスク一覧</h1>
      </div>

      <NuxtLink class="create-link" to="/tasks/create"> 新規作成 </NuxtLink>
    </section>

    <section class="summary-grid">
      <div class="summary-card summary-card--total">
        <span>全件</span>
        <strong>{{ taskSummary.total }}</strong>
      </div>
      <div class="summary-card summary-card--not-started">
        <span>未着手</span>
        <strong>{{ taskSummary.notStarted }}</strong>
      </div>
      <div class="summary-card summary-card--in-progress">
        <span>進行中</span>
        <strong>{{ taskSummary.inProgress }}</strong>
      </div>
      <div class="summary-card summary-card--completed">
        <span>完了</span>
        <strong>{{ taskSummary.completed }}</strong>
      </div>
    </section>

    <!-- 検索・絞り込みエリア　-->
    <section class="control-panel">
      <div class="control-group control-group--search">
        <div class="control-heading">
          <h2>検索</h2>
        </div>

        <label class="control-field control-field--wide">
          <span>キーワード</span>
          <input
            v-model="keyword"
            type="text"
            placeholder="例：API、ログイン、画面"
          />
        </label>
      </div>

      <div class="control-group">
        <div class="control-heading">
          <h2>絞り込み</h2>
        </div>

        <div class="filter-grid">
          <label class="control-field">
            <span>ステータス</span>
            <select v-model="selectedStatus">
              <option value="">すべて</option>
              <option v-for="status in statuses" :key="status" :value="status">
                {{ status }}
              </option>
            </select>
          </label>
          <label class="control-field">
            <span>カテゴリ</span>
            <select v-model="selectedCategory">
              <option value="">すべて</option>
              <option
                v-for="category in categories"
                :key="category"
                :value="category"
              >
                {{ category }}
              </option>
            </select>
          </label>
          <label class="control-field">
            <span>並び替え</span>
            <select v-model="sortOrder">
              <option value="asc">期限が近い順</option>
              <option value="desc">期限が遠い順</option>
            </select>
          </label>
        </div>
      </div>
    </section>

    <section class="list-summary">
      <span>表示件数</span>
      <strong>{{ filteredTasks.length }}</strong>
      <span>件</span>
    </section>

    <section class="table-wrapper">
      <table class="task-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>タイトル</th>
            <th>説明</th>
            <th>カテゴリ</th>
            <th>ステータス</th>
            <th>優先度</th>
            <th>期限</th>
            <th>残り日数</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="task in filteredTasks"
            :key="task.id"
            class="task-row"
            @click="$router.push(`/tasks/${task.id}`)"
          >
            <td>{{ task.id }}</td>
            <td class="text-left">
              <NuxtLink class="task-title-link" :to="`/tasks/${task.id}`">
                {{ task.title }}
              </NuxtLink>
            </td>
            <td class="text-left">
              <p class="task-description">
                {{ task.description }}
              </p>
            </td>
            <td>
              <span class="category-badge">
                {{ task.category }}
              </span>
            </td>
            <td>
              <span :class="['status', getStatusClass(task.status)]">
                {{ task.status }}
              </span>
            </td>
            <td>
              <span :class="['priority', getPriorityClass(task.priority)]">
                {{ task.priority }}
              </span>
            </td>
            <td>{{ task.dueDate }}</td>
            <td>
              <span :class="['due-label', getDueDateClass(task.dueDate)]">
                {{ getDueDateLabel(task.dueDate) }}
              </span>
            </td>
          </tr>
          <tr v-if="filteredTasks.length === 0">
            <td class="empty-message" colspan="8">
              条件に一致するタスクがありません
            </td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>
</template>

<style scoped>
/* ページ全体 */
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

/* ページヘッダー */
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  margin-top: 40px;
  margin-bottom: 24px;
}

.eyebrow {
  margin: 0 0 8px;
  color: #2d6a4f;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
}

.page-header h1 {
  margin: 0;
  font-size: 32px;
}

/* 新規作成ボタン */
.create-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 42px;
  padding: 0 16px;
  color: #fff;
  font-weight: 700;
  background: #2d6a4f;
  border-radius: 8px;
  text-decoration: none;
  box-shadow: 0 8px 18px rgba(45, 106, 79, 0.22);
  transition:
    transform 0.2s ease,
    background 0.2s ease,
    box-shadow 0.2s ease;
}

.create-link:hover {
  background: #24563f;
  transform: translateY(-1px);
  box-shadow: 0 10px 22px rgba(45, 106, 79, 0.28);
}

/* サマリーカード */
.summary-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.summary-card {
  padding: 18px 20px;
  border: 1px solid #eaecf0;
  border-radius: 12px;
  color: #172033;
  box-shadow: 0 10px 24px rgba(16, 24, 40, 0.06);
}

.summary-card span {
  display: block;
  margin-bottom: 8px;
  color: #667085;
  font-size: 13px;
  font-weight: 700;
}

.summary-card strong {
  font-size: 28px;
  line-height: 1;
}

.summary-card--total {
  background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
  border: 1px solid #e5e7eb;
}

.summary-card--not-started {
  background: linear-gradient(135deg, #fff4ed 0%, #ffe8d9 100%);
  border: 1px solid #ffd8bf;
}

.summary-card--not-started strong {
  color: #d9480f;
}

.summary-card--in-progress {
  background: linear-gradient(135deg, #eef6ff 0%, #dceeff 100%);
  border: 1px solid #c5e1ff;
}

.summary-card--in-progress strong {
  color: #1c7ed6;
}

.summary-card--completed {
  background: linear-gradient(135deg, #edf7f1 0%, #dff3e7 100%);
  border: 1px solid #cdebd8;
}

.summary-card--completed strong {
  color: #2d6a4f;
}

/* 検索・絞り込みエリア */
.control-panel {
  display: grid;
  gap: 20px;
  margin-bottom: 32px;
  padding: 20px;
  border: 1px solid #eaecf0;
  background: rgba(255, 255, 255, 0.92);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
  border-radius: 8px;
}

.control-group {
  display: grid;
  gap: 14px;
}

.control-group--search {
  padding-bottom: 20px;
  border-bottom: 1px solid #eaecf0;
}

.control-heading {
  display: grid;
  gap: 4px;
}

.control-heading h2 {
  margin: 0;
  color: #172033;
  font-size: 15px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(180px, 1fr));
  gap: 16px;
}

.control-field {
  display: grid;
  gap: 8px;
}

.control-field--wide {
  max-width: 640px;
}

.control-field span {
  color: #475467;
  font-size: 13px;
  font-weight: 700;
}

.control-field input,
.control-field select {
  min-height: 42px;
  padding: 0 12px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  font: inherit;
}

/* 表示件数 */
.list-summary {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 12px;
  color: #667085;
  font-size: 13px;
}

.list-summary strong {
  color: #2d6a4f;
  font-size: 18px;
}

/* タスクテーブル */
.table-wrapper {
  overflow-x: auto;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: #fff;
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
}

.task-table {
  width: 100%;
  min-width: 760px;
  border-collapse: collapse;
}

.task-table th {
  padding: 14px 16px;
  color: #475467;
  font-size: 13px;
  text-align: center;
  background: #f9fafb;
}

.task-table td {
  padding: 16px;
  border-top: 1px solid #eaecf0;
  color: #475467;
  font-size: 14px;
  text-align: center;
}

.task-table tbody tr {
  transition: background 0.2s ease;
}

.task-table tbody tr:hover {
  background: #f8fbfa;
}

/* タスク行 */
.task-row {
  cursor: pointer;
  transition:
    background 0.2s ease,
    transform 0.2s ease;
}

.task-row:hover {
  background: #f8fbfa;
}

.task-row:hover td:first-child {
  box-shadow: inset 4px 0 0 #2d6a4f;
}

.text-left {
  text-align: left;
}

/* タスク本文 */
.task-title {
  color: #172033;
  font-size: 15px;
  font-weight: 700;
}

.task-title-link {
  color: #172033;
  font-weight: 700;
  text-decoration: none;
}

.task-title-link:hover {
  color: #2d6a4f;
  text-decoration: underline;
}

.task-description {
  margin: 0;
  color: #667085;
  font-size: 13px;
  line-height: 1.6;
}

/* カテゴリバッジ */
.category-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 4px 10px;
  border-radius: 999px;
  background: #f2f4f7;
  color: #344054;
  font-size: 12px;
  font-weight: 700;
}

/* ステータスバッジ */
.status {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  min-width: 72px;
  padding: 4px 10px;
  font-size: 12px;
  font-weight: 700;
  border-radius: 999px;
}

.status::before {
  content: "";
  width: 6px;
  height: 6px;
  border-radius: 999px;
  background: currentColor;
}

.status--not-started {
  color: #d9480f;
  background: rgba(217, 72, 15, 0.1);
}

.status--in-progress {
  color: #1c7ed6;
  background: rgba(28, 126, 214, 0.1);
}

.status--completed {
  color: #2d6a4f;
  background: rgba(45, 106, 79, 0.1);
}

/* 優先度バッジ */
.priority {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 48px;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
}

.priority--high {
  color: #c92a2a;
  background: rgba(201, 42, 42, 0.1);
}

.priority--medium {
  color: #f08c00;
  background: rgba(240, 140, 0, 0.12);
}

.priority--low {
  color: #2f9e44;
  background: rgba(47, 158, 68, 0.1);
}

/* 期限ラベル */
.due-label {
  color: #2d6a4f;
  font-size: 13px;
  font-weight: 700;
}

.due-label--normal {
  color: #2d6a4f;
}

.due-label--warning {
  color: #f08c00;
}

.due-label--expired {
  color: #d92d20;
}

/* 空状態 */
.empty-message {
  padding: 32px;
  color: #98a2b3;
  text-align: center;
}

@media (max-width: 760px) {
  .filter-grid {
    grid-template-columns: 1fr;
  }
}
</style>
