<?php

// hook model_tag_start.php

function tag__create($arr) {
	$r = db_insert('tag', $arr);
	return $r;
}

function tag__update($tagid, $arr) {
	$r = db_update('tag', array('tagid'=>$tagid), $arr);
	return $r;
}

function tag__read($tagid) {
	$tag = db_find_one('tag', array('tagid'=>$tagid));
	return $tag;
}

function tag__delete($tagid) {
	$r = db_delete('tag', array('tagid'=>$tagid));
	db_delete('thread_tag', array('tagid'=>$tagid));
	return $r;
}

function tag__find($cond = array(), $orderby = array('rank'=>-1, 'tagid'=>-1), $page = 1, $pagesize = 100) {
	$taglist = db_find('tag', $cond, $orderby, $page, $pagesize, 'tagid');
	return $taglist;
}

function tag_read($tagid) {
	return tag__read($tagid);
}

// 创建标签
function tag_create($arr) {
	$name = array_value($arr, 'name', '');
	$name = trim($name);
	if($name == '') return FALSE;
	// 重名检查
	$exist = db_find_one('tag', array('name'=>$name));
	if($exist) return $exist['tagid'];
	$arr['name'] = $name;
	$arr['threads'] = 0;
	$arr['rank'] = array_value($arr, 'rank', 0);
	return tag__create($arr);
}

// 删除标签
function tag_delete($tagid) {
	return tag__delete($tagid);
}

// 更新标签
function tag_update($tagid, $arr) {
	return tag__update($tagid, $arr);
}

// 标签列表
function tag_list() {
	return tag__find(array(), array('rank'=>-1, 'tagid'=>-1), 1, 1000);
}

// 帖子的标签
function tag_find_by_tid($tid) {
	$list = db_find('thread_tag', array('tid'=>$tid), array(), 1, 100, 'tagid');
	$tagarr = array();
	if($list) {
		$tagids = arrlist_values($list, 'tagid');
		$taglist = db_find('tag', array('tagid'=>$tagids), array(), 1, 100, 'tagid');
		foreach($taglist as $tag) {
			$tagarr[$tag['tagid']] = $tag['name'];
		}
	}
	return $tagarr;
}

// 设置帖子的标签（重建关联）
function tag_set_by_tid($tid, $tagnames) {
	// 清除旧关联
	db_delete('thread_tag', array('tid'=>$tid));
	if(empty($tagnames)) return TRUE;
	if(!is_array($tagnames)) $tagnames = array($tagnames);
	foreach($tagnames as $name) {
		$name = trim($name);
		if($name == '') continue;
		$tagid = tag_create(array('name'=>$name));
		if(!$tagid) continue;
		// 关联
		$exist = db_find_one('thread_tag', array('tagid'=>$tagid, 'tid'=>$tid));
		if(!$exist) {
			db_insert('thread_tag', array('tagid'=>$tagid, 'tid'=>$tid));
			// 帖子数 +1
			db_update('tag', array('tagid'=>$tagid), array('threads+'=>1));
		}
	}
	return TRUE;
}

// 标签下的帖子列表
function tag_thread_find($tagid, $page = 1, $pagesize = 20) {
	$list = db_find('thread_tag', array('tagid'=>$tagid), array('tid'=>-1), $page, $pagesize, 'tid');
	if(empty($list)) return array();
	$tids = arrlist_values($list, 'tid');
	$threadlist = thread_find_by_tids($tids, array('tid'=>-1));
	return $threadlist;
}

// 标签帖子数
function tag_thread_count($tagid) {
	return db_count('thread_tag', array('tagid'=>$tagid));
}

// hook model_tag_end.php

?>
