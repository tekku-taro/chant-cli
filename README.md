# Chant Cli

PHP製コンソールアプリケーション作成用の簡易フレームワークです。簡単にコマンドを作成し、コマンドラインで実行できます。

## 特徴

- コンソールアプリケーションを作成するための、基本的な機能を提供するフレームワーク
- 個々のコマンドに対する処理をコマンドクラスに記述し、アプリに登録するだけで、実行可能
- 入力するコマンドに関して、オプション引数やパラメーター、フラグを追加できる
- 出力する文字列に色を付けたり、テーブルで整形できる
- ユーザへ質問や確認するメソッドなどが用意されていて、対話的に処理を実行できる

## ディレクトリ構造

```
src
├── app	
│   ├── bootstrap
│   │   └── config.php	                       # 設定ファイル
│   └── console	
│        ├── commands						 # アプリのコマンドクラス
│        │   ├── TestCommand.php
│        │   └── TestCommand2.php	
│        └── CommandList.php				  # 登録されたコマンドのリスト
├── libs									# ライブラリ本体
│   ├── Application.php		            	   # アプリケーションクラス
│   ├── Errors								 # 例外クラス
│   ├── IOInterface		            		   # 入出力関連
│   ├── Command		            			  # コマンド継承元と登録	
│   │   ├── Command.php		
│   │   └── CommandRegistry.php
│   ├── Signature			　　　   		   # 入力するコマンドの情報
│   └── Utility	 				    		 # ユーティリティ
... etc
├── .env				             	      # 環境変数情報
└── chant				                      # appのエントリーポイント
```



## 基本的な使い方

1. コマンド作成のための以下のコマンドをターミナル上で実行

   ```bash
   # ./chant command:make [コマンド名]
   ./chant command:make SampleCommand
   ```

   app/console/commands 配下に **SampleCommand.php** ファイルが作成される

2. SampleCommand.php ファイルを編集

   ```php
   <?php
   namespace Taro\App\Console\Commands;
   
   use Taro\Libs\Command\Command;
   
   class SampleCommand extends Command
   {
       // ターミナル上で入力するコマンドシグネチャ
       // オプション引数を [] で囲んで追加する。必須でない場合は 最後に ? を付ける
       public $signature = 'command:sample_command [arg1] [arg2?]';
   
       // パラメーター cf. ['name', 'age']  "--"は省略
       public $params = [];
       
       // フラグ cf. ['a','b','c']  "-"は省略
       public $flags = [];
   
       // コマンドの説明
       public $description = 'SampleCommand Class';
   
       // コマンド実行時の処理本体
       public function handle()
       {
           // Here is where you write any script executed by this command
           $this->textInfo('sample_command command (' . $this->signature . ')');
   
   
       }
   }
   ```

   

3. Taro\App\Console\CommandList クラスに新規クラスを追加

   ```php
   use Taro\App\Console\Commands\SampleCommand; <= 追加
   
   class CommandList
   {
   
       public static $commands = [
           // SomeCommand::class
           ListRegisteredCommands::class,
           HelpCommand::class,
           MakeCommand::class,
           ...
           
           SampleCommand::class, <= 追加
       ];
   }
   ```

   

4. 新規に作成したコマンドをターミナル上で実行

   ```bash
   ./chant command:sample_command
   ```




## ターミナルでのコマンド実行方法

windows環境など、shebang を使わない場合は、phpコマンドで、 chant.php ファイルを実行する

```php
# bash を使わない場合は php コマンドで実行
php chant.php 実行するコマンド
```

shebang を使い、直接実行する

```bash
# chant ファイルの先頭の shebang を実行環境に合わせて変更
#!/usr/bin/php

# ファイル権限の変更
chmod +x ./chant

# chant ファイルを直接実行
./chant  実行するコマンド
```



### コマンドシグネチャ

基本のコマンドとオプション引数からなる

オプション引数は、必須かどうかを設定できる

```php
// 必須
[arg1]
// 必須でない (引数名の最後に ? を付ける)
[arg2?]
```

パラメーターとフラグは、それぞれの**プロパティの配列**に追加する



### オプション引数・パラメーター・フラグの取得方法

コマンド入力されたオプション引数などの値は、コマンドクラス内で以下のように取得できる

```bash
# コマンドシグネチャ
public $signature = 'command:test [arg1] [arg2?]';
public $params = ['name', 'is_test'];
public $flags = ['x','y','z'];

# 入力されたコマンド
./chant command:test alpha --name=hoge --is_test -x
```

コマンドクラスの handle() メソッド内で

```php
public function handle()
{
	// オプション引数
    $arg1 = $this->argument('arg1'); // $arg1 == 'alpha'

    // name パラメーター
    $name = $this->parameter('name'); // $name == 'hoge'
    
    // is_test パラメーター (値が指定されていない場合は、trueを返す)
    $is_test = $this->parameter('is_test'); // $is_test == true
    
    // x フラグ (フラグがあれば true を返す)
    $x = $this->flag('x'); // $x == true
}
```



## コマンドライン入出力

### 文字列の出力

```php
public function handle()
{
 	// 文字列を表示
    $this->text('test');
  	// 赤色文字列を表示
    $this->textDanger('test');
 	// 黄色文字列を表示
    $this->textWarning('test');    
 	// 緑色文字列を表示
    $this->textInfo('test');    
 	// 緑背景色の文字列を表示
    $this->success('test'); 
 	// 赤背景色の文字列を表示
    $this->error('test');     
}
```

### ユーザーへの質問

```php
public function handle()
{
    // question('質問文を書く')
    // ユーザーの入力が戻り値
    $result = $this->question('question1?');
}
```

### ユーザーへの確認

```php
public function handle()
{
    // confirm('確認文を書く')
    // ユーザーの入力に応じて、true/false が返される
    // yes/y なら true, それ以外は false
    $result = $this->confirm('confirm?');
}
```

### テーブルの出力

```php
public function handle()
{
    // 2次元データを見やすいテーブルで表示する
    // $this->table(headers[], body[ row1[],row2[],row3[] ... ], '色指定')
    // 指定できる色は black,red,green,blue,cyan,yellow, white
    $this->table(
        ['col1', 'col2'],
        [
            ['1cell1', '1cell2'],
            ['2cell1', '2cell2'],
            ['3cell1', '3cell2'],
            ['4cell1', '4cell2'],
        ],
        'green'
    );	
}
```

## 登録済みコマンド

| コマンド名    | シグネチャ                        | 説明                                               |
| ------------- | --------------------------------- | -------------------------------------------------- |
| Test コマンド | command:test [option1] [option2?] | コマンド各種テスト用                               |
| Help コマンド | command:help                      | Cli のヘルプを表示                                 |
| List コマンド | command:list                      | 登録済みコマンドのリストを表示                     |
| Make コマンド | command:make ***コマンド名***     | クラス名が**コマンド名**のコマンドクラス雛形を作成 |



## ライセンス (License)

**Chant Cli**は[MIT license](https://opensource.org/licenses/MIT)のもとで公開されています。

**Chant Cli** is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).# Chant Cli