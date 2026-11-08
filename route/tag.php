<?php

!defined('DEBUG') AND exit('Access Denied.');

// hook tag_start.php

$tagid = param(1, 0);
$page = param(2, 1);
$pagesize = $conf['pagesize'];

$tag = tag_read($tagid);
empty($tag) AND message(3, lang('tag_not_exists'));

$total = tag_thread_count($tagid);
$pagination = pagination(url("tag-$tagid-{page}"), $total, $page, $pagesize);
$threadlist = tag_thread_find($tagid, $page, $pagesize);
thread_list_access_filter($threadlist, $gid);

$header['title'] = $tag['name'].' - '.$conf['sitename'];
$header['mobile_title'] = $tag['name'];

// hook tag_end.php

include _include(APP_PATH.'view/htm/tag.htm');

?>
