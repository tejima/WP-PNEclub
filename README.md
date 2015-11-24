# WP-PNEclub使い方

## イントロダクション

WP-PNEclubは、PNE.clubの集金アカウント情報を元に、
有料ユーザーのみの限定コンテンツをWordPress内で表示する（出し分ける）プラグインです。

有料／無料の細かいWordPress内の表示コントロールには、既存プラグインであるWP-Membersプラグインを利用しています。
詳細はプラグインの説明をご覧ください。有料メディアを運営するための、以下の使い方を想定しています。

* ログイン不要の無料コンテンツ（記事／ページ）の表示 WordPress通常機能
* 有料記事のチラ見せ（MORE前まで） 有料サイトへの誘導
* クレジットカードによる月額有料会員登録（PNE.club担当）
* 有料会員への限定コンテンツ（記事／ページ）の表示

該当のクラブの正しい利用者であることが確認されれば、WordPressの特定のユーザーとしてログインを継続します。

コンテンツの入力、出し分けはWordPressと WP-Membersプラグイン
会員登録／退会、会費集金、督促等の会員管理は、PNE.clubサービス、WP-PNEclubが担当します。

## 事前準備

* WP-Membersプラグインが動作するバージョンのWordPressのインストール
* PNE.club ( http://pne.club )でのクラブ加盟
* 自社のクラブIDの取得（PNE.club管理画面=>表示設定）
* （オプション）コースIDの取得（PNE.club管理画面=>コース設定）
* WordPress内に、プレミアム読者としてログインさせるユーザーの追加
 * 権限は必ず「購読者」、名前等は自由

## インストール

* WP-Membersプラグインのインストール https://ja.wordpress.org/plugins/wp-members/
* WP-PNEclubプラグインのインストール https://github.com/tejima/WP-PNEclub
 * wp-content/plugins にpneclub.phpを置き、WordPress管理画面からプラグインを有効にする

## WP-PNEclub設定

* pneclub.php内の設定値を以下を参考にして変更する

```
add_option('pneclub_clubid', 'yx4ugVP6HA', null, 'yes');
※PNE.clubで加盟したクラブID

add_option('pneclub_targetcourse', 'ANY', null, 'yes');
※有料ユーザーとして認めるコースID（通常すべてを受け入れる”ANY”のままで良い）

add_option('pneclub_readerlogin', 'reader', null, 'yes');
add_option('pneclub_readerpassword', 'gatagatamichi', null, 'yes');
※プレミアム読者のユーザーアカウント

add_option('pneclub_refreshtime', 3, null, 'yes');
※ログイン後のリダイレクト待ち時間
```

## WP-Members設定

* ダイアログ入力値の設定

WP-Members設定＝＞ダイアログ
＝＞アクセス制限された投稿記事および固定ページ、ログインおよび登録フォームの上に表示

を以下のタグに変更する。

```
オンラインサービスをご利用いただくには会員認証が必要です。

<form action="(絶対パスをここにかく)/wp-admin/admin-post.php" method="post">
<label>e-mail</label>
<input type="hidden" name="action" value="pnelogin">
<input type="email" name="email"><br>
<label>password</label>
<input type="password" name="password"><br>
<input type="submit" value="ログイン">
</form>
<br>

※まだ会員登録がお済みでない方は<a href="/premium">会員登録</a>にお進みください。
```

* 公開範囲等の設定

クラブの運営スタイルに合わせて適宜変更する。

参考設定値

<img src="http://p.pne.jp/d/201511111446.png">
<img src="http://p.pne.jp/d/201511111447.png">
