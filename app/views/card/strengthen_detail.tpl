<hr>
強化するカード
<hr>
<img src="{$html->webroot}img/sample01.png" />
○○レッド<br />
赤 ノーマル<br />
Lv1 攻1000 防 2000<br />
<input type="button" value="変更" /><br />
<input type="button" value="一括強化する" /><br />
<hr>
合わせるカードを選ぼう
<hr>
<select name="name">
	<option value="value1">GET順</option>
	<option value="value1">○○順</option>
</select>
<input type="button" value="並替え" /><br />

<hr>
<img src="{$html->webroot}img/sample01.png" />
○○レッド<br />
赤 ノーマル<br />
Lv1 攻1000 防 2000<br />
<input type="button" value="合わせる" onclick="location.href='{$html->webroot}card/strengthen_confirm'"/><br />