<!-- components/tasks/TaskCreatePreview.vue -->
<!-- タスク作成フォームの入力内容プレビュー -->
<script setup lang="ts">
defineProps<{
  title: string;
  description: string;
  status: string;
  priority: string;
  category: string;
  dueDate: string;
}>();
</script>

<template>
  <aside class="preview-panel">
    <p class="preview-label">Preview</p>
    <h2>{{ title || "タスクタイトル" }}</h2>
    <p class="preview-description">
      {{ description || "ここにタスクの説明が表示されます。" }}
    </p>

    <dl class="preview-list">
      <dt>ステータス</dt>
      <dd>{{ status }}</dd>

      <dt>優先度</dt>
      <dd>{{ priority }}</dd>

      <dt>カテゴリ</dt>
      <dd>{{ category }}</dd>

      <dt>期限</dt>
      <dd>{{ dueDate || "未設定" }}</dd>
    </dl>
  </aside>
</template>

<style scoped>
.preview-panel {
  box-sizing: border-box;
  position: sticky;
  top: 24px;
  width: 100%;
  min-width: 0;
  padding: 22px;
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
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
  grid-template-columns: auto minmax(0, 1fr);
  gap: 12px;
  margin: 0;
}

.preview-list dt,
.preview-list dd {
  margin: 0;
  padding-top: 12px;
  border-top: 1px solid #eaecf0;
}

.preview-list dt {
  color: #667085;
  font-size: 13px;
  font-weight: 700;
}

.preview-list dd {
  text-align: right;
  overflow-wrap: anywhere;
  color: #172033;
  font-size: 13px;
  font-weight: 700;
}

@media (max-width: 860px) {
  .preview-panel {
    position: static;
  }
}
</style>
