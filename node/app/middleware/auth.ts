/**
 * 未ログインユーザーのアクセスを防ぐ認証ミドルウェア
 *
 * 認証されていない場合はログイン画面へリダイレクトする。
 */
export default defineNuxtRouteMiddleware(() => {
  const { isAuthenticated } = useAuth();
  if (!isAuthenticated.value) {
    return navigateTo("/login");
  }
});
