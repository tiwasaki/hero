自分の情報<br />
ID:{$self.id}<br />
ニックネーム:{$self.nickname}<br />
サムネイル画像:<img src="{$self.thumbnailUrl}" /><br />
<hr>
フレンドの情報<br />
{foreach from=$friends item=friend}
	{$friend.nickname}<img src="{$friend.thumbnailUrl}" /><br />
{/foreach}
<hr>
<a onClick="window.mediator.pushMessage('')" href="#">バトルSTART</a>