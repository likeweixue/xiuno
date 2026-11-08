<?php

!defined('DEBUG') AND exit('Access Denied.');

// hook search_start.php

$keyword = param('keyword', '');
$page = param('page', 1);
$pagesize = 20;
$keyword = trim($keyword);

$header['title'] = lang('search').' - '.$conf['sitename'];
$header['mobile_title'] = lang('search');

if($keyword == '') {
	// 空关键词，显示搜索框
	$threadlist = array();
	$pagination = '';
	$total = 0;
	include _include(APP_PATH.'view/htm/search.htm');
	return;
}

// 关键词长度限制
$keyword = mb_substr($keyword, 0, 50, 'UTF-8');

// hook search_before.php

// 1. 按标题搜索
$titlelist = thread_find_by_keyword($keyword);

// 2. 按内容搜索（帖子正文）
$cond = array('isfirst'=>1, 'message'=>array('LIKE'=>$keyword));
$postlist = post_find($cond, array('pid'=>-1), $page, $pagesize);

// 合并标题命中的帖子和内容命中的帖子
$threadlist = array();
if($titlelist) {
	foreach($titlelist as $_thread) {
		$threadlist[$_thread['tid']] = $_thread;
	}
}
if($postlist) {
	foreach($postlist as $_post) {
		$tid = $_post['tid'];
		if(!isset($threadlist[$tid])) {
			$thread = thread_read($tid);
			if(!empty($thread)) $threadlist[$tid] = $thread;
		}
	}
}

// 权限过滤
thread_list_access_filter($threadlist, $gid);

$total = count($threadlist);
$pagination = pagination(url("search", array('keyword'=>$keyword)), $total, $page, $pagesize);

// 分页截取
$offset = ($page - 1) * $pagesize;
$threadlist = array_slice($threadlist, $offset, $pagesize, TRUE);

// hook search_end.php

include _include(APP_PATH.'view/htm/search.htm');

?>
