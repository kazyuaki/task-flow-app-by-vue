/** タスクAPI・表示用の型定義 */

import type {
  TASK_CATEGORIES,
  TASK_PRIORITIES,
  TASK_STATUSES,
} from "~/constants/task";

export type ApiTaskResponse = {
  data: ApiTask[];
};

export type ApiCategory = {
  id: number;
  name: string;
  color: string | null;
};

export type ApiCategoryResponse = {
  data: ApiCategory[];
};

export type TaskStatus = (typeof TASK_STATUSES)[number];

export type TaskPriority = (typeof TASK_PRIORITIES)[number];

export type TaskCategory = (typeof TASK_CATEGORIES)[number];

export type TaskFormChecklistItem = {
  label: string;
  done: boolean;
};

export type TaskCreateForm = {
  title: string;
  description: string;
  status: TaskStatus;
  priority: TaskPriority;
  category: TaskCategory;
  dueDate: string;
  checklist: TaskFormChecklistItem[];
};

/* ------------------------------
 * APIレスポンス型
 * ----------------------------- */
export type ApiTask = {
  id: number;
  title: string;
  description?: string;
  status: number;
  priority: number;
  category?: { name: string } | null;
  due_date?: string;
};

export type ApiTaskDetail = {
  id: number;
  user_id: number;
  category_id: number | null;
  category?: { id: number; name: string; color?: string | null } | null;
  title: string;
  description: string | null;
  status: number;
  priority?: number | null;
  due_date?: string | null;
  created_at: string;
  updated_at: string;
  checklist?: ChecklistItem[];
};

/* ------------------------------
 * フロント表示用型
 * ----------------------------- */
export type Task = {
  id: number;
  title: string;
  description: string;
  status: TaskStatus;
  priority: TaskPriority;
  category: TaskCategory;
  dueDate: string;
  dueDateTimestamp: number;
};

export type ChecklistItem = {
  id: number | null;
  tempId?: number; // 新規追加アイテム用の一時ID
  label: string;
  done: boolean;
};

export type TaskDetail = {
  id: number;
  categoryId: number | null;
  title: string;
  description: string;
  status: TaskStatus;
  priority: TaskPriority;
  category: TaskCategory;
  dueDate: string;
  dueDateInput: string;
  assignee: string;
  createdAt: string;
  updatedAt: string;
  checklist: ChecklistItem[];
};

export type UpdateTaskPayload = {
  id: number;
  categoryId: number | null;
  title: string;
  description: string;
  status: TaskStatus;
  priority: TaskPriority;
  assignee: string;
  category: TaskCategory;
  dueDate: string;
  checklist: Pick<ChecklistItem, "id" | "label" | "done">[];
};

/* ------------------------------
 * ソート関連型
 * ----------------------------- */
export type SortKey =
  | "id"
  | "title"
  | "category"
  | "status"
  | "priority"
  | "dueDate";
export type SortOrder = "asc" | "desc";
