仲間一覧<br />
<hr>
{foreach from=$friends item=friend}
	<img src="{$friend.thumbnailUrl}" /><a href="">{$friend.nickname}</a><a href="">仲間解除</a>
{/foreach}
<hr>
<input type="button" value="仲間を探す" />