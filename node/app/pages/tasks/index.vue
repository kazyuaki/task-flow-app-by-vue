<!-- タスク一覧ページ　-->
<script setup lang="ts">
import { computed, ref } from "vue";
import AppHeader from "~/components/layouts/AppHeader.vue";

const keyword = ref("");

const tasks = [
  {
    id: 1,
    title: "Laravel API作成",
    description: "ログインAPIを実装する",
    status: "未着手",
    dueDate: "2026-05-10",
  },
  {
    id: 2,
    title: "Nuxt画面作成",
    description: "一覧画面を作成する",
    status: "進行中",
    dueDate: "2026-05-12",
  },
];

/* タスクをキーワードで絞り込む */
const filteredTasks = computed(() => {
  if (!keyword.value) return tasks;

  return tasks.filter((task) => {
    return (
      task.title.includes(keyword.value) ||
      task.description.includes(keyword.value)
    );
  });
});

const getStatusClass = (status: string) => {
  if (status === "進行中") {
    return 'status--in-progress';
  }

  if (status === "未着手") {
    return 'status--not-started';
  }

  if (status === "完了") {
    return 'status--completed';
  }

  return '';
}

</script>

<template>
  <main class="page">
    <AppHeader title="タスク一覧" />

    <section class="page-header">
      <div>
        <p class="eyebrow">Tasks</p>
        <h1>タスク一覧</h1>
      </div>

      <NuxtLink class="create-link" to="/tasks/create"> 新規作成 </NuxtLink>
    </section>

    <section class="search-area">
      <label class="search-box">
        <span>検索</span>
        <input
          v-model="keyword"
          type="text"
          placeholder="タイトル・説明で検索"
        />
      </label>
    </section>

    <section class="table-wrapper">
      <table class="task-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>タイトル</th>
            <th>説明</th>
            <th>ステータス</th>
            <th>期限</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="task in filteredTasks" :key="task.id">
            <td>{{ task.id }}</td>
            <td>
              <strong class="task-title">
                {{ task.title }}
              </strong>
            </td>
            <td>{{ task.description }}</td>
            <td>
              <span 
                :class="['status', getStatusClass(task.status)]">
                {{ task.status }}
              </span>
            </td>
            <td>{{ task.dueDate }}</td>
          </tr>
          <tr v-if="filteredTasks.length === 0">
            <td class="empty-message" colspan="5">
              条件に一致するタスクがありません
            </td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>
</template>

<style scoped>
.page {
  padding: 32px;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
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
}

.search-area {
  margin-bottom: 32px;
}

.search-box {
  display: grid;
  gap: 8px;
  width: min(420px, 100%);
}

.search-box span {
  color: #475467;
  font-size: 13px;
  font-weight: 700;
}

.search-box input {
  min-height: 42px;
  padding: 0 12px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  font: inherit;
}

.table-wrapper {
  overflow-x: auto;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: #fff;
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

.task-title {
  color: #172033;
}

.status {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 72px;
  padding: 4px 10px;
  font-size: 12px;
  font-weight: 700;
  border-radius: 999px;
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

.empty-message {
  padding: 32px;
  color: #98a2b3;
  text-align: center;
}
</style>
