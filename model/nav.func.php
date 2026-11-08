<?php

// hook model_nav_start.php

// ------------> 最原生的 CURD，无关联其他数据。

function nav__create($arr) {
	// hook model_nav__create_start.php
	$r = db_create('nav', $arr);
	// hook model_nav__create_end.php
	return $r;
}

function nav__update($id, $arr) {
	// hook model_nav__update_start.php
	$r = db_update('nav', array('id'=>$id), $arr);
	// hook model_nav__update_end.php
	return $r;
}

function nav__read($id) {
	// hook model_nav__read_start.php
	$nav = db_find_one('nav', array('id'=>$id));
	// hook model_nav__read_end.php
	return $nav;
}

function nav__delete($id) {
	// hook model_nav__delete_start.php
	$r = db_delete('nav', array('id'=>$id));
	// hook model_nav__delete_end.php
	return $r;
}

function nav__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {
	// hook model_nav__find_start.php
	$navlist = db_find('nav', $cond, $orderby, $page, $pagesize, 'id');
	// hook model_nav__find_end.php
	return $navlist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。

function nav_create($arr) {
	// hook model_nav_create_start.php
	$r = nav__create($arr);
	nav_list_cache_delete();
	// hook model_nav_create_end.php
	return $r;
}

function nav_update($id, $arr) {
	// hook model_nav_update_start.php
	$r = nav__update($id, $arr);
	nav_list_cache_delete();
	// hook model_nav_update_end.php
	return $r;
}

function nav_read($id) {
	// hook model_nav_read_start.php
	$nav = nav__read($id);
	nav_format($nav);
	// hook model_nav_read_end.php
	return $nav;
}

function nav_delete($id) {
	// hook model_nav_delete_start.php
	$r = nav__delete($id);
	nav_list_cache_delete();
	// hook model_nav_delete_end.php
	return $r;
}

function nav_find($cond = array(), $orderby = array('rank'=>-1, 'id'=>1), $page = 1, $pagesize = 1000) {
	// hook model_nav_find_start.php
	$navlist = nav__find($cond, $orderby, $page, $pagesize);
	if($navlist) foreach ($navlist as &$nav) nav_format($nav);
	// hook model_nav_find_end.php
	return $navlist;
}

// ------------> 其他方法

function nav_format(&$nav) {
	// hook model_nav_format_start.php
	if(empty($nav)) return;
	// hook model_nav_format_end.php
}

function nav_count($cond = array()) {
	// hook model_nav_count_start.php
	$n = db_count('nav', $cond);
	// hook model_nav_count_end.php
	return $n;
}

function nav_maxid() {
	// hook model_nav_maxid_start.php
	$n = db_maxid('nav', 'id');
	// hook model_nav_maxid_end.php
	return $n;
}

// 从缓存中读取 nav_list 数据
function nav_list_cache() {
	global $conf, $navlist;
	$navlist = cache_get('navlist');
	// hook model_nav_list_cache_start.php
	if($navlist === NULL || $navlist === FALSE) {
		$navlist = nav_find();
		cache_set('navlist', $navlist);
	}
	// hook model_nav_list_cache_end.php
	return $navlist;
}

// 更新 navlist 缓存
function nav_list_cache_delete() {
	global $conf;
	static $deleted = FALSE;
	if($deleted) return;
	// hook model_nav_list_cache_delete_start.php
	cache_delete('navlist');
	$deleted = TRUE;
	// hook model_nav_list_cache_delete_end.php
}

// 根据 fid 查找菜单（版块自动同步时使用）
function nav_find_by_fid($fid) {
	// hook model_nav_find_by_fid_start.php
	$nav = db_find_one('nav', array('fid'=>$fid));
	// hook model_nav_find_by_fid_end.php
	return $nav;
}

// hook model_nav_end.php

?>
