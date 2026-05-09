<!-- タスク新規作成ページ -->
<script setup lang="ts">
import { computed, reactive, ref } from "vue";
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import {
  TASK_CATEGORIES,
  TASK_PRIORITIES,
  TASK_STATUSES,
} from "~/constants/task";

const statuses = TASK_STATUSES;
const priorities = TASK_PRIORITIES;
const categories = TASK_CATEGORIES;
const config = useRuntimeConfig();
const apiBaseUrl = process.server ? "http://nginx" : config.public.apiBaseUrl;
const isSubmitting = ref(false);
const generalError = ref("");
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

// フォームの入力内容が有効かどうかを判定
const canSubmit = computed(() => {
  return !isSubmitting.value;
});

const clearErrors = () => {
  generalError.value = "";

  Object.keys(errors).forEach((key) => {
    delete errors[key];
  });
};

const clearFieldError = (field: string) => {
  delete errors[field];
  generalError.value = "";
};

const addError = (field: string, message: string) => {
  errors[field] = [...(errors[field] || []), message];
};

const getToday = () => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, "0");
  const day = String(today.getDate()).padStart(2, "0");

  return `${year}-${month}-${day}`;
};

const validateForm = () => {
  clearErrors();

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

  return Object.keys(errors).length === 0;
};

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

  const fieldMap: Record<string, string> = {
    category_id: "category",
    due_date: "dueDate",
  };

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
</script>

<template>
  <main class="page">
    <AppHeader title="タスク新規作成" />

    <PageTitle
      eyebrow="Create Task"
      title="タスク新規作成"
      action-label="一覧へ戻る"
      action-to="/tasks"
      action-variant="secondary"
    />

    <section class="create-layout">
      <!-- 入力フォーム -->
      <form class="form-panel" @submit.prevent="submitTask">
        <p v-if="generalError" class="form-alert">
          {{ generalError }}
        </p>

        <div class="form-section">
          <h2>基本情報</h2>

          <label class="form-field">
            <span>タイトル</span>
            <input
              v-model="form.title"
              type="text"
              placeholder="例：タスク登録APIを作成する"
              :aria-invalid="Boolean(errors.title)"
              @input="clearFieldError('title')"
            />
            <small v-for="message in errors.title" :key="message" class="field-error">
              {{ message }}
            </small>
          </label>

          <label class="form-field">
            <span>説明</span>
            <textarea
              v-model="form.description"
              rows="5"
              maxlength="1000"
              placeholder="作業内容や完了条件を入力"
              :aria-invalid="Boolean(errors.description)"
              @input="clearFieldError('description')"
            />
            <small
              v-for="message in errors.description"
              :key="message"
              class="field-error"
            >
              {{ message }}
            </small>
          </label>
        </div>

        <div class="form-section">
          <h2>管理情報</h2>

          <div class="form-grid">
            <label class="form-field">
              <span>ステータス</span>
              <select
                v-model="form.status"
                :aria-invalid="Boolean(errors.status)"
                @change="clearFieldError('status')"
              >
                <option v-for="status in statuses" :key="status" :value="status">
                  {{ status }}
                </option>
              </select>
              <small
                v-for="message in errors.status"
                :key="message"
                class="field-error"
              >
                {{ message }}
              </small>
            </label>

            <label class="form-field">
              <span>優先度</span>
              <select
                v-model="form.priority"
                :aria-invalid="Boolean(errors.priority)"
                @change="clearFieldError('priority')"
              >
                <option
                  v-for="priority in priorities"
                  :key="priority"
                  :value="priority"
                >
                  {{ priority }}
                </option>
              </select>
              <small
                v-for="message in errors.priority"
                :key="message"
                class="field-error"
              >
                {{ message }}
              </small>
            </label>

            <label class="form-field">
              <span>カテゴリ</span>
              <select
                v-model="form.category"
                :aria-invalid="Boolean(errors.category)"
                @change="clearFieldError('category')"
              >
                <option
                  v-for="category in categories"
                  :key="category"
                  :value="category"
                >
                  {{ category }}
                </option>
              </select>
              <small
                v-for="message in errors.category"
                :key="message"
                class="field-error"
              >
                {{ message }}
              </small>
            </label>

            <label class="form-field">
              <span>期限</span>
              <input
                v-model="form.dueDate"
                type="date"
                :min="getToday()"
                :aria-invalid="Boolean(errors.dueDate)"
                @input="clearFieldError('dueDate')"
              />
              <small
                v-for="message in errors.dueDate"
                :key="message"
                class="field-error"
              >
                {{ message }}
              </small>
            </label>
          </div>
        </div>

        <div class="form-actions">
          <NuxtLink class="cancel-link" to="/tasks">キャンセル</NuxtLink>
          <button class="submit-button" type="submit" :disabled="!canSubmit">
            {{ isSubmitting ? "作成中..." : "作成する" }}
          </button>
        </div>
      </form>

      <!-- 入力内容プレビュー -->
      <aside class="preview-panel">
        <p class="preview-label">Preview</p>
        <h2>{{ form.title || "タスクタイトル" }}</h2>
        <p class="preview-description">
          {{ form.description || "ここにタスクの説明が表示されます。" }}
        </p>

        <dl class="preview-list">
          <div>
            <dt>ステータス</dt>
            <dd>{{ form.status }}</dd>
          </div>
          <div>
            <dt>優先度</dt>
            <dd>{{ form.priority }}</dd>
          </div>
          <div>
            <dt>カテゴリ</dt>
            <dd>{{ form.category }}</dd>
          </div>
          <div>
            <dt>期限</dt>
            <dd>{{ form.dueDate || "未設定" }}</dd>
          </div>
        </dl>
      </aside>
    </section>
  </main>
