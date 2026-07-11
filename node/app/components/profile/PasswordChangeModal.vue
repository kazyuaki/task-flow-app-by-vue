<!-- パスワード変更モーダル -->
<script setup lang="ts">
import { computed, reactive, watch } from "vue";

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

const errors = reactive({
  currentPassword: "",
  password: "",
  passwordConfirmation: "",
});

const touched = reactive({
  currentPassword: false,
  password: false,
  passwordConfirmation: false,
});

const validateCurrentPassword = () => {
  if (!form.currentPassword.trim()) {
    errors.currentPassword = "現在のパスワードを入力してください。";
  } else {
    errors.currentPassword = "";
  }
};

const validatePassword = () => {
  if (!form.password.trim()) {
    errors.password = "新しいパスワードを入力してください。";
  } else if (form.password.length < 8) {
    errors.password = "パスワードは8文字以上で入力してください。";
  } else {
    errors.password = "";
  }
};

const validatePasswordConfirmation = () => {
  if (!form.passwordConfirmation.trim()) {
    errors.passwordConfirmation = "確認用パスワードを入力してください。";
  } else if (form.passwordConfirmation !== form.password) {
    errors.passwordConfirmation = "パスワードが一致しません。";
  } else {
    errors.passwordConfirmation = "";
  }
};

const validateForm = () => {
  validateCurrentPassword();
  validatePassword();
  validatePasswordConfirmation();

  return (
    !errors.currentPassword && !errors.password && !errors.passwordConfirmation
  );
};

const canSubmit = computed(() => {
  return (
    form.currentPassword.trim() !== "" &&
    form.password.trim().length >= 8 &&
    form.passwordConfirmation === form.password
  );
});

const resetForm = () => {
  form.currentPassword = "";
  form.password = "";
  form.passwordConfirmation = "";
  errors.currentPassword = "";
  errors.password = "";
  errors.passwordConfirmation = "";
  touched.currentPassword = false;
  touched.password = false;
  touched.passwordConfirmation = false;
};

const handleClose = () => {
  resetForm();
  emit("close");
};

const handleCurrentPasswordBlur = () => {
  touched.currentPassword = true;
  validateCurrentPassword();
};

const handlePasswordBlur = () => {
  touched.password = true;
  validatePassword();
};

const handlePasswordConfirmationBlur = () => {
  touched.passwordConfirmation = true;
  validatePasswordConfirmation();
};

watch(
  () => form.currentPassword,
  () => {
    if (touched.currentPassword) {
      validateCurrentPassword();
    }
  },
);

watch(
  () => form.password,
  () => {
    if (touched.password) {
      validatePassword();
    }
    if (touched.passwordConfirmation) {
      validatePasswordConfirmation();
    }
  },
);

watch(
  () => form.passwordConfirmation,
  () => {
    if (touched.passwordConfirmation) {
      validatePasswordConfirmation();
    }
  },
);

const handleSubmit = () => {
  touched.currentPassword = true;
  touched.password = true;
  touched.passwordConfirmation = true;

  if (!validateForm()) {
    return;
  }

  emit("save", {
    currentPassword: form.currentPassword,
    password: form.password,
    passwordConfirmation: form.passwordConfirmation,
  });
};
</script>

<template>
  <Teleport to="body">
    <div v-if="isOpen" class="modal-overlay" @click.self="handleClose">
      <section
        class="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="password-modal-title"
      >
        <h2 id="password-modal-title">パスワード変更</h2>

        <form class="modal-form" @submit.prevent="handleSubmit">
          <label class="form-item">
            <span>現在のパスワード</span>
            <input
              v-model="form.currentPassword"
              type="password"
              autocomplete="current-password"
              @blur="handleCurrentPasswordBlur"
            />
            <p
              v-if="touched.currentPassword && errors.currentPassword"
              class="error-message"
            >
              {{ errors.currentPassword }}
            </p>
          </label>
          <label class="form-item">
            <span>新しいパスワード</span>
            <input
              v-model="form.password"
              type="password"
              autocomplete="new-password"
              @blur="handlePasswordBlur"
            />
            <p v-if="touched.password && errors.password" class="error-message">
              {{ errors.password }}
            </p>
          </label>
          <label class="form-item">
            <span>新しいパスワード（確認）</span>
            <input
              v-model="form.passwordConfirmation"
              type="password"
              autocomplete="new-password"
              @blur="handlePasswordConfirmationBlur"
            />
            <p
              v-if="touched.passwordConfirmation && errors.passwordConfirmation"
              class="error-message"
            >
              {{ errors.passwordConfirmation }}
            </p>
          </label>

          <div class="modal-buttons">
            <button type="button" class="cancel-button" @click="handleClose">
              キャンセル
            </button>
            <button type="submit" class="save-button" :disabled="!canSubmit">
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

.error-message {
  margin: 4px 0 0;
  color: #e03131;
  font-size: 13px;
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

.save-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
