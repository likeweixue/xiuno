<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1, 'list');

// hook admin_tag_start.php

if($action == 'list') {
	
	// hook admin_tag_list_get_post.php
	
	if($method == 'GET') {
		
		$taglist = tag_list();
		
		$header['title'] = lang('admin_tag_list');
		$header['mobile_title'] = lang('admin_tag_list');
		
		include _include(ADMIN_PATH.'view/htm/tag_list.htm');
		
	} elseif($method == 'POST') {
		
		$tagarr = param('tag', array(''));
		$rankarr = param('rank', array(0));
		$deletearr = param('delete', array());
		
		// 删除标记的标签
		if(!empty($deletearr)) {
			foreach($deletearr as $tagid=>$v) {
				if($v) tag_delete($tagid);
			}
		}
		
		// 更新/新增标签
		foreach($tagarr as $tagid=>$name) {
			$name = trim($name);
			if($name == '') continue;
			if($tagid > 0) {
				// 更新
				tag_update($tagid, array('name'=>$name, 'rank'=>array_value($rankarr, $tagid, 0)));
			} else {
				// 新增
				tag_create(array('name'=>$name, 'rank'=>array_value($rankarr, $tagid, 0)));
			}
		}
		
		message(0, lang('save_successfully'));
	}
	
} elseif($action == 'panel') {
	
	// hook admin_tag_panel_get_post.php
	
	if($method == 'GET') {
		
		// 开关已合并到"侧边栏"页面，GET 直接跳转
		http_location(url('sidebar-list'));
		
	} elseif($method == 'POST') {
		
		$calendar_on = param('calendar_on', 0);
		$tag_on = param('tag_on', 0);
		$link_on = param('link_on', 0);
		
		$replace = array();
		$replace['sidebar_calendar_on'] = $calendar_on;
		$replace['sidebar_tag_on'] = $tag_on;
		$replace['sidebar_link_on'] = $link_on;
		
		file_replace_var(APP_PATH.'conf/conf.php', $replace);
		
		message(0, lang('save_successfully'));
	}
}

// hook admin_tag_end.php

?>