</template>

<style scoped>
/* ページ全体 */
.page {
  min-height: 100vh;
  padding: 32px;
  background:
    radial-gradient(
      circle at top left,
      rgba(45, 106, 79, 0.12),
      transparent 32%
    ),
    linear-gradient(135deg, #f8fbfa 0%, #eef6f2 100%);
}

/* 作成画面レイアウト */
.create-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 340px;
  gap: 24px;
  align-items: start;
}

/* 入力フォーム */
.form-panel,
.preview-panel {
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
}

.form-panel {
  display: grid;
  gap: 24px;
  padding: 24px;
}

.form-section {
  display: grid;
  gap: 16px;
}

.form-section h2 {
  margin: 0;
  color: #172033;
  font-size: 16px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(180px, 1fr));
  gap: 16px;
}

.form-field {
  display: grid;
  gap: 8px;
}

.form-field span {
  color: #475467;
  font-size: 13px;
  font-weight: 700;
}

.form-field input,
.form-field select,
.form-field textarea {
  width: 100%;
  min-height: 42px;
  padding: 0 12px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  color: #172033;
  font: inherit;
  background: #fff;
}

.form-field textarea {
  padding: 12px;
  resize: vertical;
  line-height: 1.6;
}

.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
  border-color: #2d6a4f;
  outline: 3px solid rgba(45, 106, 79, 0.12);
}

.form-field input[aria-invalid="true"],
.form-field select[aria-invalid="true"],
.form-field textarea[aria-invalid="true"] {
  border-color: #d92d20;
}

.form-field input[aria-invalid="true"]:focus,
.form-field select[aria-invalid="true"]:focus,
.form-field textarea[aria-invalid="true"]:focus {
  border-color: #d92d20;
  outline-color: rgba(217, 45, 32, 0.12);
}

.form-alert {
  margin: 0;
  padding: 12px 14px;
  border: 1px solid #fda29b;
  border-radius: 6px;
  color: #b42318;
  font-size: 14px;
  font-weight: 700;
  background: #fffbfa;
}

.field-error {
  color: #b42318;
  font-size: 12px;
  font-weight: 700;
  line-height: 1.5;
}

/* フォーム操作 */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding-top: 8px;
}

.cancel-link,
.submit-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 42px;
  padding: 0 16px;
  border-radius: 8px;
  font: inherit;
  font-weight: 700;
}

.cancel-link {
  border: 1px solid #d0d5dd;
  color: #344054;
  text-decoration: none;
  background: #fff;
}

.submit-button {
  border: 0;
  color: #fff;
  background: #2d6a4f;
  box-shadow: 0 8px 18px rgba(45, 106, 79, 0.22);
  cursor: pointer;
  transition:
    transform 0.2s ease,
    background 0.2s ease,
    box-shadow 0.2s ease;
}

.submit-button:hover:not(:disabled) {
  background: #24563f;
  transform: translateY(-1px);
  box-shadow: 0 10px 22px rgba(45, 106, 79, 0.28);
}

.submit-button:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

/* 入力内容プレビュー */
.preview-panel {
  position: sticky;
  top: 24px;
  padding: 22px;
}

.preview-label {
  margin: 0 0 10px;
  color: #2d6a4f;
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
}

.preview-panel h2 {
  margin: 0;
  color: #172033;
  font-size: 20px;
}

.preview-description {
  margin: 14px 0 20px;
  color: #667085;
  font-size: 14px;
  line-height: 1.7;
}

.preview-list {
  display: grid;
  gap: 12px;
  margin: 0;
}

.preview-list div {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding-top: 12px;
  border-top: 1px solid #eaecf0;
}

.preview-list dt {
  color: #667085;
  font-size: 13px;
  font-weight: 700;
}

.preview-list dd {
  margin: 0;
  color: #172033;
  font-size: 13px;
  font-weight: 700;
}

@media (max-width: 860px) {
  .create-layout,
  .form-grid {
    grid-template-columns: 1fr;
  }

  .preview-panel {
    position: static;
  }
}
</style>
