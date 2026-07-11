<!-- プロフィールページ -->
<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import ProfileEditModal from "~/components/profile/ProfileEditModal.vue";
import PasswordChangeModal from "~/components/profile/PasswordChangeModal.vue";

const { user, fetchUser } = useAuth();
const { $api } = useNuxtApp();

const showProfileModal = ref(false);
const showPasswordModal = ref(false);

onMounted(async () => {
  if (!user.value) {
    await fetchUser();
  }
});

const openProfileModal = () => {
  showProfileModal.value = true;
};
const closeProfileModal = () => {
  showProfileModal.value = false;
};

const openPasswordModal = () => {
  showPasswordModal.value = true;
};
const closePasswordModal = () => {
  showPasswordModal.value = false;
};

const handleProfileSave = async (payload: { name: string; email: string }) => {
  try {
    await $api.put("/api/profile", {
      name: payload.name,
      email: payload.email,
    });

    await fetchUser();

    showProfileModal.value = false;
  } catch (error) {
    console.error(error);
  }
};

const handlePasswordSave = async (payload: {
  currentPassword: string;
  password: string;
  passwordConfirmation: string;
}) => {
  try {
    await $api.put("/api/profile/password", {
      current_password: payload.currentPassword,
      password: payload.password,
      password_confirmation: payload.passwordConfirmation,
    });

    showPasswordModal.value = false;
  } catch (error) {
    console.error(error);
  }
};
</script>

<template>
  <div class="page">
    <AppHeader />
    <main class="profile-page">
      <section class="profile-card">
        <h1>プロフィール</h1>

        <div class="profile-item">
          <span class="label">名前</span>
          <span class="value">{{ user?.name }}</span>
        </div>

        <div class="profile-item">
          <span class="label">メールアドレス</span>
          <span class="value">{{ user?.email }}</span>
        </div>

        <div class="profile-item">
          <span class="label">パスワード</span>
          <span class="value">••••••••</span>
        </div>

        <div class="button-group">
          <button type="button" @click="openProfileModal">
            プロフィール編集
          </button>

          <button
            type="button"
            class="secondary"
            @click="openPasswordModal"
          >
            パスワード変更
          </button>
        </div>
      </section>
    </main>

    <ProfileEditModal
      :is-open="showProfileModal"
      :name="user?.name ?? ''"
      :email="user?.email ?? ''"
      @close="closeProfileModal"
      @save="handleProfileSave"
    />
    <PasswordChangeModal
      :is-open="showPasswordModal"
      @close="closePasswordModal"
      @save="handlePasswordSave"
    />
  </div>
</template>

<style scoped>
.page {
  min-height: 100vh;
  background: linear-gradient(135deg, #edf6f3 0%, #f7faf9 55%, #edf6f3 100%);
}

.profile-page {
  display: flex;
  justify-content: center;
  padding: 40px 16px;
}

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
