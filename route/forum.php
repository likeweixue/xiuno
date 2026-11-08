<?php

!defined('DEBUG') AND exit('Access Denied.');

// hook forum_start.php
$fid = param(1, 0);
$page = param(2, 1);
$orderby = param('orderby');
$digest = param('digest', 0);
$extra = array(); // 给插件预留

$active = 'default';
!in_array($orderby, array('tid', 'lastpid', 'views', 'top')) AND $orderby = 'lastpid';
if($orderby == 'tid') $active = 'new';
elseif($orderby == 'views') $active = 'hot';
elseif($orderby == 'top') $active = 'top';
elseif($digest) $active = 'digest';
$extra['orderby'] = $orderby;
$digest AND $extra['digest'] = 1;

$forum = forum_read($fid);
empty($forum) AND message(3, lang('forum_not_exists'));
forum_access_user($fid, $gid, 'allowread') OR message(-1, lang('insufficient_visit_forum_privilege'));
$pagesize = $conf['pagesize'];

// hook forum_top_list_before.php

$toplist = $page == 1 && $active == 'default' ? thread_top_find($fid) : array();

// 从默认的地方读取主题列表
$thread_list_from_default = 1;

// hook forum_thread_list_before.php

if($thread_list_from_default) {
	// 精华筛选
	if($digest) {
		$cond = array('fid'=>$fid, 'digest'=>array('>'=>0));
		$order = 'lastpid';
	} elseif($orderby == 'top') {
		// 置顶筛选：只显示置顶帖
		$cond = array('fid'=>$fid, 'top'=>array('>'=>0));
		$order = 'top';
	} else {
		$cond = array('fid'=>$fid);
		$order = $orderby;
	}
	$pagination = pagination(url("forum-$fid-{page}", $extra), $forum['threads'], $page, $pagesize);
	$threadlist = thread_find($cond, array($order=>-1), $page, $pagesize);
	
	// 默认排序且第一页时，合并置顶帖
	if($active == 'default' && $page == 1 && !empty($toplist)) {
		$threadlist = $toplist + $threadlist;
	}
}

$header['title'] = $forum['seo_title'] ? $forum['seo_title'] : $forum['name'].'-'.$conf['sitename'];
$header['mobile_title'] = $forum['name'];
$header['mobile_link'] = url("forum-$fid");
$header['keywords'] = '';
$header['description'] = $forum['brief'];

$_SESSION['fid'] = $fid;

// hook forum_end.php

include _include(APP_PATH.'view/htm/forum.htm');

?>