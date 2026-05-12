import { computed } from "vue";
import { formatTask } from "~/utils/task";
import type { ApiTaskResponse } from "~/types/task";

/**
 * タスクデータを取得するカスタムフック
 * @returns タスクデータ、ローディング状態、エラー情報、データの再取得関数
 */
export const useTasks = async () => {
  const config = useRuntimeConfig();

  const apiBaseUrl = process.server ? "http://nginx" : config.public.apiBaseUrl;

  const { data, pending, error, refresh } = await useFetch<ApiTaskResponse>(
    "/api/tasks",
    {
      baseURL: apiBaseUrl,
      key: "tasks",
    },
  );

  const tasks = computed(() => {
    const apiTasks = data.value?.data || [];

    return apiTasks.map(formatTask);
  });

  return {
    tasks,
    pending,
    error,
    refresh,
  };
};
