<!-- components/categories/CategoryCreateModal.vue -->
<!-- カテゴリ作成モーダル -->

<script setup lang="ts">
import { ref, watch } from "vue";
import type { TaskCategory } from "~/types/task";

const props = defineProps<{
  open: boolean;
  errorMessage: string;
  isSubmitting: boolean;
}>();

const emit = defineEmits<{
  close: [];
  create: [category: TaskCategory];
}>();

const categoryName = ref("");
const localErrorMessage = ref("");

watch(
  () => props.open,
  (open) => {
    if (open) {
      categoryName.value = "";
      localErrorMessage.value = "";
    }
  },
);

const handleClose = () => {
  emit("close");
};

const handleSubmit = () => {
  const name = categoryName.value.trim();

  if (!name) {
    localErrorMessage.value = "カテゴリ名を入力してください。";
    return;
  }

  localErrorMessage.value = "";
  emit("create", name as TaskCategory);
};
</script>

<template>
  <Teleport to="body">
    <div v-if="open" class="modal-backdrop" @click.self="handleClose">
      <section class="modal-panel" role="dialog" aria-modal="true">
        <header class="modal-header">
          <h2>カテゴリ追加</h2>

          <button class="close-button" type="button" @click="handleClose">
            ×
          </button>
        </header>

        <form class="modal-form" @submit.prevent="handleSubmit">
          <label class="form-field">
            <span>カテゴリ名</span>
            <input
              v-model="categoryName"
              type="text"
              placeholder="例：買い物"
              maxlength="30"
            />
          </label>

          <p v-if="localErrorMessage || errorMessage" class="error-message">
            {{ localErrorMessage || errorMessage }}
          </p>

          <footer class="modal-actions">
            <button class="secondary-button" type="button" @click="handleClose">
              キャンセル
            </button>

            <button class="primary-button" type="submit" :disabled="isSubmitting">
              {{ isSubmitting ? "作成中..." : "作成する" }}
            </button>
          </footer>
        </form>
      </section>
    </div>
  </Teleport>
</template>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: grid;
  place-items: center;
  padding: 24px;
  background: rgba(16, 24, 40, 0.45);
}

.modal-panel {
  box-sizing: border-box;
  display: grid;
  gap: 20px;
  width: min(100%, 420px);
  padding: 24px;
  border-radius: 12px;
  background: #fff;
  box-shadow: 0 24px 60px rgba(16, 24, 40, 0.22);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.modal-header h2 {
  margin: 0;
  color: #172033;
  font-size: 20px;
}

.close-button {
  border: 0;
  color: #667085;
  font-size: 24px;
  line-height: 1;
  background: transparent;
  cursor: pointer;
}

.modal-form {
  display: grid;
  gap: 16px;
}

.form-field {
  display: grid;
  gap: 8px;
}

.form-field span {
  color: #344054;
  font-size: 14px;
  font-weight: 800;
}

.form-field input {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #d0d5dd;
  border-radius: 8px;
  padding: 12px 14px;
  color: #172033;
  font-size: 15px;
}

.error-message {
  margin: 0;
  color: #b42318;
  font-size: 14px;
  font-weight: 700;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.secondary-button,
.primary-button {
  min-height: 42px;
  padding: 0 16px;
  border-radius: 8px;
  font-weight: 800;
  cursor: pointer;
}

.secondary-button {
  border: 1px solid #d0d5dd;
  color: #344054;
  background: #fff;
}

.primary-button {
  border: 0;
  color: #fff;
  background: #2d6a4f;
}

.primary-button:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}
</style>
