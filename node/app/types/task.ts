/** タスクAPI・表示用の型定義 */

export type ApiTaskResponse = {
  data: ApiTask[];
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
  title: string;
  description: string | null;
  status: number;
  priority?: number | null;
  due_date?: string | null;
  created_at: string;
  updated_at: string;
};

/* ------------------------------
 * フロント表示用型
 * ----------------------------- */
export type Task = {
  id: number;
  title: string;
  description: string;
  status: string;
  priority: string;
  category: string;
  dueDate: string;
  dueDateTimestamp: number;
};

export type ChecklistItem = {
  label: string;
  done: boolean;
};

export type TaskDetail = {
  id: number;
  title: string;
  description: string;
  status: string;
  priority: string;
  category: string;
  dueDate: string;
  assignee: string;
  createdAt: string;
  updatedAt: string;
  checklist: ChecklistItem[];
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
