<?php

session_start();

require_once 'defineutil.php';
require_once '../twitteroauth/autoload.php';
require_once '../util/scriptutil.php';

use Abraham\TwitterOAuth\TwitterOAuth;

//セッションに入れておいたさっきの配列
$access_token = $_SESSION['access_token'];

//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

if(!empty($_GET['search'])){
	$src = $connection->get("search/tweets", array('q' => $_GET['search'],'count' => '50'));
}else{
	$src = $connection->get("statuses/home_timeline");
}//検索フォームが空打ちの場合でもホームタイムラインを表示したい
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- BootstrapのCSS読み込み -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../js/bootstrap.min.js"></script>
	<!--  <meta http-equiv="Content-type" content="text/html; charset=UTF-8" /> -->
<title>S0mmelier</title>
		<style>
			body {background-color:#f2dae8}
		</style>
</head>

<body>
<div class="container">
<div class="row">
			<div class="col-xs-4">
				<a href="top.php"><img src="../S0mmelier-logo.png" class="img-rounded"></a>
				<ul class="nav nav-pills nav-stacked">
					<li <?php if(empty($_GET['search']) && empty($_GET['sort'])){echo 'class="active"';}?>><a href="top.php">ホーム</a></li>
					<li <?php if(!empty($_GET['sort']) && $_GET['sort']=='fav'){echo 'class="active"';}?>><a href="search.php?sort=fav<?php if(!empty($_GET['search'])){echo '&search='.$_GET['search'];}?>">いいね順</a></li>
					<li><a href="search.php">ソート2</a></li>
					<li><a href="search.php">ソート3</a></li>
					<li><a href="search.php">ソート4</a></li>
				</ul>
				<form action="search.php" method="GET" class="form-inline">
				<input type="text" class="form-control" name="search"/><input type="submit" class="btn btn-primary" value="検索"/>
				</form>
			</div>

	<div class="col-xs-8">
	
<?php if(!empty($_GET['search'])){
			$pass_higher = $src->statuses;
			
			//$pass_higher[0]->created_at = "nnn";代入サンプル
	  }else{
	  		$pass_higher = $src;
	  		
	  }//検索結果とホームタイムライン、取得内容によって必要なキーが異なる

if(!empty($_GET['sort'])&& $_GET['sort']=='fav'){
	foreach ($pass_higher as $key => $value){
  		$key_fav[$key] = $value->favorite_count;
	}
	array_multisort ( $key_fav , SORT_DESC , $pass_higher);
}



 foreach ($pass_higher as $tweetval) { ?>
		<div class="panel panel-default">
			<a href="<?php echo "https://twitter.com/".h($tweetval->user->screen_name)."/status/".$tweetval->id; ?>">
            <h3><img src="<?php echo $tweetval->user->profile_image_url;?>" class="img-rounded"><?php echo h($tweetval->user->name).'&nbsp;&nbsp;@'.h($tweetval->user->screen_name);?></h3>
            </a>
            <?php echo h($tweetval->text)."<br/>".$tweetval->favorite_count."いいね<br/>"; 
             echo date('Y-m-d H:i:s', strtotime((string) $tweetval->created_at));?>
        </div>
        <?php } 

var_dump($src);
?>
</div>
</div>
</div>
        </body>
</html>