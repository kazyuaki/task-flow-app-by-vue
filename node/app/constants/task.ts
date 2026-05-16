import type { TaskPriority, TaskStatus } from "~/types/task";

/* タスク関連の定数を定義 */
export const TASK_STATUSES: TaskStatus[] = ["未着手", "進行中", "完了"];

export const TASK_CATEGORIES = ["開発", "UI/UX", "テスト", "ドキュメント"];

export const TASK_PRIORITIES: TaskPriority[] = ["高", "中", "低"];

export const STATUS_LABELS: Record<number, TaskStatus> = {
  0: "未着手",
  1: "進行中",
  2: "完了",
};

export const PRIORITY_LABELS: Record<number, TaskPriority> = {
  0: "低",
  1: "中",
  2: "高",
};

export const STATUS_VALUES: Record<TaskStatus, number> = {
  "未着手": 0,
  "進行中": 1,
  "完了": 2,
};

export const PRIORITY_VALUES: Record<TaskPriority, number> = {
  低: 0,
  中: 1,
  高: 2,
};

export const STATUS_CLASS_MAP: Record<string, string> = {
  未着手: "status--not-started",
  進行中: "status--in-progress",
  完了: "status--completed",
};

export const PRIORITY_CLASS_MAP: Record<string, string> = {
  高: "priority--high",
  中: "priority--medium",
  低: "priority--low",
};
