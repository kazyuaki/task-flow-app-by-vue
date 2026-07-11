<!-- プロフィール編集モーダル -->
<script setup lang="ts">
import { computed, reactive, watch } from "vue";

type Props = {
  isOpen: boolean;
  name: string;
  email: string;
};

const props = defineProps<Props>();

const emit = defineEmits<{
  close: [];
  save: [{ name: string; email: string }];
}>();

const form = reactive({
  name: "",
  email: "",
});

const errors = reactive({
  name: "",
  email: "",
});

const touched = reactive({
  name: false,
  email: false,
});

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const validateName = () => {
  errors.name = form.name.trim() ? "" : "名前を入力してください。";
};

const validateEmail = () => {
  if (!form.email.trim()) {
    errors.email = "メールアドレスを入力してください。";
  } else if (!emailPattern.test(form.email)) {
    errors.email = "メールアドレスの形式が正しくありません。";
  } else {
    errors.email = "";
  }
};

const validateForm = () => {
  validateName();
  validateEmail();

  return !errors.name && !errors.email;
};

const handleNameBlur = () => {
  touched.name = true;
  validateName();
};

const handleEmailBlur = () => {
  touched.email = true;
  validateEmail();
};

const canSubmit = computed(() => {
  return form.name.trim() !== "" && emailPattern.test(form.email);
});

watch(
  () => props.isOpen,
  (isOpen) => {
    if (!isOpen) {
      return;
    }

    form.name = props.name;
    form.email = props.email;
    errors.name = "";
    errors.email = "";
    touched.name = false;
    touched.email = false;
  },
  {
    immediate: true,
  },
);

watch(
  () => form.name,
  () => {
    if (touched.name) {
      validateName();
    }
  },
);

watch(
  () => form.email,
  () => {
    if (touched.email) {
      validateEmail();
    }
  },
);

const handleClose = () => {
  emit("close");
};

const handleSubmit = () => {
  touched.name = true;
  touched.email = true;

  if (!validateForm()) {
    return;
  }

  emit("save", {
    name: form.name,
    email: form.email,
  });
};
</script>

<template>
  <Teleport to="body">
    <div v-if="props.isOpen" class="modal-overlay" @click.self="handleClose">
      <section
        class="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="profile-modal-title"
      >
        <h2 id="profile-modal-title">プロフィール編集</h2>

        <form class="modal-form" @submit.prevent="handleSubmit">
          <label class="form-item">
            <span>名前</span>
            <input
              v-model="form.name"
              type="text"
              autocomplete="name"
              @blur="handleNameBlur"
            />
            <p v-if="touched.name && errors.name" class="error-message">
              {{ errors.name }}
            </p>
          </label>

          <label class="form-item">
            <span>メールアドレス</span>
            <input
              v-model="form.email"
              type="email"
              autocomplete="email"
              @blur="handleEmailBlur"
            />
            <p v-if="touched.email && errors.email" class="error-message">
              {{ errors.email }}
            </p>
          </label>

          <div class="modal-buttons">
            <button type="button" class="cancel-button" @click="handleClose">
              キャンセル
            </button>

            <button type="submit" class="save-button" :disabled="!canSubmit">
              保存する
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
  background: rgb(0 0 0 / 40%);
}

.modal {
  width: 100%;
  max-width: 480px;
  padding: 24px;
  border-radius: 12px;
  background: #fff;
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
  font-size: 16px;
  box-sizing: border-box;
}

.form-item input:focus {
  outline: none;
  border-color: #2f755d;
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

.save-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
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

@media (max-width: 640px) {
  .modal {
    padding: 20px;
  }

  .modal-buttons {
    flex-direction: column;
  }
}
</style>
