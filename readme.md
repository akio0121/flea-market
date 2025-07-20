・アプリケーション名
勤怠管理システム

・環境構築

Dockerビルド
1,git@github.com:akio0121/flea-market.git
2,DockerDesktopアプリを起動する。
3,docker-compose up -d --build

Laravel環境構築
1,docker-compose exec php bash
2,composer install
3,「.env.example」ファイルを 「.env」ファイルに命名を変更する。
4,php artisan key:generate
5,php artisan migrate
6,php artisan db:seed
7,php artisan storage:link

・ダミーデータ
1,ユーザー名      testA
  メールアドレス  aaa@bbb.com
  パスワード      aaaaaaaa

2,ユーザー名      testB
  メールアドレス   bbb@ccc.com
  パスワード       bbbbbbbb

3,ユーザー名      testC
  メールアドレス  ccc@ddd.com
  パスワード      cccccccc

・使用技術（実行環境）
PHP 8.3.12
MySQL 8.0.26
Laravel 8.83.8


・ER図


・URL
開発環境 http://localhost/
phpMyAdmin http://localhost:8080/
MailHog http://localhost:8025/
