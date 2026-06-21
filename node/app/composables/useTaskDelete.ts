// composables/useTaskDelete.ts

/* タスク削除用Composable */
export const useTaskDelete = () => {
  const { $api } = useNuxtApp();

  const deleteTask = async (taskId: number) => {
    await $api.get("/sanctum/csrf-cookie");
    await $api.delete(`/api/tasks/${taskId}`);
  };

  return {
    deleteTask,
  };
};
