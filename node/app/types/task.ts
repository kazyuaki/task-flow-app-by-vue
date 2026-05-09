/* eslint-disable @typescript-eslint/no-explicit-any */


export type ApiTaskResponse = {
  data: ApiTask[];
};

export type ApiTask = {
  id: number;
  title: string;
  description?: string;
  status: number;
  priority: number;
  category?: { name: string } | null;
  due_date?: string;
};

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

export type SortKey =
  | "id"
  | "title"
  | "category"
  | "status"
  | "priority"
  | "dueDate";
export type SortOrder = "asc" | "desc";
