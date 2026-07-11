<!-- タスク編集ページ-->
<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import TaskDetailStateMessage from "~/components/tasks/TaskDetailStateMessage.vue";
import TaskEditForm from "~/components/tasks/TaskEditForm.vue";
import type { UpdateTaskPayload } from "~/types/task";

const route = useRoute();

const taskId = Number(route.params.id);
const { displayTask, pending, error } = await useTaskDetail(taskId);

/* タスク更新のハンドラー */
const handleUpdate = async (payload: UpdateTaskPayload) => {
  console.log("更新payload:", payload);
  
  try {
    await useTaskUpdate(payload);
    clearNuxtData(`task-detail-${taskId}`);
    clearNuxtData("tasks");
    await navigateTo(`/tasks/${taskId}`);
  } catch (err) {
    console.error("タスクの更新に失敗しました:", err);
    alert("タスクの更新に失敗しました。もう一度お試しください。");
  }
};
</script>

<template>
  <main class="page">
    <AppHeader title="タスク編集" />

    <PageTitle
      eyebrow="Task Edit"
      title="タスク編集"
      secondary-action-label="新規作成"
      secondary-action-to="/tasks/create"
      secondary-action-variant="primary"
      action-label="一覧へ戻る"
      action-to="/tasks"
      action-variant="secondary"
    />

    <TaskDetailStateMessage v-if="pending" message="読み込み中..." />

    <TaskDetailStateMessage
      v-else-if="error"
      message="タスクの読み込みに失敗しました。"
    />

    <section v-else-if="displayTask">
      <TaskEditForm 
        :task="displayTask" 
        @submit="handleUpdate"
      />
    </section>


    <TaskDetailStateMessage v-else message="タスクが見つかりませんでした。" />
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

.edit-label {
  margin: 0 0 8px;
  color: #2d6a4f;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.edit-card h2 {
  margin: 0;
  color: #1f2d28;
  font-size: 24px;
}

.edit-description {
  margin: 12px 0 0;
  color: #6b7c73;
  line-height: 1.8;
}

@media (max-width: 760px) {
  .page {
    padding: 12px;
  }
}
</style>
