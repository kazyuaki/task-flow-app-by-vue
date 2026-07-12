<!-- タスク詳細ページ -->
<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import TaskDetailCard from "~/components/tasks/TaskDetailCard.vue";
import TaskDetailStateMessage from "~/components/tasks/TaskDetailStateMessage.vue";
import ConfirmDialog from "~/components/common/ConfirmDialog.vue";

const route = useRoute();
const { showToast } = useToast();

const taskId = Number(route.params.id);

const { displayTask, pending, error } = await useTaskDetail(taskId);

const { deleteTask } = useTaskDelete();

const isDeleting = ref(false);

const isDeleteDialogOpen = ref(false);
const deleteTargetTaskId = ref<number | null>(null);

const openDeleteDialog = (taskId: number) => {
  deleteTargetTaskId.value = taskId;
  isDeleteDialogOpen.value = true;
};

const closeDeleteDialog = () => {
  deleteTargetTaskId.value = null;
  isDeleteDialogOpen.value = false;
};

/* タスク削除のハンドラー */
const handleDeleteTask = async () => {
  if (!deleteTargetTaskId.value) return;
  try {
    isDeleting.value = true;
    await deleteTask(deleteTargetTaskId.value);
    closeDeleteDialog();
    showToast("タスクを削除しました。");
    await navigateTo("/tasks");
  } catch (err) {
    console.error("タスクの削除に失敗しました:", err);
    showToast("タスクの削除に失敗しました。もう一度お試しください。", "error");
  } finally {
    isDeleting.value = false;
  }
};
</script>

<template>
  <main class="page">
    <AppHeader title="タスク詳細" />

    <PageTitle
      eyebrow="Task Details"
      title="タスク詳細"
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
      title="タスクの取得に失敗しました"
      :message="error.message"
    />

    <TaskDetailCard
      v-else-if="displayTask"
      :task="displayTask"
      :is-deleting="isDeleting"
      @delete="openDeleteDialog"
    />

    <TaskDetailStateMessage
      v-else
      title="タスクが見つかりません"
      message="指定されたIDのタスクはまだ用意されていません。"
    />

    <ConfirmDialog
      :open="isDeleteDialogOpen"
      title="タスクを削除しますか？"
      message="この操作は取り消せません。"
      confirm-text="削除する"
      :loading="isDeleting"
      @close="closeDeleteDialog"
      @confirm="handleDeleteTask"
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

@media (max-width: 760px) {
  .page {
    padding: 8px;
  }

  .profile-page {
    padding: 32px 10px;
  }
}
</style>
