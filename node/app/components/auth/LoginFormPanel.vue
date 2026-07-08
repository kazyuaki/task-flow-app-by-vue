<!-- ログインフォームのパネルコンポーネント -->
<script lang="ts" setup>
import { computed, reactive, ref } from "vue";
import { validateLoginForm } from "~/utils/validation/login";

const { login, loading } = useAuth();

const form = reactive({
  email: "",
  password: "",
});

const errors = reactive({
  email: "",
  password: "",
});

const loginError = ref("");
const showPassword = ref(false);

const canSubmit = computed(() => {
  return form.email.trim() !== "" && form.password.trim() !== "";
})

const handleSubmit = async () => {
  loginError.value = "";

  const validationErrors = validateLoginForm(form.email, form.password);

  errors.email = validationErrors.email;
  errors.password = validationErrors.password;

  if (errors.email || errors.password) {
    return;
  }

  try {
    await login({
      email: form.email,
      password: form.password,
    });
  } catch {
    loginError.value =
      "メールアドレスまたはパスワードが正しくありません。";
  }
};
</script>
<template>
  <section class="login-panel" aria-labelledby="login-title">
    <p class="panel-label">Welcome back</p>
    <h2 id="login-title">ログイン</h2>
    <form class="login-form" @submit.prevent="handleSubmit">
      <p v-if="loginError" class="form-error" role="alert">
        {{ loginError }}
      </p>

      <label>
        メールアドレス
        <input
          v-model.trim="form.email"
          type="email"
          autocomplete="email"
          placeholder="test@example.com"
        />
        <p v-if="errors.email" class="error-message">
          {{ errors.email }}
        </p>
      </label>
      <label>
        パスワード
        <input
          v-model="form.password"
          :type="showPassword ? 'text' : 'password'"
          autocomplete="current-password"
          placeholder="test1234"
        />
        <p v-if="errors.password" class="error-message">
          {{ errors.password }}
        </p>
      </label>
      <label class="password-toggle">
        <input
          v-model="showPassword"
          type="checkbox"
        />
        パスワードを表示
      </label>
      <button type="submit" :disabled="loading || !canSubmit">ログイン</button>
      <NuxtLink to="/register" class="register-link">会員登録はこちら</NuxtLink>
    </form>
  </section>
</template>

<style scoped>
.login-panel {
  width: 100%;
  padding: 32px;
  background: rgba(255, 255, 255, 0.86);
  border: 1px solid rgba(23, 32, 51, 0.1);
  border-radius: 8px;
  box-shadow: 0 24px 70px rgba(24, 39, 75, 0.12);
}

.login-panel h2 {
  margin: 0 0 24px;
  font-size: 30px;
}

.panel-label {
  margin: 0 0 12px;
  color: #2d6a4f;
  font-size: 13px;
  font-weight: 800;
  letter-spacing: 0;
  text-transform: uppercase;
}

.login-form {
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

.error-message {
  margin: 0;
  color: #e03131;
  font-size: 13px;
}

.form-error {
  margin: 0;
  padding: 12px 14px;
  color: #b42318;
  font-size: 14px;
  font-weight: 800;
  background: #fffbfa;
  border: 1px solid #fda29b;
  border-radius: 6px;
}

.register-link {
  justify-self: center;
  color: #2d6a4f;
  font-size: 14px;
  font-weight: 800;
}

.register-link:hover {
  text-decoration: underline;
}

@media (max-width: 760px) {
  .login-panel {
    padding: 24px;
  }
}
</style>
