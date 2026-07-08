import { computed, onMounted, reactive, ref } from "vue";
import { validateTaskForm } from "~/utils/validation/task";

import {
  PRIORITY_VALUES,
  TASK_CATEGORIES,
  TASK_PRIORITIES,
  TASK_STATUSES,
} from "~/constants/task";
import type {
  ApiCategory,
  ApiCategoryResponse,
  TaskCategory,
  TaskCreateForm,
} from "~/types/task";

const isTaskCategory = (category: string): category is TaskCategory =>
  (TASK_CATEGORIES as readonly string[]).includes(category);

export const useTaskCreateForm = () => {
  const { $api } = useNuxtApp();
  const statuses = TASK_STATUSES;
  const priorities = TASK_PRIORITIES;
  const categoryRecords = ref<ApiCategory[]>([]);
  const categories = computed(() =>
    categoryRecords.value
      .map((category) => category.name)
      .filter(isTaskCategory),
  );

  const isSubmitting = ref(false);
  const generalError = ref("");
  const submitted = ref(false);

  const touched = reactive<Record<string, boolean>>({});
  const errors = reactive<Record<string, string[]>>({});

  // フォームの状態管理
  const form = reactive<TaskCreateForm>({
    title: "",
    description: "",
    status: "未着手",
    priority: "中",
    category: "",
    dueDate: "",
    checklist: [
      {
        label: "",
        done: false,
      },
    ],
  });

  // 今日の日付を "YYYY-MM-DD" 形式で取得する関数
  const getToday = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
  };

  // フォームの入力内容が有効かどうかを判定
  const canSubmit = computed(() => {
    const hasRequiredFields =
      form.title.trim() && form.description.trim() && form.dueDate;

    const isValidDueDate = !form.dueDate || form.dueDate >= getToday();

    const hasCategory = categoryRecords.value.some(
      (category) => category.name === form.category,
    );

    return Boolean(
      hasRequiredFields &&
        hasCategory &&
        isValidDueDate &&
        !isSubmitting.value,
    );
  });

  // フィールドがタッチされたことを記録する関数
  const touchField = (field: string) => {
    touched[field] = true;
    validateForm();
  };

  // エラーハンドリング関数
  const clearErrors = () => {
    generalError.value = "";

    Object.keys(errors).forEach((key) => {
      delete errors[key];
    });
  };

  // 特定のフィールドのエラーをクリアする関数
  const clearFieldError = (field: string) => {
    delete errors[field];
    generalError.value = "";
  };

  // チェックリスト関連のエラーをクリアする関数
  const clearChecklistErrors = () => {
    Object.keys(errors).forEach((key) => {
      if (key.startsWith("checklist.")) {
        delete errors[key];
      }
    });
  };

  // フィールドにエラーメッセージを追加する関数
  const addError = (field: string, message: string) => {
    errors[field] = [...(errors[field] || []), message];
  };

  // クライアント側の入力バリデーション関数
  const validateForm = () => {
    clearErrors();

    validateTaskForm({
      form,
      statuses,
      priorities,
      getToday,
      addError,
    });

    return Object.keys(errors).length === 0;
  };

  // サーバーからのエラーレスポンスをフォームに反映する関数
  const applyServerErrors = (error: unknown) => {
    const response = error as {
      response?: {
        data?: {
          message?: string;
          errors?: Record<string, string[]>;
        };
      };
      data?: {
        message?: string;
        errors?: Record<string, string[]>;
      };
    };
    const errorData = response.response?.data ?? response.data;
    const serverErrors = errorData?.errors;

    if (!serverErrors) {
      generalError.value =
        errorData?.message || "タスクの作成に失敗しました。";
      return;
    }

    // サーバーのフィールド名とフォームのフィールド名のマッピング
    const fieldMap: Record<string, string> = {
      category_id: "category",
      due_date: "dueDate",
    };

    // サーバーからのエラーをフォームのエラーオブジェクトに変換
    Object.entries(serverErrors).forEach(([field, messages]) => {
      const formField = fieldMap[field] || field;

      if (formField === "user_id") {
        generalError.value = messages[0] || "入力内容を確認してください。";
        return;
      }

      errors[formField] = messages;
    });
  };

  // チェックリストアイテムの追加・削除関数
  const addChecklistItem = () => {
    form.checklist.push({
      label: "",
      done: false,
    });
  };

  // チェックリストアイテムの削除関数
  const removeChecklistItem = (index: number) => {
    form.checklist.splice(index, 1);
    clearChecklistErrors();
  };

  // フォーム送信処理
  const submitTask = async () => {
    submitted.value = true;

    if (!validateForm() || isSubmitting.value) return;

    isSubmitting.value = true;

    try {
      const selectedCategory = categoryRecords.value.find(
        (category) => category.name === form.category,
      );

      if (!selectedCategory) {
        errors.category = ["カテゴリを選択してください"];
        return;
      }

      const checklist = form.checklist
        .map((item) => ({
          label: item.label.trim(),
          done: item.done,
        }))
        .filter((item) => item.label);

      await $api.get("/sanctum/csrf-cookie");
      await $api.post("/api/tasks", {
        title: form.title,
        description: form.description,
        status: statuses.indexOf(form.status),
        priority: PRIORITY_VALUES[form.priority],
        category_id: selectedCategory.id,
        due_date: form.dueDate,
        checklist: checklist.map((item, index) => ({
          label: item.label,
          done: item.done,
          sort_order: index + 1,
        })),
      });
      await navigateTo("/tasks");
    } catch (error) {
      applyServerErrors(error);
      console.error("タスクの作成に失敗しました:", error);
    } finally {
      isSubmitting.value = false;
    }
  };

  const loadCategories = async () => {
    try {
      const response = await $api.get<ApiCategoryResponse>("/api/categories");
      categoryRecords.value = response.data.data;
      form.category = categories.value[0] ?? "";
    } catch (error) {
      generalError.value = "カテゴリの取得に失敗しました。";
      console.error("カテゴリの取得に失敗しました:", error);
    }
  };

  onMounted(loadCategories);

  return {
    categories,
    priorities,
    statuses,
    form,
    errors,
    touched,
    submitted,
    generalError,
    isSubmitting,
    canSubmit,
    getToday,
    clearFieldError,
    submitTask,
    touchField,
    addChecklistItem,
    removeChecklistItem,
  };
};
