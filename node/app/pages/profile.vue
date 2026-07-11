<!-- プロフィールページ -->
<script setup lang="ts">
import AppHeader from "~/components/layouts/AppHeader.vue";
import ProfileCard from "~/components/profile/ProfileCard.vue";
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
      <ProfileCard
        :name="user?.name ?? ''"
        :email="user?.email ?? ''"
        @edit="openProfileModal"
        @changePassword="openPasswordModal"
      />
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
</style>
