<!-- ページタイトル　-->
<script setup lang="ts">
defineProps<{
  eyebrow: string;
  title: string;
  actionLabel?: string;
  actionTo?: string;
  actionVariant?: "primary" | "secondary";

  secondaryActionLabel?: string;
  secondaryActionTo?: string;
  secondaryActionVariant?: "primary" | "secondary";
}>();
</script>

<template>
  <section class="page-header">
    <div>
      <p class="eyebrow">{{ eyebrow }}</p>
      <h1>{{ title }}</h1>
    </div>
    <div class="action-group">
      <NuxtLink
        v-if="secondaryActionLabel && secondaryActionTo"
        :class="[
          'action-link',
          `action-link--${secondaryActionVariant || 'secondary'}`,
        ]"
        :to="secondaryActionTo"
      >
        {{ secondaryActionLabel }}
      </NuxtLink>

      <NuxtLink
        v-if="actionLabel && actionTo"
        :class="[
          'action-link',
          `action-link--${actionVariant || 'primary'}`,
        ]"
        :to="actionTo"
      >
        {{ actionLabel }}
      </NuxtLink>
    </div>
  </section>
</template>

<style scoped>
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  margin-top: 40px;
  margin-bottom: 24px;
}

.eyebrow {
  margin: 0 0 8px;
  color: #2d6a4f;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
}

.action-group {
  display: flex;
  align-items: center;
  gap: 12px;
}

.page-header h1 {
  margin: 0;
  font-size: 32px;
}

.action-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 42px;
  padding: 0 16px;
  border-radius: 8px;
  font-weight: 700;
  text-decoration: none;
}

.action-link--primary {
  color: #fff;
  background: #2d6a4f;
  box-shadow: 0 8px 18px rgba(45, 106, 79, 0.22);
  transition:
    transform 0.2s ease,
    background 0.2s ease,
    box-shadow 0.2s ease;
}

.action-link--primary:hover {
  background: #24563f;
  transform: translateY(-1px);
  box-shadow: 0 10px 22px rgba(45, 106, 79, 0.28);
}

.action-link--secondary {
  border: 1px solid #d0d5dd;
  color: #344054;
  background: rgba(255, 255, 255, 0.86);
}
</style>
