<!-- 会員登録フォームのパネルコンポーネント -->

<script setup lang="ts">
import { reactive } from "vue";

const { register, loading } = useAuth();

const form = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const errors = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const showPassword = ref(false);

const canSubmit = computed(() => {
  return (
    form.name.trim() !== "" &&
    form.email.trim() !== "" &&
    form.password.trim() !== "" &&
    form.password_confirmation.trim() !== ""
  );
});

const handleSubmit = async () => {
  Object.assign(errors, {
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  try {
    await register({
      name: form.name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    });
  } catch (error: any) {
    if (error?.response?.status === 422) {
      const validationErrors = error.response.data.errors;

      errors.name = validationErrors.name?.[0] ?? "";
      errors.email = validationErrors.email?.[0] ?? "";
      errors.password = validationErrors.password?.[0] ?? "";
      errors.password_confirmation =
        validationErrors.password_confirmation?.[0] ?? "";
    }
  }
};
</script>
<template>
  <section class="register-panel aria-labelledby=register-title">
    <p class="panel-label">Create account</p>
    <h2 id="regsiter-title">会員登録</h2>

    <form class="register-form" novalidate @submit.prevent="handleSubmit">
      <label>
        名前
        <input
          v-model="form.name"
          type="text"
          autocomplete="name"
          placeholder="山本 太郎"
        />
        <p v-if="errors.name" class="error-message">
          {{ errors.name }}
        </p>
      </label>
      <label>
        メールアドレス
        <input
          v-model.trim="form.email"
          type="email"
          autocomplete="email"
          placeholder="you@example.com"
        />
      </label>
      <p v-if="errors.email" class="error-message">
        {{ errors.email }}
      </p>
      <label>
        パスワード
        <input
          v-model="form.password"
          :type="showPassword ? 'text' : 'password'"
          autocomplete="new-password"
          placeholder="8文字以上・数字を含む"
        />
        <p v-if="errors.password" class="error-message">
          {{ errors.password }}
        </p>
      </label>
      <label>
        パスワード（確認）
        <input
          v-model="form.password_confirmation"
          :type="showPassword ? 'text' : 'password'"
          autocomplete="new-password"
          placeholder="もう一度入力してください"
        />
        <p v-if="errors.password_confirmation" class="error-message">
          {{ errors.password_confirmation }}
        </p>
      </label>
      <label class="password-toggle">
        <input
          v-model="showPassword"
          type="checkbox"
        />
        パスワードを表示
      </label>
      <button type="submit" :disabled="!canSubmit">登録する</button>
      <NuxtLink to="/login" class="login-link"
        >すでにアカウントをお持ちの方はこちら
      </NuxtLink>
    </form>
  </section>
</template>

<style scoped>
.register-panel {
  width: 100%;
  padding: 32px;
  background: rgba(255, 255, 255, 0.86);
  border: 1px solid rgba(23, 32, 51, 0.1);
  border-radius: 8px;
}

.register-panel h2 {
  margin: 8px 0 24px;
  font-size: 24px;
}

.panel-label {
  margin: 0 0 12px;
  color: #2d6a4f;
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
}

.register-form {
  display: grid;
  gap: 16px;
}

label {
  display: grid;
  gap: 8px;
  color: #313b4d;
  font-size: 14px;
  font-weight: 800;
}

input,
button {
  min-height: 46px;
  border-radius: 6px;
  font: inherit;
}

input {
  padding: 10px 12px;
  color: #172033;
  border: 1px solid #cdd5df;
  background: #fff;
  outline: none;
}

input:focus {
  border-color: #2d6a4f;
  box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.14);
}

.error-message {
  margin: 0;
  color: #e03131;
  font-size: 13px;
  font-weight: 700;
}

.password-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 400;
  cursor: pointer;
}

.password-toggle input {
  min-height: auto;
}

button {
  border: 0;
  color: #fff;
  font-weight: 800;
  background: #2d6a4f;
  cursor: pointer;
  transition:
    transform 0.16s ease,
    background 0.16s ease;
}

button:hover {
  background: #24583f;
  transform: translateY(-1px);
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

button:disabled:hover {
  background: #2d6a4f;
  transform: none;
}

.login-link {
  justify-self: center;
  color: #2d6a4f;
  font-size: 14px;
  font-weight: 800;
}

.login-link:hover {
  text-decoration: underline;
}

@media (max-width: 760px) {
  .register-panel {
    padding: 24px;
  }
}
</style>
