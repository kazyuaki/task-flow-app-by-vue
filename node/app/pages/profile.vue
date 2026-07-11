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
  <main class="page">
    <AppHeader />
    <section class="profile-page">
      <ProfileCard
        :name="user?.name ?? ''"
        :email="user?.email ?? ''"
        @edit="openProfileModal"
        @changePassword="openPasswordModal"
      />
    </section>

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
  </main>
</template>

<style scoped>
.page {
  min-height: 100vh;
  padding: 32px;
  background:
    radial-gradient(
      circle at top left,
      rgba(45, 106, 79, 0.12),
      transparent 32%
    ),
    linear-gradient(135deg, #f8fbfa 0%, #eef6f2 100%);
}

.profile-page {
  display: flex;
  justify-content: center;
  padding: 40px 16px;
}

@media (max-width: 760px) {
  .page {
    padding: 12px;
  }

  .profile-page {
    padding: 32px 10px;
  }
}
</style>
