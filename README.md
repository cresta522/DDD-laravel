# オラオラDDD

## 概要

小長谷のオラオラDDD

個人・チーム開発するにあたっての共通設定メモ

## 技術スタック

- Laravel8
    - DDD採用予定
- DevOps(Docker) 非必須, plantUML 必須(設計ファイル見れなくても良い人はなくてもいいけど推奨)
- CI/CD
- VPS docker / AWS Fargate (予算にあわせて)
- css(sass)

## 法律

### 特定商取引法に基づく表記

### 利用規約

### プライバシーポリシー

## Git

### Repository

https://github.com/cresta522/DDD

### 注意事項

必ず作業前・一日の初めなど着手するタイミングで最新状態pullしてください。

不要なコンフリクトの原因にもなるし、コミット量が多くなります。

### 保護設定

[参考サイト](https://qiita.com/da-sugi/items/ba3cd83e64c689795c50)

### ブランチルール

以下を参考に、作業ブランチから親ブランチへPR(Pull Request)投げてください

### 開発

#### 親ブランチ

`develop`ブランチ

#### 作業ブランチ

`feature/***`ブランチ

### アイデア

#### 親ブランチ

`idea`ブランチ

#### 作業ブランチ

`idea-feature/***`ブランチ

### 設計

#### 親ブランチ

`system_design`ブランチ

#### 作業ブランチ

`system_design-feature/***`ブランチ

### ドキュメント

#### 親ブランチ

`docs` ブランチ

#### 作業ブランチ

`docs-feature/***`

## コーディングルール

- PSRに則ってください。
- テーブルの列にenumは許可しません。
- マジックナンバーも許容しません。
- インデントは4スペースとします。
- 関数の型宣言必須とします。
- ControllerにValidateは禁止します。make:requestで作成されたRequestに設定してください。
- viewを返すアクションでredirectする際はExceptionを吐き、Exception内でreturnしてください。
- urlはケバブケースに統一します。
- Routingにnameは必須とします。

## Laravel ディレクトリ階層(DDD込みCQRS込み)

### CommandService: 永続化
永続化処理をまとめるサービス

リポジトリをDIして、Entityの中のModelを引数として渡す。

#### Entity
永続化対象(Model)生成器

永続化対象をどんなRequestをもとに生成するか場合によって分かれるため静的関数で自身を生成する。

返り値は基本自分自身とし、`getModel`関数で永続化対象を返却する。

### QueryService: 非永続化(取得)
非永続化をまとめるサービス

リポジトリをDIしてクエリを作成し、必要なレコードの取得を行う。

取得したレコードはModelになっているのでDtoに引き渡し、返り値をDto、DtoCollectionとする

#### Dto (Data Transfer Object)

引数はModelとする。そのクラス内で必要なレコードの表示内容の変更を行う

- 姓名をまとめてフルネームを取得する関数を作成する
- 生年月日から年齢を取得する関数を作成する

など。

### Service: DBと関係ないもの

取得したModelを用いた表示内容の変更ならDtoの責務だが、例えばRequestをもとにレコードの存在を確認するか、
などの永続化・非永続化などに属さない処理をまとめる。

一番処理内容が少ないことが望ましい。

### Repository

- 渡されたデータを保存する
- 渡されたIDを削除する
- データ取得のためのビルダを提供する

のみを役割とする。

Controllerの中で呼ばないこと。DIをするのはサービス内でのみとする。

### 注意

Serviceの中でCommandServiceやQueryServiceを呼び、その中でServiceを再帰的に呼んでしまうと500になる。

### ディレクトリ構造
```
docker/                       Dockerコンテナ群
src/
   ├─ app/                    メインコード
   │   ├─ Actions
   │   │   └─ Commands/       Fortify(Fortify認証 カスタマイズ用 Laravel8-)
   │   ├─ Console/
   │   │   └─ Commands/       コマンド (Laravel標準)
   │   │
   │   ├─ Core/               共通機能
   │   │
   │   ├─ Domain/             業務ドメイン(DDD)
   │   │   ├─ Entity/         エンティティ（集約）
   │   │   │   └─ Validator/  エンティティバリデーター
   │   │   ├─ Event/          ドメインイベント
   │   │   ├─ Service/        ドメインサービス
   │   │   └─ ValueObjects/   値オブジェクト（エンティティ用）
   │   │
   │   ├─ Exception/          例外
   │   │
   │   ├─ Http/
   │   │   ├─ Controllers/    Webコントローラ (Laravel標準)
   │   │   │
   │   │   ├─ Middleware/     ミドルウェア (Laravel標準)
   │   │   └─ Requests/       フォームバリデーション (Laravel標準)
   │   │
   │   ├─ Jobs/               ジョブ (Laravel標準)
   │   ├─ Listeners/          イベントリスナー (Laravel標準)
   │   │
   │   ├─ Models/             Eloquentモデル (Laravel標準)
   │   │   ├─ Event/          Eloquentイベント
   │   │   └─ Validator/      Eloquentモデルバリデーター
   │   │
   │   ├─ Providers/          サービスプロバイダ (Laravel標準)
   │   │
   │   ├─ Repositories/       リポジトリ
   │   │   └─ Domain/         業務ドメイン/エンティティ（集約） 用リポジトリ
   │   │
   │   ├─ Rules/              バリデーションルール (Laravel標準)
   │   │
   │   ├─ Services/           アプリケーションサービス
   │   │   ├─ Command/           永続化処理
   │   │   │   └─ Entity/        モデルを直接変更しないようにEntity経由
   │   │   └─ Query/             非永続化処理
   │   │       └─ DTO/           Data Transfer Object
   │   │
   │   ├─ ValueObjects/       値オブジェクト
   │   └─ View/               ビュー用のヘルパー
   │
   ├─ bootstrap               起動
   │
   ├─ config                  設定ファイル
   │
   ├─ database
   │   └─ migrations          マイグレーション
   │
   ├─ resources               ビュー、js、sass
   │
   ├─ routes                  ルーティング
   │
   └─ tests/                  テスト
       ├─ Browser             E2Eテスト (Laravel Dusk)
       ├─ Feature             機能テスト (Feature test)
       └─ Unit                単体テスト (Unit test)
```

## 権限やけくそコマンド

`docker-compose exec -u root app chmod 777 -R ./`

`docker-compose exec -u root app chmod 777 -R ./storage`

## 備忘録メモ

### テスト

`docker-compose exec app php artisan test --env=testing`

## setup

### laravel

`docker-compose exec -u root app composer create-project --prefer-dist laravel/laravel . "8.x"`

### livewire & auth

`docker-compose exec -u root app composer require livewire/livewire`

`docker-compose exec -u root app composer require laravel/ui`

`docker-compose exec -u root app php artisan ui vue --auth`  
`docker-compose exec -u root app npm install`  
`docker-compose exec -u root app npm run dev`  
`docker-compose exec -u root app php artisan migrate`

### ide-helper

`docker-compose exec -u root app composer require --dev barryvdh/laravel-ide-helper`  
`docker-compose exec -u root app php artisan ide-helper:generate`  
`docker-compose exec app php artisan ide-helper:models -W -R`

### debugbar

`docker-compose exec -u root app composer require --dev barryvdh/laravel-debugbar`  
`docker-compose exec -u root app php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"`

### dbal

カラム変更で必要  
`docker-compose exec -u root app composer require doctrine/dbal`

### adminlte3(デザイナーいない場合)

`docker-compose exec -u root app composer require jeroennoten/laravel-adminlte`  
`docker-compose exec -u root app php artisan adminlte:install`  
`docker-compose exec -u root app php artisan adminlte:install --only=auth_views`  
`docker-compose exec -u root app php artisan adminlte:install --only=main_views`

### Job database

`docker-compose exec -u root app php artisan queue:table`

`docker-compose exec -u root app php artisan migrate`

### Laravel jobはCron 不要

`docker-compose exec -d app php artisan schedule:work`

## plugin

### ts

`docker-compose exec -u root app npm install -g typescript` 
`docker-compose exec -u root app tsc --version`  
`docker-compose exec -u root app tsc --init`

## 画面遷移テスト

https://qiita.com/MitsukiYamauchi/items/cedaa476b3424c317070

## SSH git 設定

https://qiita.com/shizuma/items/2b2f873a0034839e47ce

## docker で Lets Encrypt

https://paulownia.hatenablog.com/entry/2020/09/12/150658

## 鍵生成

ssh-keygen -t rsa -b 4096

### CSR作成

`openssl genrsa -out hoge.key 2048`
`openssl req -new -key hoge.key -out hoge.csr`