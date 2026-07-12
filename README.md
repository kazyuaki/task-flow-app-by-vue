# Task Flow App by Vue & Laravel

Nuxt 4 と Laravel 13 で作成した、認証付きのタスク管理アプリケーションです。  
Laravel Sanctum / Fortify による Cookie ベースの SPA 認証を使い、ログイン後にタスク一覧・作成・詳細・編集・削除を操作できます。

## 開発背景

日々の学習や業務の中で、やるべきことが増えるにつれてタスクの管理が煩雑になっていました。
そこで、自分自身が使いやすいタスク管理ツールを作りたいと考え、本アプリを開発しました。
単純なメモアプリではなく、実際の業務を意識して、

- 認証
- バリデーション
- コンポーネント設計
- API設計
- Docker環境構築
- デプロイ

まで一通り経験できる構成を目指しました。

## 工夫した点


### 直感的に操作できるUI

自分自身が日常的に利用することを想定し、必要な情報へ素早くアクセスできるUIを意識しました。

タスク一覧画面では、

- キーワード検索
- ステータス絞り込み
- カテゴリ絞り込み
- 優先度絞り込み
- 期限日のソート

に対応しており、多くのタスクの中から目的のタスクを素早く見つけられるようにしています。

また、ステータスごとの件数サマリーを表示することで、現在の進捗状況を一目で把握できるようにしました。

### フロントエンドとバックエンドのバリデーション併用

ユーザーが入力ミスにすぐ気付けるよう、フロントエンドでリアルタイムにバリデーションを実施しています。

また、不正なリクエストや想定外の入力に対応するため、Laravel 側でもバリデーションを実装し、フロントエンド・バックエンドの両方で入力値を検証する構成にしました。

### コンポーネント設計

保守性と再利用性を意識し、責務ごとにコンポーネントを分割しています。

例えばプロフィール機能では、プロフィール表示・編集モーダル・パスワード変更モーダルをそれぞれ独立したコンポーネントとして実装しました。

また、共通トースト通知やページタイトルなどもコンポーネント化することで、重複コードの削減と機能追加のしやすさを意識しています。


### モダンなレイアウト設計

一覧画面や詳細画面では、カードレイアウトやグラデーション背景を採用し、視認性と操作性を意識したUIを実装しました。

また、レスポンシブ対応を行い、PC・タブレット・スマートフォンなど異なる画面サイズでも利用しやすいレイアウトを目指しました。

## 苦労した点

### Sanctum認証

Cookieベース認証の仕組みを理解するまで苦労しました。
特に

- CSRF Cookie取得
- CORS設定
- Stateful Domains

の関係性を理解することで、
SPA認証の仕組みへの理解を深めることができました。

### VPSデプロイ

NuxtとLaravelをDocker環境で本番公開する際、

- Nginxリバースプロキシ
- HTTPS化
- Dockerコンテナ間通信

の設定で試行錯誤しました。
502 Bad Gateway の調査を通じて、
NginxとPHP-FPMの接続について理解が深まりました。

## 主な機能

- ユーザー登録・ログイン・ログアウト
- タスク一覧表示
- キーワード検索
- ステータス・カテゴリ・優先度による絞り込み
- 期限日の昇順・降順ソート
- ステータス別の件数サマリー表示
- タスクの新規作成
- タスク詳細表示
- タスク編集
- タスク削除
- チェックリスト付きタスクの作成・更新
- タスク作成画面からのカテゴリ追加
- プロフィール・パスワード変更
- Laravel API のバリデーションエラー表示
- 更新処理の成功・失敗を知らせる共通トースト通知

## 技術スタック

### Frontend

- Nuxt 4
- Vue 3
- TypeScript
- Axios

### Backend

- Laravel 13
- Laravel Sanctum
- Laravel Fortify
- MySQL

### Development

- Docker Compose
- Nginx
- phpMyAdmin
- MailHog

## 公開URL

https://taskflow-app-kazu.com

## URL

- フロントエンド: http://localhost:3000
- Laravel API: http://localhost:8000
- phpMyAdmin: http://localhost:8081
- MailHog: http://localhost:8025

## セットアップ

### 1. リポジトリを取得

