仲間一覧<br />
<hr>
{foreach from=$friends item=friend}
	11/03 0:00<br />
	<img src="{$friend.thumbnailUrl}" /><a href="">{$friend.nickname}</a><br />
	<a href="">仲間解除</a>
{/foreach}
<hr>
<input type="button" value="仲間を探す" />