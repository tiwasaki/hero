マイホーム<br />
<hr>
ニュースを表示?
<hr>
<a href="{$html->webroot}map">【シナリオへ移動】</a>(Unity起動)<br />
<a href="{$html->webroot}gashapon">【ガチャ】</a>
<a href="{$html->webroot}team">【チーム】</a>

<hr>
{$self.nickname}さんの<br />グッジョブ帳
<hr>
{foreach from=$friends item=friend}
	11/03 0:00<br />
	<img src="{$friend.thumbnailUrl}" /><a href="">{$friend.nickname}</a><br />
	グッジョブ
	<hr>
{/foreach}
<a href="">もっと見る</a>
<hr>
仲間の活躍
<hr>
{foreach from=$friends item=friend}
	11/03 0:00<br />
	<img src="{$friend.thumbnailUrl}" /><a href="">{$friend.nickname}</a><br />
	○○を達成!!
	<hr>
{/foreach}
<a href="">もっと見る</a>
<hr>
もっと仲間を増やそう<br />
<input type="button" value="仲間を探す" />