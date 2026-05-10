<!-- components/tasks/TaskDetailStateMessage.vue -->
<!-- タスク詳細ページの状態メッセージ -->
<script setup lang="ts">
defineProps<{
  title?: string;
  message: string;
}>();
</script>

<template>
  <section class="state-message">
    <h2 v-if="title">{{ title }}</h2>
    <p>{{ message }}</p>
    <NuxtLink class="secondary-link" to="/tasks">一覧へ戻る</NuxtLink>
  </section>
</template>

<style scoped>
.state-message {
  display: grid;
  gap: 14px;
  padding: 24px;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
}

.state-message h2 {
  margin: 0;
  color: #172033;
  font-size: 16px;
}

.state-message p {
  margin: 0;
  color: #667085;
  font-size: 15px;
  line-height: 1.7;
}

.secondary-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: fit-content;
  min-height: 42px;
  padding: 0 16px;
  border: 1px solid #d0d5dd;
  border-radius: 8px;
  color: #344054;
  font-size: 15px;
  font-weight: 800;
  text-decoration: none;
  background: #fff;
}

@media (max-width: 640px) {
  .secondary-link {
    width: 100%;
  }
}
</style>
