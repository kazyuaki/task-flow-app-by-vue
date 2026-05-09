import type { ApiTask, Task } from "~/types/task";
import { STATUS_LABELS, PRIORITY_LABELS } from "~/constants/task";

/* APIから受け取ったタスクデータをフロントエンドで使用する形式に変換する関数 */
export const formatTask = (task: ApiTask): Task => {
  const dueDate = task.due_date ? task.due_date.slice(0, 10) : "";
  const dueDateTimestamp = dueDate ? new Date(dueDate).getTime() : Number.MAX_SAFE_INTEGER;

  return {
    id: task.id,
    title: task.title,
    description: task.description || "",
    status: STATUS_LABELS[task.status] || "不明",
    priority: PRIORITY_LABELS[task.priority] || "不明",
    category: task.category?.name || "未分類",
    dueDate: dueDate || "なし",
    dueDateTimestamp,
  };
};
