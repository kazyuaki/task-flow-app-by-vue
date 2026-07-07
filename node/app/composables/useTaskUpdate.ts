import type { ApiCategoryResponse, UpdateTaskPayload } from "~/types/task";
import { PRIORITY_VALUES, TASK_STATUSES } from "~/constants/task";

/** タスク更新API */
export const useTaskUpdate = async (
  payload: UpdateTaskPayload,
): Promise<void> => {
  const { $api } = useNuxtApp();

  // チェックリストの整形
  const checklist = payload.checklist
    .map((item) => ({
      id: item.id,
      label: item.label.trim(),
      done: item.done,
    }))
    .filter((item) => item.label)
    .map((item, index) => ({
      ...item,
      sort_order: index + 1,
    }));
  
  const status = TASK_STATUSES.includes(payload.status)
    ? TASK_STATUSES.indexOf(payload.status)
    : 0;
  const priority = PRIORITY_VALUES[payload.priority] ?? 1;

  const categoryResponse = await $api.get<ApiCategoryResponse>("/api/categories");
  const category = categoryResponse.data.data.find(
    (category) => category.name === payload.category,
  );

  if (!category) {
    throw new Error("選択したカテゴリが見つかりません。");
  }

  // APIリクエスト
  await $api.get("/sanctum/csrf-cookie");
  await $api.put(`/api/tasks/${payload.id}`, {
    category_id: category.id,
    title: payload.title,
    description: payload.description,
    status,
    priority,
    due_date: payload.dueDate || null,
    checklist,
  });
};
