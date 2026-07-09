<!-- タスク一覧のフィルターパネル -->
<script setup lang="ts">
import type { TASK_PRIORITIES, TASK_STATUSES } from "~/constants/task";
import type { TaskCategory } from "~/types/task";

defineProps<{
  statuses: typeof TASK_STATUSES;
  categories: readonly TaskCategory[];
  priorities: typeof TASK_PRIORITIES;
}>();

const keyword = defineModel<string>("keyword", { required: true });
const selectedStatus = defineModel<string>("selectedStatus", { required: true });
const selectedCategory = defineModel<string>("selectedCategory", { required: true });
const selectedPriority = defineModel<string>("selectedPriority", { required: true });
</script>

<template>
  <section class="control-panel">
    <div class="control-group control-group--search">
      <div class="control-heading">
        <h2>検索</h2>
      </div>

      <label class="control-field control-field--wide">
        <span>キーワード</span>
        <input
          v-model="keyword"
          type="text"
          placeholder="例：API、ログイン、画面"
        />
      </label>
    </div>

    <div class="control-group">
      <div class="control-heading">
        <h2>絞り込み</h2>
      </div>

      <div class="filter-grid">
        <label class="control-field">
          <span>ステータス</span>
          <select v-model="selectedStatus">
            <option value="">すべて</option>
            <option v-for="status in statuses" :key="status" :value="status">
              {{ status }}
            </option>
          </select>
        </label>
        <label class="control-field">
          <span>カテゴリ</span>
          <select v-model="selectedCategory">
            <option value="">すべて</option>
            <option
              v-for="category in categories"
              :key="category"
              :value="category"
            >
              {{ category }}
            </option>
          </select>
        </label>
        <label class="control-field">
          <span>優先度</span>
          <select v-model="selectedPriority">
            <option value="">すべて</option>
            <option
              v-for="priority in priorities"
              :key="priority"
              :value="priority"
            >
              {{ priority }}
            </option>
          </select>
        </label>
      </div>
    </div>
  </section>
</template>

<style scoped>
.control-panel {
  display: grid;
  gap: 20px;
  margin-bottom: 32px;
  padding: 20px;
  border: 1px solid #eaecf0;
  background: rgba(255, 255, 255, 0.92);
  box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
  border-radius: 8px;
}

.control-group {
  display: grid;
  gap: 14px;
}

.control-group--search {
  padding-bottom: 20px;
  border-bottom: 1px solid #eaecf0;
}

.control-heading {
  display: grid;
  gap: 4px;
}

.control-heading h2 {
  margin: 0;
  color: #172033;
  font-size: 15px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(180px, 1fr));
  gap: 16px;
}

.control-field {
  display: grid;
  gap: 8px;
}

.control-field--wide {
  max-width: 640px;
}

.control-field span {
  color: #475467;
  font-size: 13px;
  font-weight: 700;
}

.control-field input,
.control-field select {
  min-height: 42px;
  padding: 0 12px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  font: inherit;
}

@media (max-width: 760px) {
  .filter-grid {
    grid-template-columns: 1fr;
  }
}
</style>
