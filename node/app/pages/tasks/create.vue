<!-- タスク新規作成ページ -->
<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import PageTitle from "~/components/common/PageTitle.vue";
import { useTaskCreateForm } from "~/composables/useTaskCreateForm";
import TaskCreateFormPanel from "~/components/tasks/TaskCreateFormPanel.vue";
import TaskCreatePreview from "~/components/tasks/TaskCreatePreview.vue";
import type { TaskCategory } from "~/types/task";
import CategoryCreateModal from "~/components/categories/CategoryCreateModal.vue";

definePageMeta({
  middleware: "auth",
});

const {
  statuses,
  priorities,
  categories,
  form,
  errors,
  touched,
  submitted,
  generalError,
  isSubmitting,
  canSubmit,
  getToday,
  createCategory,
  clearFieldError,
  submitTask,
  touchField,
  addChecklistItem,
  removeChecklistItem,
} = useTaskCreateForm();

const isCategoryModalOpen = ref(false);
const isCategorySubmitting = ref(false);
const categoryErrorMessage = ref("");
const toastMessage = ref("");
let toastTimer: ReturnType<typeof setTimeout> | null = null;

const openCategoryModal = () => {
  categoryErrorMessage.value = "";
  isCategoryModalOpen.value = true;
};

const closeCategoryModal = () => {
  isCategoryModalOpen.value = false;
  categoryErrorMessage.value = "";
};

const showToast = (message: string) => {
  toastMessage.value = message;

  if (toastTimer) {
    clearTimeout(toastTimer);
  }

  toastTimer = setTimeout(() => {
    toastMessage.value = "";
    toastTimer = null;
  }, 3000);
};

const handleCategoryCreate = async (category: TaskCategory) => {
  if (isCategorySubmitting.value) return;

  isCategorySubmitting.value = true;
  categoryErrorMessage.value = "";

  const result = await createCategory(category);

  isCategorySubmitting.value = false;

  if (result.errorMessage) {
    categoryErrorMessage.value = result.errorMessage;
    return;
  }

  closeCategoryModal();
  showToast(`カテゴリ「${result.category}」を作成しました。`);
};

onBeforeUnmount(() => {
  if (toastTimer) {
    clearTimeout(toastTimer);
  }
});
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
      <TaskCreateFormPanel
        :form="form"
        :statuses="statuses"
        :priorities="priorities"
        :categories="categories"
        :errors="errors"
        :touched="touched"
        :submitted="submitted"
        :general-error="generalError"
        :is-submitting="isSubmitting"
        :can-submit="canSubmit"
        :due-date-min="getToday()"
        @submit="submitTask"
        @clear-field="clearFieldError"
        @touch-field="touchField"
        @add-checklist-item="addChecklistItem"
        @remove-checklist-item="removeChecklistItem"
        @open-category-modal="openCategoryModal"
      />

      <TaskCreatePreview
        :title="form.title"
        :description="form.description"
        :status="form.status"
        :priority="form.priority"
        :category="form.category"
        :due-date="form.dueDate"
        :checklist="form.checklist"
      />
    </section>

    <CategoryCreateModal
      :open="isCategoryModalOpen"
      :error-message="categoryErrorMessage"
      :is-submitting="isCategorySubmitting"
      @close="closeCategoryModal"
      @create="handleCategoryCreate"
    />

    <Transition name="toast">
      <p v-if="toastMessage" class="toast-message" role="status">
        {{ toastMessage }}
      </p>
    </Transition>
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
  min-width: 0;
}

.toast-message {
  position: fixed;
  right: 24px;
  bottom: 24px;
  z-index: 1100;
  max-width: min(360px, calc(100vw - 48px));
  box-sizing: border-box;
  margin: 0;
  padding: 14px 16px;
  border: 1px solid #b7e4c7;
  border-radius: 8px;
  color: #1b4332;
  font-size: 14px;
  font-weight: 800;
  background: #f1fff6;
  box-shadow: 0 16px 40px rgba(16, 24, 40, 0.16);
}

.toast-enter-active,
.toast-leave-active {
  transition:
    opacity 0.18s ease,
    transform 0.18s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(8px);
}

@media (max-width: 860px) {
  .create-layout {
    grid-template-columns: 1fr;
  }

  .toast-message {
    right: 16px;
    bottom: 16px;
    max-width: calc(100vw - 32px);
  }
}
</style>
