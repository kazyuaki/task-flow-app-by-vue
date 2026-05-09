<script setup lang="ts">
import { useRouter } from "vue-router";
import { getDueDateClass, getDueDateLabel } from "~/utils/taskDueDate";
import type { SortKey, SortOrder, Task } from "~/types/task";
import { PRIORITY_CLASS_MAP, STATUS_CLASS_MAP } from "~/constants/task";

defineProps<{
  tasks: Task[];
  sortKey: SortKey;
  sortOrder: SortOrder;
}>();

const emit = defineEmits<{
  sort: [key: SortKey];
}>();

const router = useRouter();

const headers: { key: SortKey; label: string }[] = [
  { key: "id", label: "ID" },
  { key: "title", label: "タイトル" },
  { key: "category", label: "カテゴリ" },
  { key: "status", label: "ステータス" },
  { key: "priority", label: "優先度" },
  { key: "dueDate", label: "期限" },
  { key: "dueDate", label: "残り日数" },
];
</script>

<template>
  <section class="table-wrapper">
    <table class="task-table">
      <thead>
        <tr>
          <th
            v-for="header in headers"
            :key="`${header.key}-${header.label}`"
            class="sortable-header"
            @click="emit('sort', header.key)"
          >
            {{ header.label }}
            <span v-if="sortKey === header.key">
              {{ sortOrder === "asc" ? "↑" : "↓" }}
            </span>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="task in tasks"
          :key="task.id"
          class="task-row"
          @click="router.push(`/tasks/${task.id}`)"
        >
          <td>{{ task.id }}</td>
          <td class="text-left">
            <NuxtLink class="task-title-link" :to="`/tasks/${task.id}`">
              {{ task.title }}
            </NuxtLink>
          </td>
          <td>
            <span class="category-badge">
              {{ task.category }}
            </span>
          </td>
          <td>
            <span :class="['status', STATUS_CLASS_MAP[task.status]]">
              {{ task.status }}
            </span>
          </td>
          <td>
            <span :class="['priority', PRIORITY_CLASS_MAP[task.priority]]">
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
        <tr v-if="tasks.length === 0">
          <td class="empty-message" colspan="7">
            条件に一致するタスクがありません
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</template>

<style scoped>
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

.sortable-header {
  cursor: pointer;
  user-select: none;
}

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

.task-title-link {
  color: #172033;
  font-weight: 700;
  text-decoration: none;
}

.task-title-link:hover {
  color: #2d6a4f;
  text-decoration: underline;
}

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

.empty-message {
  padding: 32px;
  color: #98a2b3;
  text-align: center;
}
</style>
