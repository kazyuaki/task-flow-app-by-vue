/** 認証関係の型定義 */
export type LoginForm = {
  email: string;
  password: string;
};

export type RegisterForm = {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
};

export type User = {
  id: number;
  name: string;
  email: string;
};

export type FetchUserOptions = {
  force?: boolean;
  clearAuthenticatedOnFail?: boolean;
};
