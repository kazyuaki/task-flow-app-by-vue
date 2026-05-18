import type { TaskCreateForm, TaskPriority, TaskStatus } from "~/types/task";

type ValidateTaskFormParams = {
  form: TaskCreateForm;
  statuses: readonly TaskStatus[];
  priorities: readonly TaskPriority[];

  getToday: () => string;
  addError: (field: string, message: string) => void;
};

export const validateTaskForm = ({
  form,
  statuses,
  priorities,
  getToday,
  addError,
}: ValidateTaskFormParams) => {
  if (!form.title.trim()) {
    addError("title", "タイトルを入力してください");
  }
  if (form.title.length > 255) {
    addError("title", "タイトルは255文字以内で入力してください");
  }
  if (!form.description.trim()) {
    addError("description", "説明を入力してください");
  }
  if (form.description.length > 1000) {
    addError("description", "説明は1000文字以内で入力してください");
  }
  if (!statuses.includes(form.status)) {
    addError("status", "ステータスを選択してください");
  }
  if (!priorities.includes(form.priority)) {
    addError("priority", "優先度を選択してください");
  }
  if (!form.dueDate) {
    addError("dueDate", "期限日を入力してください");
  } else if (Number.isNaN(Date.parse(form.dueDate))) {
    addError("dueDate", "期限日は正しい日付形式で入力してください");
  } else if (form.dueDate < getToday()) {
    addError("dueDate", "期限日は今日以降の日付を入力してください");
  }

  form.checklist.forEach((item, index) => {
    const label = item.label.trim();

    if (label.length > 255) {
      addError(
        `checklist.${index}.label`,
        "チェックリストの項目は255文字以内で入力してください",
      );
    }
  });
};
