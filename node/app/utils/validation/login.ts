// utils/validation/login.ts

export const validateLoginForm = (
  email: string,

  password: string,
) => {
  const errors = {
    email: "",

    password: "",
  };

  if (!email) {
    errors.email = "メールアドレスを入力してください。";
  }

  if (!password) {
    errors.password = "パスワードを入力してください。";
  }

  return errors;
};