<template>
  <main class="page">
    <AppHeader />

    <div class="login-layout">
      <LoginIntro />
      <LoginFormPanel />
    </div>
  </main>
</template>

<script setup lang="ts">
import LoginFormPanel from '../components/auth/LoginFormPanel.vue'
import LoginIntro from '../components/auth/LoginIntro.vue'
import AppHeader from '../components/layouts/AppHeader.vue'
</script>

<style scoped>
.page {
  min-height: 100vh;
  color: #172033;
  background:
    radial-gradient(
      circle at 12% 18%,
      rgba(45, 106, 79, 0.14),
      transparent 30%
    ),
    linear-gradient(135deg, #f6f8fb 0%, #edf4f1 48%, #f7f3ea 100%);
}

.login-layout {
  min-height: calc(100vh - 72px);
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(320px, 420px);
  align-items: center;
  gap: clamp(32px, 8vw, 96px);
  width: min(1120px, calc(100% - 40px));
  margin: 0 auto;
  padding: 56px 0;
}

@media (max-width: 760px) {
  .login-layout {
    min-height: auto;
    grid-template-columns: 1fr;
    padding: 40px 0;
  }
}
</style>
