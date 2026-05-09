<!-- タスクのサマリーを表示するグリッドコンポーネント -->
<script setup lang="ts">
defineProps<{
  summary: {
    total: number;
    notStarted: number;
    inProgress: number;
    completed: number;
  };
}>();
</script>

<template>
  <section class="summary-grid">
    <div class="summary-card summary-card--total">
      <span>全件</span>
      <strong>{{ summary.total }}</strong>
    </div>
    <div class="summary-card summary-card--not-started">
      <span>未着手</span>
      <strong>{{ summary.notStarted }}</strong>
    </div>
    <div class="summary-card summary-card--in-progress">
      <span>進行中</span>
      <strong>{{ summary.inProgress }}</strong>
    </div>
    <div class="summary-card summary-card--completed">
      <span>完了</span>
      <strong>{{ summary.completed }}</strong>
    </div>
  </section>
</template>

<style scoped>
.summary-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.summary-card {
  padding: 18px 20px;
  border: 1px solid #eaecf0;
  border-radius: 12px;
  color: #172033;
  box-shadow: 0 10px 24px rgba(16, 24, 40, 0.06);
}

.summary-card span {
  display: block;
  margin-bottom: 8px;
  color: #667085;
  font-size: 13px;
  font-weight: 700;
}

.summary-card strong {
  font-size: 28px;
  line-height: 1;
}

.summary-card--total {
  background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
  border: 1px solid #e5e7eb;
}

.summary-card--not-started {
  background: linear-gradient(135deg, #fff4ed 0%, #ffe8d9 100%);
  border: 1px solid #ffd8bf;
}

.summary-card--not-started strong {
  color: #d9480f;
}

.summary-card--in-progress {
  background: linear-gradient(135deg, #eef6ff 0%, #dceeff 100%);
  border: 1px solid #c5e1ff;
}

.summary-card--in-progress strong {
  color: #1c7ed6;
}

.summary-card--completed {
  background: linear-gradient(135deg, #edf7f1 0%, #dff3e7 100%);
  border: 1px solid #cdebd8;
}

.summary-card--completed strong {
  color: #2d6a4f;
}
</style>
