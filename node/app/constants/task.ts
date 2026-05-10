/* タスク関連の定数を定義 */

export const TASK_STATUSES = ["未着手", "進行中", "完了"];

export const TASK_CATEGORIES = ["開発", "UI/UX", "テスト", "ドキュメント"];

export const TASK_PRIORITIES = ["高", "中", "低"];

export const STATUS_LABELS: Record<number, string> = {
  0: "未着手",
  1: "進行中",
  2: "完了",
};

export const PRIORITY_LABELS: Record<number, string> = {
  0: "低",
  1: "中",
  2: "高",
};

export const PRIORITY_VALUES: Record<string, number> = {
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
