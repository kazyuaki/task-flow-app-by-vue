<!-- components/common/ConfirmDialog.vue -->
<!-- 汎用的な確認ダイアログコンポーネント -->
<script setup lang="ts">
defineProps<{
  open: boolean;
  title: string;
  message?: string;
  confirmText?: string;
  cancelText?: string;
  loading?: boolean;
}>();

const emit = defineEmits<{
  confirm: [];
  close: [];
}>();
</script>

<template>
  <Teleport to="body">
    <div v-if="open" class="dialog-overlay" @click.self="emit('close')">
      <div class="dialog">
        <h2>{{ title }}</h2>
        <p v-if="message" class="dialog-message">
          {{ message }}
        </p>

        <div class="dialog-actions">
          <button
            type="button"
            class="cancel-button"
            :disabled="loading"
            @click="emit('close')"
          >
            {{ cancelText || "キャンセル" }}
          </button>

          <button
            type="button"
            class="confirm-button"
            :disabled="loading"
            @click="emit('confirm')"
          >
            {{ loading ? "処理中..." : confirmText || "実行する" }}
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
.dialog-overlay {
  position: fixed;

  inset: 0;

  z-index: 1000;

  display: flex;

  align-items: center;

  justify-content: center;

  padding: 24px;

  background: rgba(15, 23, 42, 0.45);

  backdrop-filter: blur(2px);
}

.dialog {
  width: 100%;

  max-width: 420px;

  padding: 24px;

  border-radius: 16px;

  background: #fff;

  box-shadow: 0 24px 48px rgba(15, 23, 42, 0.18);

  display: grid;

  gap: 16px;
}

.dialog h2 {
  margin: 0;

  color: #172033;

  font-size: 22px;
}

.dialog-message {
  margin: 0;

  color: #667085;

  font-size: 15px;

  line-height: 1.7;
}

.dialog-actions {
  display: flex;

  justify-content: flex-end;

  gap: 12px;

  padding-top: 8px;
}

.cancel-button,
.confirm-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 42px;
  padding: 0 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 800;
  cursor: pointer;
  transition:
    background 0.2s ease,
    transform 0.2s ease;
}

.cancel-button {
  border: 1px solid #d0d5dd;
  color: #344054;
  background: #fff;
}

.confirm-button {
  border: 1px solid #f4c7c3;
  color: #b42318;
  background: #fef3f2;
}

.confirm-button:hover:not(:disabled) {
  background: #fee4e2;
  transform: translateY(-1px);
}

.cancel-button:hover:not(:disabled) {
  background: #f9fafb;
}

.cancel-button:disabled,
.confirm-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
