type LoginForm = {
  email: string;
  password: string;
};

type User = {
  id: number;
  name: string;
  email: string;
};

/**
 * 認証関連のロジックを提供するカスタムフック
 */
export const useAuth = () => {
  const { $api } = useNuxtApp();

  const user = useState<User | null>("auth.user", () => null);

  const loading = useState<boolean>("auth.loading", () => false);

	// ユーザー情報を取得する関数
  const fetchUser = async () => {
    try {
      const response = await $api.get("/api/user");
      user.value = response.data;
    } catch (error) {
      user.value = null;
    }
  };

   // アプリ起動時にログイン状態を復元	
	 onMounted(() => {
		fetchUser();
	 });
	
	// ログイン処理
	const login = async (form: LoginForm) => {
    loading.value = true;

    try {
      await $api.get("/sanctum/csrf-cookie");
      await $api.post("/api/login", form);
      await fetchUser();

      await navigateTo("/tasks");
    } finally {
      loading.value = false;
    }
  };

	// ログアウト処理
  const logout = async () => {
    await $api.post("/api/logout");
    user.value = null;
    await navigateTo("/login");
  };

  return {
    user,
    loading,
    login,
    logout,
  };
};
