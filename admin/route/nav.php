<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

// hook admin_nav_start.php

if(empty($action) || $action == 'list') {
	
	// hook admin_nav_list_get_post.php
	
	if($method == 'GET') {
		
		// hook admin_nav_list_get_start.php
		
		$header['title']        = lang('nav_admin');
		$header['mobile_title'] = lang('nav_admin');
		
		$navlist = nav_find();
		$maxid = nav_maxid();
		
		// hook admin_nav_list_get_end.php
		
		include _include(ADMIN_PATH."view/htm/nav_list.htm");
	
	} elseif($method == 'POST') {
		
		// 不转义（FALSE），存原始值，显示时再 htmlspecialchars 防 XSS
		$namearr = param('name', array(''), FALSE);
		$urlarr = param('url', array(''), FALSE);
		$rankarr = param('rank', array(0));
		
		// 加载现有菜单，用于判断新增/更新/删除
		$navlist = nav_find();
		
		// hook admin_nav_list_post_start.php
		
		foreach($namearr as $k=>$name) {
			$name = trim($name);
			if(empty($name)) continue; // 空名称跳过
			
			$arr = array(
				'name'=>$name,
				'url'=>array_value($urlarr, $k, ''),
				'rank'=>intval(array_value($rankarr, $k, 0)),
			);
			
			if(!isset($navlist[$k])) {
				// 添加 / add
				$arr['fid'] = 0; // 手动创建的菜单为纯自定义
				nav_create($arr);
			} else {
				// 编辑 / edit（保留原有 fid 关联）
				nav_update($k, $arr);
			}
		}
		
		// 删除 / delete：存在于旧列表但本次未提交（被删除的行）
		foreach($navlist as $k=>$v) {
			if(empty($namearr[$k])) {
				nav_delete($k);
			}
		}
		
		// hook admin_nav_list_post_end.php
		
		message(0, lang('save_successfully'));
	}

}

// hook admin_nav_end.php

?>
