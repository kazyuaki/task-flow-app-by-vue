<!-- components/common/AppToast.vue -->
<!-- トースト共通コンポーネント -->
<script setup lang="ts">
const { toast } = useToast();
</script>

<template>
  <Transition name="toast">
    <div
      v-if="toast.show"
      :class="['toast-message', toast.type]"
      :role="toast.type === 'error' ? 'alert' : 'status'"
      aria-live="polite"
    >
      {{ toast.message }}
    </div>
  </Transition>
</template>

<style scoped>
.toast-message {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 2000;
  width: max-content;
  max-width: min(360px, calc(100vw - 48px));
  box-sizing: border-box;
  margin: 0;
  padding: 14px 16px;
  border: 1px solid;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 700;
  box-shadow: 0 16px 40px rgba(16, 24, 40, 0.16);
}

.success {
  border-color: #b7e4c7;
  color: #1b4332;
  background: #f1fff6;
}

.error {
  border-color: #fecaca;
  color: #991b1b;
  background: #fef2f2;
}

.toast-enter-active,
.toast-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(8px);
}

@media (max-width: 600px) {
  .toast-message {
    top: 16px;
    right: 16px;
    max-width: calc(100vw - 32px);
  }
}
</style>
