/* タスク関連の定数を定義 */
export const TASK_STATUSES = ["未着手", "進行中", "完了"] as const;

export const TASK_PRIORITIES = ["低", "中", "高"] as const;

export const STATUS_LABELS = {
  0: "未着手",
  1: "進行中",
  2: "完了",
} satisfies Record<number, (typeof TASK_STATUSES)[number]>;

export const PRIORITY_LABELS = {
  0: "低",
  1: "中",
  2: "高",
} satisfies Record<number, (typeof TASK_PRIORITIES)[number]>;

export const STATUS_VALUES = {
  "未着手": 0,
  "進行中": 1,
  "完了": 2,
} satisfies Record<(typeof TASK_STATUSES)[number], number>;

export const PRIORITY_VALUES = {
  低: 0,
  中: 1,
  高: 2,
} satisfies Record<(typeof TASK_PRIORITIES)[number], number>;

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
