/* タスクの期限表示に関するユーティリティ関数 */
export const getDueDateLabel = (dueDate: string) => {
  const today = new Date();
  const targetDate = new Date(dueDate);

  today.setHours(0, 0, 0, 0);
  targetDate.setHours(0, 0, 0, 0);

  const diffTime = targetDate.getTime() - today.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays < 0) return "期限切れ";
  if (diffDays === 0) return "今日まで";
  if (diffDays === 1) return "明日まで";

  return `あと${diffDays}日`;
};

/* タスクの期限に応じたCSSクラスを返す関数 */
export const getDueDateClass = (dueDate: string) => {
  const today = new Date();
  const targetDate = new Date(dueDate);

  today.setHours(0, 0, 0, 0);
  targetDate.setHours(0, 0, 0, 0);

  const diffTime = targetDate.getTime() - today.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays < 0) return "due-label--expired";
  if (diffDays <= 2) return "due-label--warning";

  return "due-label--normal";
};
