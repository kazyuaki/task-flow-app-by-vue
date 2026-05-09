<!-- タスク新規作成ページ -->
<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import { useTaskCreateForm } from "~/composables/useTaskCreateForm";
import TaskCreateFormPanel from "~/components/tasks/TaskCreateFormPanel.vue";
import TaskCreatePreview from "~/components/tasks/TaskCreatePreview.vue";

const {
  statuses,
  priorities,
  categories,
  form,
  errors,
  touched,
  generalError,
  isSubmitting,
  canSubmit,
  getToday,
  clearFieldError,
  submitTask,
  touchField,
} = useTaskCreateForm();
</script>

<template>
  <main class="page">
    <AppHeader title="タスク新規作成" />

    <PageTitle
      eyebrow="Create Task"
      title="タスク新規作成"
      action-label="一覧へ戻る"
      action-to="/tasks"
      action-variant="secondary"
    />

    <section class="create-layout">
      <TaskCreateFormPanel
        :form="form"
        :statuses="statuses"
        :priorities="priorities"
        :categories="categories"
        :errors="errors"
        :touched="touched"
        :general-error="generalError"
        :is-submitting="isSubmitting"
        :can-submit="canSubmit"
        :due-date-min="getToday()"
        @submit="submitTask"
        @clear-field="clearFieldError"
        @touch-field="touchField"
      />

      <TaskCreatePreview
        :title="form.title"
        :description="form.description"
        :status="form.status"
        :priority="form.priority"
        :category="form.category"
        :due-date="form.dueDate"
      />
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

/* 作成画面レイアウト */
.create-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 340px;
  gap: 24px;
  align-items: start;
  min-width: 0;
}

@media (max-width: 860px) {
  .create-layout {
    grid-template-columns: 1fr;
  }
}
</style>
