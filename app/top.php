<?php
//レイアウト未編集
session_start();

require_once 'defineutil.php';
require_once '../twitteroauth/autoload.php';
require_once '../util/scriptutil.php';

use Abraham\TwitterOAuth\TwitterOAuth;

if(!empty($_POST["logout"])){
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
    	setcookie(session_name(), '', time()-42000, '/');
	}
	session_destroy();
	echo "ログアウトしてる<br/>";
}

//セッションに入れておいたさっきの配列
if(!empty($_SESSION['access_token'])&& $_SESSION['access_token']!=''){
	
	$access_token = $_SESSION['access_token'];
	
	//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$user = $connection->get("statuses/home_timeline",array('count'=>'50'));
}
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
<?php if(empty($_SESSION['access_token'])){ ?>
	<body>
	<a href="top.php"><img src="../S0mmelier-logo.png" class="img-rounded"></a>
	<br>
	<a href="login.php"><img src="../twitter.png"></a>
	</body>
<?php }else{?>
<body>
<div class="container">
<div class="row">
			<div class="col-xs-4">
				<a href="top.php"><img src="../S0mmelier-logo.png" class="img-rounded"></a>
				<ul class="nav nav-pills nav-stacked">
					<li class="active"><a href="top.php">ホーム</a></li>
					<li><a href="search.php">いいね順</a></li>
					<li><a href="search.php">ソート2</a></li>
					<li><a href="search.php">ソート3</a></li>
					<li><a href="search.php">ソート4</a></li>
				</ul>
				<form action="search.php" method="GET" class="form-inline">
				<input type="text" class="form-control" name="search"/><input type="submit" class="btn btn-primary" value="検索"/>
				</form>
			</div>
    
	<div class="col-xs-8">
	
	<?php foreach ($user as $tweetval) { ?>
			
			<div class="panel panel-default">
				<a href="<?php echo "https://twitter.com/".h($tweetval->user->screen_name)."/status/".$tweetval->id; ?>">
    			<h3><img src="<?php echo $tweetval->user->profile_image_url;?>" class="img-rounded"><?php echo h($tweetval->user->name).'&nbsp;&nbsp;@'.h($tweetval->user->screen_name);?></h3></a>
				<?php echo h($tweetval->text)."<br/>".$tweetval->favorite_count."いいね<br/>"; ?>
				<?php echo date('Y-m-d H:i:s', strtotime((string) $tweetval->created_at));?>
    		</div>
	<?php 
	} ?>
	</div>
</div>
</div>


<form name="status" method="POST" action="top.php">
<input type=hidden name="logout" value="true">
<input type=submit value="ログアウト">
</form>
<?php

var_dump($user);?>
</body>
<?php } ?>
</html>