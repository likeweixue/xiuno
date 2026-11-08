<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1, 'list');

// hook admin_sidebar_start.php

if($action == 'list') {
	
	// hook admin_sidebar_list_get_post.php
	
	if($method == 'GET') {
		
		$linklist = link_list();
		
		// 侧边栏面板开关
		$input = array();
		$input['calendar_on'] = form_radio_yes_no('calendar_on', array_value($conf, 'sidebar_calendar_on', 0));
		$input['tag_on'] = form_radio_yes_no('tag_on', array_value($conf, 'sidebar_tag_on', 0));
		$input['link_on'] = form_radio_yes_no('link_on', array_value($conf, 'sidebar_link_on', 0));
		
		$header['title'] = lang('admin_sidebar');
		$header['mobile_title'] = lang('admin_sidebar');
		
		include _include(ADMIN_PATH.'view/htm/sidebar.htm');
		
	} elseif($method == 'POST') {
		
		$namearr = param('name', array(''));
		$urlarr = param('url', array(''));
		$rankarr = param('rank', array(0));
		$deletearr = param('delete', array());
		
		// 删除标记的友链
		if(!empty($deletearr)) {
			foreach($deletearr as $linkid=>$v) {
				if($v) link_delete($linkid);
			}
		}
		
		// 更新/新增友链
		foreach($namearr as $linkid=>$name) {
			$name = trim($name);
			$url = trim(array_value($urlarr, $linkid, ''));
			if($name == '' && $url == '') continue;
			if($linkid > 0) {
				link_update($linkid, array('name'=>$name, 'url'=>$url, 'rank'=>array_value($rankarr, $linkid, 0)));
			} else {
				link_create(array('name'=>$name, 'url'=>$url, 'rank'=>array_value($rankarr, $linkid, 0)));
			}
		}
		
		message(0, lang('save_successfully'));
	}
}

// hook admin_sidebar_end.php

?>
