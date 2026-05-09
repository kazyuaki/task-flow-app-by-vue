# Task Flow App by Vue & Laravel

Vue.js と Laravel を使用したタスク管理アプリケーションです。

## 機能メモ

### タスク一覧画面

- タスクの一覧表示
- キーワード検索
- ステータス・カテゴリによる絞り込み
- 期限日の昇順・降順ソート
- ステータス別の件数サマリー表示
- 優先度・カテゴリ・期限までの日数の表示
- Laravel API からタスク一覧を取得

### タスク新規作成画面

- タスクの新規登録
- タイトル・説明・ステータス・優先度・カテゴリ・期限日の入力
- フロントエンドでの入力バリデーション表示
- Laravel Form Request のバリデーションエラー表示
- 登録成功後にタスク一覧画面へ遷移

### API

- `GET /api/tasks`: タスク一覧をカテゴリ付きで取得
- `POST /api/tasks`: タスクを新規作成

### バリデーション

タスク新規作成では、Laravel の `StoreTaskRequest` を基準に以下を検証します。

- タイトルは必須、255文字以内
- 説明は必須、1000文字以内
- ステータス・優先度は必須
- 期限日は必須、今日以降の日付

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
docker compose exec php php artisan migrate --seed
```

### 4. フロントエンド（Vue.js）設定

```bash
docker compose exec node npm install
docker compose exec node npm run dev
```

### 5. 開発用URL

- フロントエンド: http://localhost:3000
- Laravel API: http://localhost:8000
- phpMyAdmin: http://localhost:8081

### 6. GitHubへの初回プッシュ（新規リポジトリ用）

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin git@github.com:kazyuaki/task-flow-app-by-vue.git
git push -u origin main
```

### 補足

GitHub への初回反映手順は上記「6. GitHub への初回プッシュ（新規リポジトリ用）」を参照してください。
