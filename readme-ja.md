
# Laravel Extension Pack

[![Build Status](https://travis-ci.org/jumilla/laravel-addomnipot.svg)](https://travis-ci.org/jumilla/laravel-addomnipot)
[![Quality Score](https://img.shields.io/scrutinizer/g/jumilla/laravel-addomnipot.svg?style=flat)](https://scrutinizer-ci.com/g/jumilla/laravel-addomnipot)
[![Code Coverage](https://scrutinizer-ci.com/g/jumilla/laravel-addomnipot/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jumilla/laravel-addomnipot)
[![Latest Stable Version](https://poser.pugx.org/laravel-plus/addomnipot/v/stable.svg)](https://packagist.org/packages/laravel-plus/addomnipot)
[![Total Downloads](https://poser.pugx.org/laravel-plus/addomnipot/d/total.svg)](https://packagist.org/packages/laravel-plus/addomnipot)
[![Software License](https://poser.pugx.org/laravel-plus/addomnipot/license.svg)](https://packagist.org/packages/laravel-plus/addomnipot)

## 機能

* バージョンベースマイグレーション機能の追加
	* セマンティックバージョンベースのデータベースマイグレーションライブラリ [Laravel Versionia](http://github.com/jumilla/laravel-versionia) を採用しました。
	* マイグレーション／シードクラスは、Laravel 5のディレクトリ構造に組み込まれました。**app\Database\Migrations** と **app\Database\Seeds** ディレクトリを使ってください。
	* マイグレーションにグループを指定できるようになりました。
	* シードに名前が付けられるようになりました。
	* バージョンとの指定は、`App/Providers/DatabaseServiceProvider` クラスで行います。
	* Laravelのマイグレーション／シードクラスをそのまま利用できます。

* アドオン機能の追加
	* アプリケーション内のパッケージ機能です。Laravel 5のディレクトリ構造を複製するイメージで使うことができます。
	* デフォルトで、**addons** ディレクトリの下に配置されます。
	* アドオンに独自の名前空間(PSR-4)を一つ持たせることができます。
	* Laravel 5のパッケージとして扱えます。lang, viewの識別子の名前空間表記`{addon-name}::`が使えます。configも使えます。
	* アドオンの追加はディレクトリをコピーするだけ。**config/app.php** などの設定ファイルにコードを追加する必要はありません。
	* 9種類のひな形と2種類のサンプルを用意しています。`php artisan make:addon` で生成できます。

## インストール方法

### [A] Laravelひな形プロジェクトをダウンロードする

[Laravel Extension Pack](https://github.com/jumilla/laravel-extension)を組み込み済みのひな形プロジェクトを使う方法です。

[Laravel Addomnipot](https://github.com/jumilla/laravel-addomnipot)のアドオン機能に加え、[Laravel Versionia](https://github.com/jumilla/laravel-versionia)のセマンティックバージョンベースのマイグレーション機能や、アドオンに対応したソースコードジェネレーターも利用できます。

```sh
composer create-project laravel-plus/laravel5 <project-name>
```

### [B] 既存のプロジェクトに`laravel-plus/extension`をインストールする

[Laravel Extension Pack](https://github.com/jumilla/laravel-extension)を使う方法です。

[Laravel Addomnipot](https://github.com/jumilla/laravel-addomnipot)のアドオン機能に加え、[Laravel Versionia](https://github.com/jumilla/laravel-versionia)、やアドオンに対応したソースコードジェネレーターも利用できます。

詳しくは、[Laravel Extension Pack](https://github.com/jumilla/laravel-extension)のドキュメントを参照してください。

### [C] 既存のプロジェクトに`jumilla/laravel-addomnipot`をインストールする

[Laravel Addomnipot](https://github.com/jumilla/laravel-addomnipot)のアドオン機能のみを組み込む方法です。

#### 1. Composerで`jumilla/laravel-addomnipot`パッケージを追加します。

```sh
composer require jumilla/laravel-addomnipot
```

#### 2. サービスプロバイダーを追加します。

**config/app.php** ファイルの`providers`セクションに、`Jumilla\Addomnipot\Laravel\ServiceProvider`クラスを追加してください。

```php
	'providers' => [
		Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
		...
		↓次の行を追加する
		Jumilla\Addomnipot\Laravel\ServiceProvider::class,
	],
```

## 動作確認

サンプルとして、アドオン`wiki`を作成します。

```sh
php artisan make:addon wiki sample:ui
```

ルーティング設定を確認してください。

```sh
php artisan route:list
```

ローカルサーバーを立ち上げ、ブラウザで`http://localhost:8000/addons/wiki`にアクセスします。
パッケージ名が表示されれば成功です。

```sh
php artisan serve
```

## コマンド

### addon:list

アドオンの一覧を表示します。

```sh
php artisan addon:list
```

`addons`ディレクトリや`addon.php`ファイルが存在しない場合は作成します。

### addon:status

アドオンの状態を確認できます。

```sh
php artisan addon:status
```

`addons`ディレクトリや`addon.php`ファイルが存在しない場合は作成します。

### addon:name

アドオン内のファイルを走査し、PHP名前空間を変更します。

```sh
php artisan addon:name blog Sugoi/Blog
```

走査したファイルを確認したい場合は、`-v`オプションを指定してください。

```sh
php artisan addon:name blog Sugoi/Blog -v
```

### addon:remove

アドオンを削除します。

```sh
php artisan addon:remove blog;
```

`addons/blog` ディレクトリを削除するだけです。

### make:addon

アドオンを作成します。
次のコマンドは、アドオン `blog` を `library`タイプのひな形を用いて PHP名前空間 `Blog` として生成します。
アドオン `blog-app` を `ui`タイプのひな形を用いて PHP名前空間 `BlogApp` として生成します。

```sh
php artisan make:addon blog library
php artisan make:addon blog-app ui
```

ひな形は10種類から選べます。

- **minimum** - 最小構成。
- **simple** - **views** ディレクトリと **route.php** があるシンプルな構成。
- **asset** - css/jsをビルドするための構成。
- **library** - PHPクラスとデータベースを提供する構成。
- **api** - Web APIのための構成。
- **ui** - Web UIのための構成。
- **ui-sample** - UIアドオンのサンプル。
- **debug** - デバッグ機能を収めるアドオン。'debug-bar'のサービスプロバイダ登録も含む。
- **generator** - カスタマイズ用スタブファイル。
- **laravel5** - Laravel 5のディレクトリ構成。
- **laravel5-auth** - Laravel 5に含まれる認証サンプル。

コマンド引数でひな形を指定しない場合、対話形式で選択できます。

```sh
php artisan make:addon blog
```

PHP名前空間は `--namespace` オプションで指定することもできます。
名前空間の区切りには、`/` か `\\` を使ってください。

```sh
php artisan make:addon blog --namespace App/Blog
php artisan make:addon blog --namespace App\\Blog
```

## ヘルパ関数

### addon($name = null)

名前を指定してアドオンを取得します。

```php
$addon = addon('blog');
```

名前を省略すると、呼び出し元のクラスが含まれるアドオンを返します。
`addon(addon_name())` と等価です。

```php

namespace Blog\Http\Controllers;

class BlogController
{
	public function index()
	{
		$addon = addon();	// == addon(addon_name())
		Assert::same('blog', $addon->name());
	}
}
```

`addon()` 関数で取得した `LaravelPlus\Extension\Addons\Addon` オブジェクトを使って、アドオンの属性やリソースにアクセスすることができます。

```php
$addon = addon();
$addon->path();				// {$root}/addons/blog
$addon->relativePath();		// addons/blog
$addon->phpNamespace();		// Blog
$addon->config('page.row_limit', 20);
$addon->trans('emails.title');
$addon->transChoice('emails.title', 2);
$addon->view('layouts.default');
$addon->spec('forms.user_register');
```

### addon_name($class)

クラス名からアドオン名を取得します。
クラス名は名前空間を含む完全修飾名でなければなりません。
名前を指定してアドオンを取得します。

```php
$name = addon_name(get_class($this));
$name = addon_name(\Blog\Providers\AddonServiceProvider::class);		// 'blog'
```

引数を省略すると、呼び出し元のクラスが所属するアドオンの名前を返します。

```php
<?php

namespace Blog\Http\Controllers;

class PostsController
{
	public function index()
	{
		$name = addon_name();		// 'blog'
	}
}
```

## ファサードの拡張

Laravelのファサードは、クラスの静的メソッド呼び出しをインスタンスメソッド呼び出しに変換することと、グローバル名前空間にファサードクラスのエイリアスを作成することで実現されています。
Laravel 5のエイリアスローダーはグローバル名前空間にしか作用しないため、名前空間 (`App`など) の中からファサードを扱うにはクラス名の先頭に`\`を付けなければなりません。

```
function index()
{
	return \View::make();
}
```

または、use宣言を使います。

```
use View;

...

function index()
{
	return View::make();
}
```

Laravel Extensionはファサードを解決するエイリアスローダーを持っているので、`app`と`addons`ディレクトリ下の名前空間付きのPHPクラスに対してこれらの記述が不要です。
`vendor`ディレクトリ下のパッケージには作用しないので安心です。

```
function index()
{
	return View::make();	// スッキリ！
}
```

## 起動時の動き

* `addons/{addon-name}/addon.php` の `files`のファイルをrequireします。
* `addons/{addon-name}/addon.php` の `namespace`を見て、`directories`に指定された全てのディレクトリに対しPSR-4規約に基づくクラスオートロードの設定をします。

## 著者

古川 文生 / Fumio Furukawa (fumio@jumilla.me)

## ライセンス

MIT
