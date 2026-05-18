<!-- AppHeader.vue -->  
<!-- アプリ全体のヘッダーコンポーネント -->

<script setup lang="ts">
const route = useRoute();

const { isAuthenticated, logout } = useAuth();
const isTasksPage = computed(() => route.path === "/tasks");
const isCreatePage = computed(() => route.path === "/tasks/create");
const handleLogout = async () => {
  await logout();
};
</script>

<template>
  <header class="site-header">
    <NuxtLink class="brand" to="/tasks">
      <span class="brand-mark">T</span>
      <span>Task Flow</span>
    </NuxtLink>
    <nav class="header-nav" aria-label="メインナビゲーション">
      <NuxtLink v-if="!isTasksPage" to="/tasks">タスク一覧</NuxtLink>
      <NuxtLink v-if="!isCreatePage" to="/tasks/create">新規作成</NuxtLink>
      <button v-if="isAuthenticated" class="logout-button" @click="handleLogout">
        ログアウト
      </button>
    </nav>
  </header>
</template>
<style scoped>
.site-header {
  height: 72px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  padding: 0 clamp(20px, 5vw, 64px);
  border-bottom: 1px solid rgba(23, 32, 51, 0.08);
  background: rgba(255, 255, 255, 0.62);
  backdrop-filter: blur(16px);
}

.brand {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  color: inherit;
  font-size: 18px;
  font-weight: 800;
  text-decoration: none;
}

.brand-mark {
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: 8px;
  color: #fff;
  background: #2d6a4f;
}

.header-nav {
  display: flex;
  align-items: center;
  gap: 18px;
}

.logout-button {
  padding: 0;
  border: none;
  background: transparent;
  color: #485164;
  font-size: 14px;
  font-weight: 700;
  font-family: inherit;
  line-height: 1;
  cursor: pointer;
}

.logout-button:hover {
  opacity: 0.8;
}

.header-nav a {
  color: #485164;
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
}

@media (max-width: 760px) {
  .site-header {
    height: auto;
    align-items: flex-start;
    flex-direction: column;
    padding-top: 18px;
    padding-bottom: 18px;
  }

  .header-nav {
    width: 100%;
    justify-content: space-between;
  }
}
</style>
