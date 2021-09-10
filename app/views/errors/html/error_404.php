<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$heading;?></title>
	<style>
		html, body {
			background-color: #fff;
			color: #636b6f;
			font-family: Helvetica, Arial, sans-serif;
			font-weight: 100;
			height: 100vh;
			margin: 0;
		}
		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		.message {
			font-size: 18px;
			text-align: center;
			padding: 10px; text-align:left; line-height:10px;
		}
		.box{
			height: 100vh;
			position: relative;
			align-items: center;
			display: flex;
			justify-content: center;
		}
	</style>
</head>
<body>
	<div class="box">
		<div class="message">
			<?=$heading;?><br><small><?=$message;?></small>
		</div>
	</div>
</body>
</html>
