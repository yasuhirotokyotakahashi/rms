# Rese

## 目的
Rese（リーズ）は、ある企業のグループ会社の飲食店予約サービスです。制作の背景として、外部の飲食店予約サービスが手数料を取られるため、自社で予約サービスを提供することを目指しています。

## アプリケーションURL
- http://35.77.86.166/

## 他のリポジトリ
- [本プロジェクトのリポジトリ](リンク)

## 機能一覧
- 新規ユーザー登録
- ログイン機能
- メール認証機能
- 飲食店一覧表示機能
- 飲食店詳細表示機能
- 飲食店予約登録・編集・削除機能
- 飲食店お気に入り登録・削除機能
- 飲食店検索機能（エリア・ジャンル・店舗名）

## 使用技術（実行環境）
- フレームワーク：Laravel [8.83.8]
- PHP：[PHPバージョン: 7.4.9]
- データベース：MySQL [8.0.26 - MySQL Community Server - GPL]
- サーバーのOSとバージョン：[Debian GNU/Linux/10 (buster)]

## テーブル設計
以下は、主要なデータベーステーブルとそのフィールドの概要です。

**usersテーブル**
- id (主キー)
- name
- email
- password
- created_at
- updated_at

**shopsテーブル**
- id (主キー)
- name
- image_path
- description
- genre_id (外部キー、genresテーブルと関連)
- address_id(外部キー、addressesテーブルと関連)
- created_at
- updated_at

**reservationsテーブル**
- id (主キー)
- user_id (外部キー、usersテーブルと関連)
- shop_id(外部キー、shopsテーブルと関連)
- date
- time
- guests
- created_at
- updated_at

**favoritesテーブル**
- id (主キー)
- user_id (外部キー、usersテーブルと関連)
- shop_id(外部キー、shopsテーブルと関連)
- created_at
- updated_at

**genresテーブル**
- id (主キー)
- name
- created_at
- updated_at

**addressesテーブル**
- id (主キー)
- city
- created_at
- updated_at


## ER図
[ER図のイメージをここに挿入]
![rmsER図](https://github.com/yasuhirotokyotakahashi/rms/assets/128282431/d6dac7d8-e118-4766-9d73-fd2e001a2368)

## 環境構築
プロジェクトをローカルで実行するための手順を以下に示します。

1. dockerをインストールする
2. docker-composeをインストールする
3. リポジトリをクローンする
4. docker-compose.ymlのある場所でビルドをする。
