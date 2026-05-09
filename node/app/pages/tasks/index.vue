<!-- タスク一覧ページ　-->
<script setup lang="ts">
import { computed, ref } from "vue";
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import TaskFilterPanel from "~/components/tasks/TaskFilterPanel.vue";
import TaskSummaryGrid from "~/components/tasks/TaskSummaryGrid.vue";
import TaskTable from "~/components/tasks/TaskTable.vue";
import { formatTask } from "~/utils/task";
import { sortTasks } from "~/utils/taskSort";
import type { ApiTaskResponse, SortKey, SortOrder } from "~/types/task";
import {
  TASK_CATEGORIES,
  TASK_STATUSES,
  TASK_PRIORITIES,
} from "~/constants/task";

const keyword = ref("");
const selectedStatus = ref("");
const statuses = TASK_STATUSES;
const selectedCategory = ref("");
const categories = TASK_CATEGORIES;
const selectedPriority = ref("");
const priorities = TASK_PRIORITIES;
const sortKey = ref<SortKey>("dueDate");
const sortOrder = ref<SortOrder>("asc");

const config = useRuntimeConfig();
/* APIからタスクデータを取得 */
const apiBaseUrl = process.server
  ? "http://nginx"
  : config.public.apiBaseUrl;
const { data } = await useFetch<ApiTaskResponse>("/api/tasks", {
  baseURL: apiBaseUrl,
});

/* APIレスポンスをフロントエンドで扱いやすい形式に変換 */
const tasks = computed(() => {
  const apiTasks = data.value?.data || [];

  return apiTasks.map(formatTask);
});

/* タスクのステータス別件数を計算 */
const taskSummary = computed(() => {
  return {
    total: tasks.value.length,
    notStarted: tasks.value.filter((task) => task.status === "未着手").length,
    inProgress: tasks.value.filter((task) => task.status === "進行中").length,
    completed: tasks.value.filter((task) => task.status === "完了").length,
  };
});

/* ソート処理 */
const handleSort = (key: SortKey) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    return;
  }
  sortKey.value = key;
  sortOrder.value = "asc";
};

/* キーワード検索　ステータス・カテゴリ・優先度絞り込み ソート */
const filteredTasks = computed(() => {
  // フィルタリング
  const filtered = tasks.value.filter((task) => {
    const matchesKeyword =
      !keyword.value ||
      task.title.includes(keyword.value) ||
      task.description.includes(keyword.value);

    const matchesStatus =
      !selectedStatus.value || task.status === selectedStatus.value;

    const matchesCategory =
      !selectedCategory.value || task.category === selectedCategory.value;

    const matchesPriority =
      !selectedPriority.value || task.priority === selectedPriority.value;

    return matchesKeyword && matchesStatus && matchesCategory && matchesPriority;
  });
  // ソート
  return sortTasks(filtered, sortKey.value, sortOrder.value);
});
</script>

<template>
  <main class="page">
    <AppHeader title="タスク一覧" />

    <PageTitle
      eyebrow="Tasks"
      title="タスク一覧"
      action-label="新規作成"
      action-to="/tasks/create"
    />

    <TaskSummaryGrid :summary="taskSummary" />

    <TaskFilterPanel
      v-model:keyword="keyword"
      v-model:selected-status="selectedStatus"
      v-model:selected-category="selectedCategory"
      v-model:selected-priority="selectedPriority"
      :statuses="statuses"
      :categories="categories"
      :priorities="priorities"
    />

    <section class="list-summary">
      <span>表示件数</span>
      <strong>{{ filteredTasks.length }}</strong>
      <span>件</span>
    </section>

    <TaskTable
      :tasks="filteredTasks"
      :sort-key="sortKey"
      :sort-order="sortOrder"
      @sort="handleSort"
    />
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
</style>
