// composables/useTaskDelete.ts

/* タスク削除用Composable */
export const useTaskDelete = () => {
    const config = useRuntimeConfig();

    const apiBaseUrl = process.server
        ? "http://nginx"
        : String(config.public.apiBaseUrl);
    
    const deleteTask = async (taskId: number) => {
        await $fetch(`/api/tasks/${taskId}`, {
            method: "DELETE",
            baseURL: apiBaseUrl,
        });
    }
    return {
        deleteTask,
    };
}