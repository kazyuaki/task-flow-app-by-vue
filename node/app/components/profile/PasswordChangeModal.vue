<!-- パスワード変更モーダル -->
<script setup lang="ts">
type Props = {
  isOpen: boolean;
};

defineProps<Props>();

const emit = defineEmits<{
  close: [];
  save: [
    {
      currentPassword: string;
      password: string;
      passwordConfirmation: string;
    },
  ];
}>();

const form = reactive({
  currentPassword: "",
  password: "",
  passwordConfirmation: "",
});

const resetForm = () => {
  form.currentPassword = "";
  form.password = "";
  form.passwordConfirmation = "";
};

const handleClose = () => {
  resetForm();
  emit("close");
};

const handleSubmit = () => {
  emit("save", {
    currentPassword: form.currentPassword,
    password: form.password,
    passwordConfirmation: form.passwordConfirmation,
  });
};
</script>

<template>
  <Teleport to="body">
    <div
      v-if="isOpen"
      class="modal-overlay"
      @click.self="handleClose"
    >
      <section
        class="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="password-modal-title"
      >
        <h2 id="password-modal-title">パスワード変更</h2>

        <form
          class="modal-form"
          @submit.prevent="handleSubmit"
        >
          <label class="form-item">
            <span>現在のパスワード</span>
            <input
              v-model="form.currentPassword"
              type="password"
              autocomplete="current-password"
            />
          </label>
          <label class="form-item">
            <span>新しいパスワード</span>
            <input
              v-model="form.password"
              type="password"
              autocomplete="new-password"
            />
          </label>
          <label class="form-item">
            <span>新しいパスワード（確認）</span>
            <input
              v-model="form.passwordConfirmation"
              type="password"
              autocomplete="new-password"
            />
          </label>

          <div class="modal-buttons">
            <button
              type="button"
              class="cancel-button"
              @click="handleClose"
            >
              キャンセル
            </button>
            <button
              type="submit"
              class="save-button"
            >
              変更する
            </button>
          </div>
        </form>
      </section>
    </div>
  </Teleport>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  background: rgb(0 0 0 / 40%);
}

.modal {
  width: 100%;
  max-width: 480px;
  padding: 24px;
  border-radius: 12px;
  background: #fff;
  box-shadow: 0 20px 50px rgb(0 0 0 / 18%);
}

.modal h2 {
  margin: 0 0 24px;
  font-size: 24px;
}

.modal-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-item {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-item span {
  font-size: 14px;
  font-weight: 600;
  color: #666;
}

.form-item input {
  width: 100%;
  padding: 12px;
  border: 1px solid #d5d9dd;
  border-radius: 8px;
  box-sizing: border-box;
  font-size: 16px;
}

.form-item input:focus {
  border-color: #2f755d;
  outline: none;
  box-shadow: 0 0 0 3px rgb(47 117 93 / 12%);
}

.modal-buttons {
  display: flex;
  gap: 12px;
  margin-top: 8px;
}

.modal-buttons button {
  flex: 1;
  padding: 12px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.cancel-button {
  background: #f3f4f6;
  color: #333;
}

.save-button {
  background: #2f755d;
  color: #fff;
}

.save-button:hover {
  opacity: 0.9;
}
</style>