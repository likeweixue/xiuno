<?php

!defined('DEBUG') AND exit('Access Denied.');

include _include(APP_PATH.'model/modlog.func.php');

$action = param(1);



// hook mod_start.php

if($action == 'top') {
	
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_top.htm');
		
	} else {
		
		$top = param('top', 0);
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		// hook mod_top_start.php
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if($top == 3 && ($gid != 1 && $gid != 2)) {
				continue;
			}
			if(forum_access_mod($fid, $gid, 'allowtop')) {
				thread_top_change($tid, $top);
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'top',
				);
				
				// hook mod_top_log_create_before.php
				modlog_create($arr);
				
			}
		}
		
		// hook mod_top_end.php
		
		message(0, lang('set_completely'));
	}

} elseif($action == 'close') {
		
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_close.htm');
		
	} else {
		
		$close = param('close', 0);
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		// hook mod_close_start.php
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowtop')) {
				thread_update($tid, array('closed'=>$close));
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'close',
				);
				
				// hook mod_close_log_create_before.php
				modlog_create($arr);
			}
		}
		
		// hook mod_close_end.php
		
		message(0, lang('set_completely'));
	}
	
} elseif($action == 'delete') {
	
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_delete.htm');
		
	} else {
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		
		$threadlist = thread_find_by_tids($tidarr);
		
		// hook mod_delete_start.php
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowdelete')) {
				thread_delete($tid);
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'delete',
				);
				// hook mod_delete_log_create_before.php
				modlog_create($arr);
			}
		}
		
		// hook mod_delete_end.php
		
		message(0, lang('delete_completely'));
	}
	
	
} elseif($action == 'move') {

	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_move.htm');
		
	} else {
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		$newfid = param('newfid', 0);
		!forum_read($newfid) AND message(1, lang('forum_not_exists'));
		
		// hook mod_move_start.php
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowmove')) {
				if($fid == $newfid) continue;
				thread_update($tid, array('fid'=>$newfid));
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'move',
				);
				// hook mod_move_log_create_before.php
				modlog_create($arr);
			}
		}
		
		// 清理下缓存
		forum_list_cache_delete();
		
		// hook mod_move_end.php
		
		message(0, lang('move_completely'));
		
	}
	
} elseif($action == 'deleteuser') {
	
	$_uid = param(2, 0);
	
	$method != 'POST' AND message(-1, 'Method error');
	
	empty($group['allowdeleteuser']) AND message(-1, lang('insufficient_delete_user_privilege'));
	
	$u = user_read($_uid);
	empty($u) AND message(-1, lang('user_not_exists_or_deleted'));
	
	$u['gid'] < 6 AND message(-1, lang('cant_delete_admin_group'));
	
	// hook mod_delete_user_start.php
	
	$r = user_delete($_uid);
	$r === FALSE AND message(-1, lang('delete_failed'));

	// hook mod_delete_user_end.php
	
	message(0, lang('delete_successfully'));
} elseif($action == 'digest') {
	
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_digest.htm');
		
	} else {
		
		$digest = param('digest', 0);
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		// hook mod_digest_start.php
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowtop')) {
				thread_update($tid, array('digest'=>$digest));
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'digest',
				);
				modlog_create($arr);
			}
		}
		
		// hook mod_digest_end.php
		
		message(0, lang('set_completely'));
	}
	
} elseif($action == 'highlight') {
	
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_highlight.htm');
		
	} else {
		
		$color = param('color', '');
		$bold = param('bold', 0);
		$italic = param('italic', 0);
		$underline = param('underline', 0);
		
		// 拼装高亮样式值，格式：颜色;粗体;斜体;下划线 如 #ff0000;1;0;0
		$highlight = '';
		if($color) $highlight = $color;
		if($bold || $italic || $underline) {
			$highlight .= ';'.($bold ? '1' : '0').';'.($italic ? '1' : '0').';'.($underline ? '1' : '0');
		}
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		// hook mod_highlight_start.php
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowtop')) {
				thread_update($tid, array('highlight'=>$highlight));
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'highlight',
				);
				modlog_create($arr);
			}
		}
		
		// hook mod_highlight_end.php
		
		message(0, lang('set_completely'));
	}
}

// hook mod_end.php

?>