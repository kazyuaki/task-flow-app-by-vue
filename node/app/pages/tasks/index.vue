<template>
  <main class="page">
    <header class="header">
      <h1>タスク一覧</h1>
      <NuxtLink to="/tasks/create">新規作成</NuxtLink>
    </header>
  </main>
</template>

<style scoped>
.page {
  padding: 32px;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}
</style>
