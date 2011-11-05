<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		{$html->charset()}
		<title>{$title_for_layout}</title>
		<script type="text/javascript" src="http://pf-sb.gree.jp/js/app/touch.js"></script>
		{$scripts_for_layout}
	</head>
	<body>
		{$content_for_layout}
		<hr>
		<ul>
			<li><a href="{$html->webroot}top">Top</a></li>
			<li><a href="{$html->webroot}my_home">マイホーム</a></li>
			<li><a href="{$html->webroot}card/lists">カード</a></li>
			<li><a href="{$html->webroot}gashapon">ガチャ</a></li>
			<li><a href="">その他</a></li>
		</ul>
		<hr>
		
		{$cakeDebug}
	</body>
</html>