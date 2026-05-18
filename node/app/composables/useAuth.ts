import type {
  FetchUserOptions,
  LoginForm,
  RegisterForm,
  User,
} from "~/types/auth";

/**
 * 認証関連のロジックを提供するカスタムフック
 */
export const useAuth = () => {
  const { $api } = useNuxtApp();

  const user = useState<User | null>("auth.user", () => null);
  const loading = useState<boolean>("auth.loading", () => false);
  const isAuthenticated = useState<boolean>(
    "auth.isAuthenticated",
    () => false,
  );
  const initialized = useState<boolean>("auth.initialized", () => false);
  const fetchingUser = useState<boolean>("auth.fetchingUser", () => false);
  const fetchUserRequestId = useState<number>(
    "auth.fetchUserRequestId",
    () => 0,
  );

  // ユーザー情報を取得する関数
  const fetchUser = async (options: FetchUserOptions = {}) => {
    const { force = false, clearAuthenticatedOnFail = true } = options;

    if (fetchingUser.value && !force) {
      return;
    }

    fetchingUser.value = true;
    const requestId = fetchUserRequestId.value + 1;
    fetchUserRequestId.value = requestId;

    try {
      const response = await $api.get("/api/user");
      if (requestId !== fetchUserRequestId.value) {
        return;
      }

      user.value = response.data;
      isAuthenticated.value = true;
    } catch (error) {
      if (requestId !== fetchUserRequestId.value) {
        return;
      }

      user.value = null;
      if (clearAuthenticatedOnFail) {
        isAuthenticated.value = false;
      }
    } finally {
      if (requestId === fetchUserRequestId.value) {
        fetchingUser.value = false;
        initialized.value = true;
      }
    }
  };

  const initializeAuth = async () => {
    if (initialized.value) {
      return;
    }

    await fetchUser();
  };

  // アプリ起動時にログイン状態を復元
  onMounted(() => {
    initializeAuth();
  });

  // ログイン処理
  const login = async (form: LoginForm) => {
    loading.value = true;

    try {
      await $api.get("/sanctum/csrf-cookie");
      await $api.post("/login", form);
      isAuthenticated.value = true;
      await fetchUser({
        force: true,
        clearAuthenticatedOnFail: false,
      });

      await navigateTo("/tasks");
    } finally {
      loading.value = false;
    }
  };

  // ログアウト処理
  const logout = async () => {
    await $api.post("/logout");
    user.value = null;
    isAuthenticated.value = false;
    initialized.value = true;
    await navigateTo("/login");
  };

  // 会員登録処理
  const register = async (form: RegisterForm) => {
    loading.value = true;

    try {
      await $api.get("/sanctum/csrf-cookie");
      await $api.post("/register", form);

      isAuthenticated.value = true;

      await fetchUser({
        force: true,
        clearAuthenticatedOnFail: false,
      });

      await navigateTo("/tasks");
    } finally {
      loading.value = false;
    }
  };

  return {
    user,
    loading,
    isAuthenticated,
    initializeAuth,
    fetchUser,
    login,
    logout,
    register,
  };
};
