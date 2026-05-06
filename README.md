# Task Flow App by Vue & Laravel

Vue.js と Laravel を使用したタスク管理アプリケーションです。

## 🚀 開発環境のセットアップ

このプロジェクトは Docker を使用して構築されています。

### 1. リポジトリのクローン

```bash
git clone git@github.com:kazyuaki/task-flow-app-by-vue.git
cd task-flow-app-by-vue
```

### 2. コンテナ起動

```bash
docker compose up -d
```

### 3. バックエンド（Laravel）設定

```bash
docker compose exec php composer install
docker compose exec php cp .env.example .env
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate
```

### 4. フロントエンド（Vue.js）設定

```bash
docker compose exec node npm install
docker compose exec node npm run dev
```

### 5. GitHubへの初回プッシュ（新規リポジトリ用）

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin git@github.com:kazyuaki/task-flow-app-by-vue.git
git push -u origin main
```

### 補足

GitHub への初回反映手順は上記「5. GitHub への初回プッシュ（新規リポジトリ用）」を参照してください。
