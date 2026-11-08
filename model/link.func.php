<?php

// hook model_link_start.php

function link__create($arr) {
	return db_insert('link', $arr);
}

function link__update($linkid, $arr) {
	return db_update('link', array('linkid'=>$linkid), $arr);
}

function link__delete($linkid) {
	return db_delete('link', array('linkid'=>$linkid));
}

function link__find($cond = array(), $orderby = array('rank'=>-1, 'linkid'=>-1), $page = 1, $pagesize = 100) {
	return db_find('link', $cond, $orderby, $page, $pagesize, 'linkid');
}

function link_create($arr) {
	$arr['name'] = trim(array_value($arr, 'name', ''));
	$arr['url'] = trim(array_value($arr, 'url', ''));
	$arr['rank'] = array_value($arr, 'rank', 0);
	if($arr['name'] == '' || $arr['url'] == '') return FALSE;
	return link__create($arr);
}

function link_delete($linkid) {
	return link__delete($linkid);
}

function link_update($linkid, $arr) {
	return link__update($linkid, $arr);
}

function link_list() {
	return link__find(array(), array('rank'=>-1, 'linkid'=>-1), 1, 200);
}

// hook model_link_end.php

?>
