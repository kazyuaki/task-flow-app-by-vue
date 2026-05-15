import type { UpdateTaskPayload } from "~/types/task";
import { PRIORITY_VALUES, TASK_STATUSES } from "~/constants/task";

/** タスク更新API */
export const useTaskUpdate = async (
  payload: UpdateTaskPayload,
): Promise<void> => {
  const config = useRuntimeConfig();
  const apiBaseUrl = process.server
    ? "http://nginx"
    : String(config.public.apiBaseUrl);

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

  await $fetch(`/api/tasks/${payload.id}`, {
    method: "PUT",
    baseURL: apiBaseUrl,
    body: {
      category_id: payload.categoryId,
      title: payload.title,
      description: payload.description,
      status,
      priority,
      due_date: payload.dueDate || null,
      checklist,
    },
  });
};
