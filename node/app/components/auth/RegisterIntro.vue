<!-- components/auth/RegisterIntro.vue -->
<!--　会員登録ページのイントロダクションコンポーネント-->

<template>
  <section class="intro">
    <p class="eyebrow">Start your flow</p>
    <h1>タスク管理を、今日から始めよう。</h1>
    <p>
      アカウントを作成して、タスク・期限・優先度を自分専用のワークスペースで管理できます。
    </p>
  </section>
</template>
<style scoped>
.intro {
  max-width: 560px;
}

.eyebrow {
  margin: 0 0 12px;
  color: #2d6a4f;
  font-size: 13px;
  font-weight: 800;
  letter-spacing: 0;
  text-transform: uppercase;
}

.intro h1 {
  margin: 0;
  font-size: clamp(36px, 6vw, 64px);
  line-height: 1.08;
}

.intro p:last-child {
  max-width: 480px;
  margin: 20px 0 0;
  color: #536071;
  font-size: 17px;
  line-height: 1.8;
}

@media (max-width: 760px) {
  .intro h1 {
    font-size: 38px;
  }
}
</style>
