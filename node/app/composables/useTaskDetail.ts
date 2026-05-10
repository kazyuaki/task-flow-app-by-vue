import { computed } from "vue";
import { PRIORITY_LABELS, STATUS_LABELS } from "~/constants/task";
import type { ApiTaskDetail, TaskDetail } from "~/types/task";

// --- ヘルパー関数 ---
const formatDate = (date?: string | null) => {
  if (!date) return "未設定";

  return new Date(date).toLocaleDateString("ja-JP");
};

/* タスク詳細取得用のComposable */
export const useTaskDetail = async (taskId: number) => {
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

  // --- 表示用のデータ整形 ---
  const displayTask = computed<TaskDetail | null>(() => {
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
      category: task.value.category?.name || "未分類",
      dueDate: formatDate(task.value.due_date),
      assignee: `ユーザー #${task.value.user_id}`,
      createdAt: formatDate(task.value.created_at),
      updatedAt: formatDate(task.value.updated_at),
      checklist: task.value.checklist || [],
    };
  });
  return {
    displayTask,
    pending,
    error,
  };
};
