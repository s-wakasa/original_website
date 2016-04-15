<?php

//HTML上の特殊文字を置換する関数
function h($str)
{
	return htmlspecialchars($str, ENT_QUOTES);
}
