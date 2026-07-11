<!-- プロフィールカード -->
<script setup lang="ts">
type Props = {
  name: string;
  email: string;
};

defineProps<Props>();

defineEmits<{
  edit: [];
  changePassword: [];
}>();
</script>

<template>
  <section class="profile-card">
    <h1>プロフィール</h1>

    <div class="profile-item">
      <span class="label">名前</span>
      <span class="value">{{ name }}</span>
    </div>

    <div class="profile-item">
      <span class="label">メールアドレス</span>
      <span class="value">{{ email }}</span>
    </div>

    <div class="profile-item">
      <span class="label">パスワード</span>
      <span class="value">••••••••</span>
    </div>

    <div class="button-group">
      <button type="button" @click="$emit('edit')">プロフィール編集</button>

      <button type="button" class="secondary" @click="$emit('changePassword')">
        パスワード変更
      </button>
    </div>
  </section>
</template>

<style scoped>
.profile-card {
  width: 100%;
  max-width: 560px;
  padding: 24px;
  border: 1px solid #ddd;
  border-radius: 12px;
  background: #fff;
}

.profile-card h1 {
  margin-bottom: 24px;
}

.profile-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
  margin-bottom: 20px;
}

.label {
  font-size: 14px;
  color: #666;
}

.value {
  font-size: 16px;
}

button {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

.button-group {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.secondary {
  background: #f3f4f6;
  color: #333;
}
</style>
