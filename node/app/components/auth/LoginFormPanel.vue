<template>
  <section class="login-panel" aria-labelledby="login-title">
    <p class="panel-label">Welcome back</p>
    <h2 id="login-title">ログイン</h2>
    <form class="login-form">
      <label>
        メールアドレス
        <input
          type="email"
          autocomplete="email"
          placeholder="you@example.com"
        />
      </label>
      <label>
        パスワード
        <input
          type="password"
          autocomplete="current-password"
          placeholder="password"
        />
      </label>
      <button type="submit">ログイン</button>
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

@media (max-width: 760px) {
  .login-panel {
    padding: 24px;
  }
}
</style>
