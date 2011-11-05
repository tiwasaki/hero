マイホーム<br />
<hr>
ニュースを表示?
<hr>
<a href="{$html->webroot}map">話を進める</a><br />
<a href="{$html->webroot}gashapon">ガチャ</a>
<a href="">チーム</a>

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