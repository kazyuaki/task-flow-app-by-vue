import type { SortKey, SortOrder, Task } from "~/types/task";

/* タスクのソート処理を行う関数 */
export const sortTasks = (
  tasks: Task[],
  key: SortKey,
  order: SortOrder,
): Task[] => {
  return [...tasks].sort((a, b) => {
    const aValue = a[key] || "";
    const bValue = b[key] || "";

    if (key === "id" || key === "dueDate") {
      const aNumber = key === "dueDate" ? a.dueDateTimestamp : Number(aValue);
      const bNumber = key === "dueDate" ? b.dueDateTimestamp : Number(bValue);

      return order === "asc"
        ? aNumber - bNumber
        : bNumber - aNumber;
    }

    return order === "asc"
      ? String(aValue).localeCompare(String(bValue))
      : String(bValue).localeCompare(String(aValue));
  });
};
