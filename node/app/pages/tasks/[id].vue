<!-- タスク詳細ページ -->
<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import TaskDetailCard from "~/components/tasks/TaskDetailCard.vue";
import TaskDetailStateMessage from "~/components/tasks/TaskDetailStateMessage.vue";

const route = useRoute();

const taskId = Number(route.params.id);

const { displayTask, pending, error } = await useTaskDetail(taskId);
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

    <TaskDetailStateMessage v-if="pending" message="読み込み中..." />

    <TaskDetailStateMessage
      v-else-if="error"
      title="タスクの取得に失敗しました"
      :message="error.message"
    />

    <TaskDetailCard v-else-if="displayTask" :task="displayTask" />

    <TaskDetailStateMessage
      v-else
      title="タスクが見つかりません"
      message="指定されたIDのタスクはまだ用意されていません。"
    />
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
</style>
