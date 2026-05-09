import { computed, reactive, ref } from "vue";
import { validateTaskForm } from "~/utils/validation/task";

import {
  TASK_CATEGORIES,
  TASK_PRIORITIES,
  TASK_STATUSES,
} from "~/constants/task";

export const useTaskCreateForm = () => {
  const statuses = TASK_STATUSES;
  const priorities = TASK_PRIORITIES;
  const categories = TASK_CATEGORIES;

  const config = useRuntimeConfig();

  const apiBaseUrl = process.server ? "http://nginx" : config.public.apiBaseUrl;

  const isSubmitting = ref(false);
  const generalError = ref("");

  const touched = reactive<Record<string, boolean>>({});
  const errors = reactive<Record<string, string[]>>({});

  // フォームの状態管理
  const form = reactive({
    title: "",
    description: "",
    status: "未着手",
    priority: "中",
    category: "開発",
    dueDate: "",
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

    return Boolean(hasRequiredFields && isValidDueDate && !isSubmitting.value);
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
      data?: {
        message?: string;
        errors?: Record<string, string[]>;
      };
    };
    const serverErrors = response.data?.errors;

    if (!serverErrors) {
      generalError.value =
        response.data?.message || "タスクの作成に失敗しました。";
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

  // フォーム送信処理
  const submitTask = async () => {
    if (!validateForm() || isSubmitting.value) return;

    isSubmitting.value = true;

    try {
      await $fetch("/api/tasks", {
        baseURL: apiBaseUrl,
        method: "POST",
        body: {
          user_id: 1,
          title: form.title,
          description: form.description,
          status: statuses.indexOf(form.status),
          priority: priorities.indexOf(form.priority),
          category_id: 1,
          due_date: form.dueDate,
        },
      });
      await navigateTo("/tasks");
    } catch (error) {
      applyServerErrors(error);
      console.error("タスクの作成に失敗しました:", error);
    } finally {
      isSubmitting.value = false;
    }
  };

  return {
    categories,
    priorities,
    statuses,
    form,
    errors,
    touched,
    generalError,
    isSubmitting,
    canSubmit,
    getToday,
    clearFieldError,
    submitTask,
    touchField,
  };
};