```bash
git clone git@github.com:kazyuaki/task-flow-app-by-vue.git
cd task-flow-app-by-vue
```

### 2. コンテナを起動

```bash
docker compose up -d
```

### 3. Laravel を初期化

```bash
docker compose exec php composer install
docker compose exec php cp .env.example .env
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate --seed
docker compose exec php php artisan optimize:clear
```

### 4. フロントエンドを起動

Docker Compose の `node` サービスは `npm install && npm run dev -- --host 0.0.0.0` を実行します。手動で起動する場合は以下です。

```bash
docker compose exec node npm install
docker compose exec node npm run dev -- --host 0.0.0.0
```

## 開発用アカウント

Seeder 実行後、以下のユーザーでログインできます。

```text
email: test@example.com
password: test1234
```

## 認証

このアプリは Laravel Sanctum の SPA 認証を使います。

- フロントエンドは `http://localhost:3000`
- API は `http://localhost:8000`
- `/sanctum/csrf-cookie` で CSRF Cookie を取得
- `/api/login`, `/api/register`, `/api/logout` は独自の認証ルートを利用
- 認証が必要な API は `auth:sanctum` ミドルウェア配下

ローカル開発では CORS と Sanctum の stateful domain を `.env` で設定しています。

```env
APP_URL=http://localhost:8000
FRONTEND_URLS=http://localhost:3000,http://localhost:3001,http://localhost:3002,http://localhost:3003,http://127.0.0.1:3000,http://127.0.0.1:3001,http://127.0.0.1:3002,http://127.0.0.1:3003
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:3001,localhost:3002,localhost:3003,127.0.0.1,127.0.0.1:3000,127.0.0.1:3001,127.0.0.1:3002,127.0.0.1:3003
```

`.env` を変更した場合はキャッシュをクリアしてください。

```bash
docker compose exec php php artisan optimize:clear
```

## 画面

- `/login`: ログイン
- `/register`: ユーザー登録
- `/profile`: プロフィール・パスワード変更
- `/tasks`: タスク一覧
- `/tasks/create`: タスク新規作成
- `/tasks/{id}`: タスク詳細
- `/tasks/{id}/edit`: タスク編集

## API

認証が必要な API です。

- `GET /api/user`: 認証ユーザー情報を取得
- `GET /api/categories`: カテゴリ一覧を取得
- `POST /api/categories`: カテゴリを作成
- `PUT /api/profile`: プロフィールを更新
- `PUT /api/profile/password`: パスワードを変更
- `GET /api/tasks`: タスク一覧を取得
- `POST /api/tasks`: タスクを作成
- `GET /api/tasks/{task}`: タスク詳細を取得
- `PUT /api/tasks/{task}`: タスクを更新
- `DELETE /api/tasks/{task}`: タスクを削除

## よく使うコマンド

```bash
# コンテナ起動
docker compose up -d

# コンテナ停止
docker compose down

# Laravel マイグレーションと Seeder
docker compose exec php php artisan migrate:fresh --seed

# Laravel キャッシュクリア
docker compose exec php php artisan optimize:clear

# Nuxt 開発サーバー
docker compose exec node npm run dev -- --host 0.0.0.0

# Nuxt ビルド
docker compose exec node npm run build
```

## トラブルシュート

### CORS error が出る

ブラウザで開いているフロントエンドの Origin が `FRONTEND_URLS` に含まれているか確認してください。  
設定変更後は以下を実行します。

```bash
docker compose exec php php artisan optimize:clear
```

### `/api/tasks` が 401 になる

ログイン Cookie が API リクエストに送られていない可能性があります。フロントエンドの API 呼び出しでは `credentials: "include"` または axios の `withCredentials: true` が必要です。

### ログインできない

Seeder を再実行して、開発用ユーザーのパスワードを更新してください。

```bash
docker compose exec php php artisan db:seed --class=UserSeeder
```

## ディレクトリ構成

```text
.
├── api/                 # Laravel API
├── node/                # Nuxt frontend
├── docker/              # Docker settings
├── docker-compose.yml
└── README.md
```

## 今後の改善予定

- UI/UX改善
- ドラッグ&ドロップによる並び替え
- 期限が近いタスクのリマインド通知
