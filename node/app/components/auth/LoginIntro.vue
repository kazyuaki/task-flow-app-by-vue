<!-- ログインページのイントロダクションコンポーネント -->
<template>
  <section class="intro">
    <p class="eyebrow">Focus your day</p>
    <h1>今日やることを、すっきり整理。</h1>
    <p>
      タスク、カテゴリ、期限をひとつの流れで管理して、迷わず次の作業へ進めます。
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
