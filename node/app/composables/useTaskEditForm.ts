import { computed, onMounted, reactive, ref } from "vue";
import type {
  ApiCategory,
  ApiCategoryResponse,
  TaskDetail,
  UpdateTaskPayload,
} from "~/types/task";

/* タスク編集フォームのロジックを管理するComposable */
export const useTaskEditForm = (
  task: TaskDetail,
  emit: (event: "submit", payload: UpdateTaskPayload) => void,
) => {
  const { $api } = useNuxtApp();
  const categoryRecords = ref<ApiCategory[]>([]);
  const categories = computed(() =>
    categoryRecords.value.map((category) => category.name),
  );

  /* フォームの状態をリアクティブに管理 */
  const form = reactive({
    title: task.title,
    description: task.description,
    status: task.status,
    priority: task.priority,
    assignee: task.assignee,
    category: task.category,
    dueDate: task.dueDateInput,
    checklist: [...task.checklist],
  });

  /* チェックリスト項目の追加 */
  const addChecklistItem = () => {
    form.checklist.push({
      id: null,
      tempId: Date.now(),
      label: "",
      done: false,
    });
  };
  /* チェックリスト項目の削除 */
  const removeChecklistItem = (id: number | null, tempId?: number) => {
    form.checklist = form.checklist.filter((item) => {
      if (tempId !== undefined) return item.tempId !== tempId;

      return item.id !== id;
    });
  };

  /* フォーム送信のハンドラー */
  const handleSubmit = () => {
    const payload: UpdateTaskPayload = {
      id: task.id,
      categoryId: task.categoryId,
      title: form.title,
      description: form.description,
      status: form.status,
      priority: form.priority,
      assignee: form.assignee,
      category: form.category,
      dueDate: form.dueDate,
      checklist: form.checklist.map((item) => ({
        id: item.id,
        label: item.label,
        done: item.done,
      })),
    };

    emit("submit", payload);
    console.log("編集フォーム送信:", form);
  };

  const loadCategories = async () => {
    try {
      const response = await $api.get<ApiCategoryResponse>("/api/categories");
      categoryRecords.value = response.data.data;

      if (
        !categoryRecords.value.some(
          (category) => category.name === form.category,
        )
      ) {
        form.category = categories.value[0] ?? "";
      }
    } catch (error) {
      console.error("カテゴリの取得に失敗しました:", error);
    }
  };

  onMounted(loadCategories);

  return {
    form,
    categories,
    addChecklistItem,
    removeChecklistItem,
    handleSubmit,
  };
};
