import { computed, type Ref } from "vue";
import type { Task } from "~/types/task";

/**
 * タスクのサマリーを計算するカスタムフック
 * @param tasks - タスクのリスト
 * @returns タスクのサマリー（総数、未着手、進行中、完了の数）
 */
export const useTaskSummary = (tasks: Ref<Task[]>) => {
  const taskSummary = computed(() => {
    return {
      total: tasks.value.length,

      notStarted: tasks.value.filter((task) => task.status === "未着手").length,

      inProgress: tasks.value.filter((task) => task.status === "進行中").length,

      completed: tasks.value.filter((task) => task.status === "完了").length,
    };
  });

  return taskSummary;
};
