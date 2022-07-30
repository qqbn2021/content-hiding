<?php
/**
 * Plugin Name:隐藏内容
 * Plugin URI:https://www.ggdoc.cn/plugin/4.html
 * Description:支持隐藏文章内容的一部分，用户需要关注微信公众号或百家号才可以查看。
 * Version:0.0.1
 * Requires at least: 5.0
 * Requires PHP:5.3
 * Author:果果开发
 * Author URI:https://www.ggdoc.cn
 * License:GPL v2 or later
 */

// 直接访问报404错误
if (!function_exists('add_action')) {
    http_response_code(404);
    exit;
}
if (defined('CONTENT_HIDING_PLUGIN_DIR')) {
    // 在我的插件那添加重名插件说明
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), array('Content_Hiding_Plugin', 'duplicate_name'));
    return;
}
// 插件目录后面有 /
const CONTENT_HIDING_PLUGIN_FILE = __FILE__;
define('CONTENT_HIDING_PLUGIN_DIR', plugin_dir_path(CONTENT_HIDING_PLUGIN_FILE));
// 定义配置
$content_hiding_options = get_option('content_hiding_options', array());
/**
 * 自动加载
 * @param string $class
 * @return void
 */
function content_hiding_autoload($class)
{
    $class_file = CONTENT_HIDING_PLUGIN_DIR . 'includes/class-' . strtolower(str_replace('_', '-', $class)) . '.php';
    if (file_exists($class_file)) {
        require_once $class_file;
    }
}

spl_autoload_register('content_hiding_autoload');
// 启用插件
register_activation_hook(CONTENT_HIDING_PLUGIN_FILE, array('Content_Hiding_Plugin', 'plugin_activation'));
// 删除插件
register_uninstall_hook(CONTENT_HIDING_PLUGIN_FILE, array('Content_Hiding_Plugin', 'plugin_uninstall'));
// 添加页面
add_action('admin_init', array('Content_Hiding_Plugin', 'admin_init'));
// 添加菜单
add_action('admin_menu', array('Content_Hiding_Plugin', 'admin_menu'));
// 在我的插件那添加设置的链接
add_filter('plugin_action_links_' . plugin_basename(CONTENT_HIDING_PLUGIN_FILE), array('Content_Hiding_Plugin', 'links'));
// 在经典编辑器处添加隐藏内容按钮
if (class_exists('Classic_Editor')) {
    add_action('admin_head', array('Content_Hiding_Plugin', 'admin_head'));
}
// 解析简码
add_shortcode('hide', array('Content_Hiding_Plugin', 'hide'));
// 添加样式文件
add_action('init', array('Content_Hiding_Plugin', 'init'));
// 添加js文件
add_action('wp_enqueue_scripts', array('Content_Hiding_Plugin', 'wp_enqueue_scripts'));
// ajax处理
add_action('wp_ajax_check_password', array('Content_Hiding_Plugin', 'check_password'));
add_action('wp_ajax_nopriv_check_password', array('Content_Hiding_Plugin', 'check_password'));