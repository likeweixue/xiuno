<?php

return array(
	'setting' => array(
		'url'=>url('setting-base'), 
		'text'=>lang('setting'), 
		'icon'=>'icon-cog', 
		'tab'=> array (
			'base'=>array('url'=>url('setting-base'), 'text'=>lang('admin_setting_base')),
			'smtp'=>array('url'=>url('setting-smtp'), 'text'=>lang('admin_setting_smtp')),
			'nav'=>array('url'=>url('nav-list'), 'text'=>lang('nav_admin')),
			'sidebar'=>array('url'=>url('sidebar-list'), 'text'=>lang('admin_sidebar')),
		)
	),
	'style' => array(
		'url'=>url('style-color'), 
		'text'=>lang('style'), 
		'icon'=>'icon-paint-brush', 
		'tab'=> array (
			'color'=>array('url'=>url('style-color'), 'text'=>lang('admin_style_color')),
		)
	),
	'forum' => array(
		'url'=>url('forum-list'), 
		'text'=>lang('forum'), 
		'icon'=>'icon-comment',
		'tab'=> array (
			'list'=>array('url'=>url('forum-list'), 'text'=>lang('admin_forum_list')),
		)
	),
	'thread' => array(
		'url'=>url('thread-list'), 
		'text'=>lang('thread'), 
		'icon'=>'icon-comment',
		'tab'=> array (
			'list'=>array('url'=>url('thread-list'), 'text'=>lang('admin_thread_batch')),
		)
	),
	'tag' => array(
		'url'=>url('tag-list'), 
		'text'=>lang('tag'), 
		'icon'=>'icon-tags',
		'tab'=> array (
			'list'=>array('url'=>url('tag-list'), 'text'=>lang('admin_tag_list')),
		)
	),
	'user' => array(
		'url'=>url('user-list'), 
		'text'=>lang('user'), 
		'icon'=>'icon-user',
		'tab'=> array (
			'list'=>array('url'=>url('user-list'), 'text'=>lang('admin_user_list')),
			'group'=>array('url'=>url('group-list'), 'text'=>lang('admin_user_group')),
			'create'=>array('url'=>url('user-create'), 'text'=>lang('admin_user_create')),
		)
	),
	'other' => array(
		'url'=>url('other'), 
		'text'=>lang('other'), 
		'icon'=>'icon-wrench',
		'tab'=> array (
			'cache'=>array('url'=>url('other-cache'), 'text'=>lang('admin_other_cache')),
		)
	),
	'plugin' => array(
		'url'=>url('plugin'), 
		'text'=>lang('plugin'), 
		'icon'=>'icon-cogs',
		'tab'=> array (
			'local'=>array('url'=>url('plugin-local'), 'text'=>lang('admin_plugin_local_list')),
			'official_free'=>array('url'=>url('plugin-official_free'), 'text'=>lang('admin_plugin_official_free_list'), 'sub'=>array(
				'cate0'=>array('url'=>url('plugin-official_free-0-1'), 'text'=>lang('pugin_cate_0')),
				'cate1'=>array('url'=>url('plugin-official_free-1-1'), 'text'=>lang('pugin_cate_1')),
				'cate2'=>array('url'=>url('plugin-official_free-2-1'), 'text'=>lang('pugin_cate_2')),
				'cate3'=>array('url'=>url('plugin-official_free-3-1'), 'text'=>lang('pugin_cate_3')),
				'cate4'=>array('url'=>url('plugin-official_free-4-1'), 'text'=>lang('pugin_cate_4')),
				'cate99'=>array('url'=>url('plugin-official_free-99-1'), 'text'=>lang('pugin_cate_99')),
			)),
			'official_fee'=>array('url'=>url('plugin-official_fee'), 'text'=>lang('admin_plugin_official_fee_list'), 'sub'=>array(
				'cate0'=>array('url'=>url('plugin-official_fee-0-1'), 'text'=>lang('pugin_cate_0')),
				'cate1'=>array('url'=>url('plugin-official_fee-1-1'), 'text'=>lang('pugin_cate_1')),
				'cate2'=>array('url'=>url('plugin-official_fee-2-1'), 'text'=>lang('pugin_cate_2')),
				'cate3'=>array('url'=>url('plugin-official_fee-3-1'), 'text'=>lang('pugin_cate_3')),
				'cate4'=>array('url'=>url('plugin-official_fee-4-1'), 'text'=>lang('pugin_cate_4')),
				'cate99'=>array('url'=>url('plugin-official_fee-99-1'), 'text'=>lang('pugin_cate_99')),
			)),
		)
	)
);

?>