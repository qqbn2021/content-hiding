<?php

class Content_Hiding_Plugin
{
    // 启用插件
    public static function plugin_activation()
    {
        // 创建默认配置
        add_option('content_hiding_options', array(
            'account' => '微信公众号',
            'wechat_text' => '验证码',
            'wechat_code_time' => 168,
            'wechat_code' => mt_rand(10000, 99999),
            'salt' => mt_rand(100000, 999999)
        ));
    }

    // 删除插件执行的代码
    public static function plugin_uninstall()
    {
        // 删除配置
        delete_option('content_hiding_options');
    }

    /**
     * 在插件页面添加同名插件处理问题
     *
     * @param $links
     *
     * @return mixed
     */
    public static function duplicate_name($links)
    {
        $settings_link = '<a href="https://www.ggdoc.cn/plugin/4.html" target="_blank">请删除其它版本《隐藏内容》插件</a>';
        array_unshift($links, $settings_link);

        return $links;
    }

    // 初始化
    public static function admin_init()
    {
        // 注册设置页面
        Content_Hiding_Page::init_page();
    }

    // 添加菜单
    public static function admin_menu()
    {
        add_options_page(
            '隐藏内容',
            '隐藏内容',
            'manage_options',
            'content-hiding-setting',
            array('Content_Hiding_Plugin', 'show_page')
        );
    }

    // 显示设置页面
    public static function show_page()
    {
        // 检查用户权限
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post" enctype="multipart/form-data">
                <?php
                $page = 'content_hiding_page';
                // 输出表单
                settings_fields($page);
                do_settings_sections($page);
                // 输出保存设置按钮
                submit_button('保存更改');
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * 表单输入框回调
     *
     * @param array $args 这数据就是add_settings_field方法中第6个参数（$args）的数据
     */
    public static function field_callback($args)
    {
        // 表单的id或name字段
        $id = $args['label_for'];
        // 表单的名称
        $input_name = 'content_hiding_options[' . $id . ']';
        // 获取表单选项中的值
        global $content_hiding_options;
        // 表单的值
        $input_value = isset($content_hiding_options[$id]) ? $content_hiding_options[$id] : '';
        // 表单的类型
        $form_type = isset($args['form_type']) ? $args['form_type'] : 'input';
        // 输入表单说明
        $form_desc = isset($args['form_desc']) ? $args['form_desc'] : '';
        // 输入表单type
        $type = isset($args['type']) ? $args['type'] : 'text';
        // 输入表单placeholder
        $form_placeholder = isset($args['form_placeholder']) ? $args['form_placeholder'] : '';
        // 下拉框等选项值
        $form_data = isset($args['form_data']) ? $args['form_data'] : array();
        // 扩展form表单属性
        $form_extend = isset($args['form_extend']) ? $args['form_extend'] : array();
        switch ($form_type) {
            case 'input':
                self::generate_input(
                    array_merge(
                        array(
                            'id' => $id,
                            'type' => $type,
                            'placeholder' => $form_placeholder,
                            'name' => $input_name,
                            'value' => $input_value,
                            'class' => 'regular-text',
                        ),
                        $form_extend
                    ));
                break;
            case 'select':
                self::generate_select(
                    array_merge(
                        array(
                            'id' => $id,
                            'placeholder' => $form_placeholder,
                            'name' => $input_name
                        ),
                        $form_extend
                    ),
                    $form_data,
                    $input_value
                );
                break;
            case 'checkbox':
                self::generate_checkbox(
                    array_merge(
                        array(
                            'name' => $input_name . '[]'
                        ),
                        $form_extend
                    ),
                    $form_data,
                    $input_value
                );
                break;
            case 'textarea':
                self::generate_textarea(
                    array_merge(
                        array(
                            'id' => $id,
                            'placeholder' => $form_placeholder,
                            'name' => $input_name,
                            'class' => 'large-text code',
                            'rows' => 5,
                        ),
                        $form_extend
                    ),
                    $input_value
                );
                break;
        }
        if (!empty($form_desc)) {
            ?>
            <p class="description"><?php echo esc_html($form_desc); ?></p>
            <?php
        }
    }

    /**
     * 生成textarea表单
     * @param array $form_data 标签上的属性数组
     * @param string $value 默认值
     * @return void
     */
    public static function generate_textarea($form_data, $value = '')
    {
        ?><textarea <?php
        foreach ($form_data as $k => $v) {
            echo esc_attr($k); ?>="<?php echo esc_attr($v); ?>" <?php
        } ?>><?php echo esc_textarea($value); ?></textarea>
        <?php
    }

    /**
     * 生成checkbox表单
     * @param array $form_data 标签上的属性数组
     * @param array $checkboxs 下拉列表数据
     * @param string|array $value 选中值，单个选中字符串，多个选中数组
     * @return void
     */
    public static function generate_checkbox($form_data, $checkboxs, $value = '')
    {
        ?>
        <fieldset><p>
                <?php
                $len = count($checkboxs);
                foreach ($checkboxs as $k => $checkbox) {
                    $checked = '';
                    if (!empty($value)) {
                        if (is_array($value)) {
                            if (in_array($checkbox['value'], $value)) {
                                $checked = 'checked';
                            }
                        } else {
                            if ($checkbox['value'] == $value) {
                                $checked = 'checked';
                            }
                        }
                    }
                    ?>
                    <label>
                        <input type="checkbox" <?php checked($checked, 'checked'); ?><?php
                        foreach ($form_data as $k2 => $v2) {
                            echo esc_attr($k2); ?>="<?php echo esc_attr($v2); ?>" <?php
                        } ?> value="<?php echo esc_attr($checkbox['value']); ?>"
                        ><?php echo esc_html($checkbox['title']); ?>
                    </label>
                    <?php
                    if ($k < ($len - 1)) {
                        ?>
                        <br>
                        <?php
                    }
                }
                ?>
            </p></fieldset>
        <?php
    }

    /**
     * 生成input表单
     * @param array $form_data 标签上的属性数组
     * @return void
     */
    public static function generate_input($form_data)
    {
        ?><input <?php
        foreach ($form_data as $k => $v) {
            echo esc_attr($k); ?>="<?php echo esc_attr($v); ?>" <?php
        } ?>><?php
    }

    /**
     * 生成select表单
     * @param array $form_data 标签上的属性数组
     * @param array $selects 下拉列表数据
     * @param string|array $value 选中值，单个选中字符串，多个选中数组
     * @return void
     */
    public static function generate_select($form_data, $selects, $value = '')
    {
        ?><select <?php
        foreach ($form_data as $k => $v) {
            echo esc_attr($k); ?>="<?php echo esc_attr($v); ?>" <?php
        } ?>><?php
        foreach ($selects as $select) {
            $selected = '';
            if (!empty($value)) {
                if (is_array($value)) {
                    if (in_array($select['value'], $value)) {
                        $selected = 'selected';
                    }
                } else {
                    if ($select['value'] == $value) {
                        $selected = 'selected';
                    }
                }
            }
            ?>
            <option <?php selected($selected, 'selected'); ?>
                    value="<?php echo esc_attr($select['value']); ?>"><?php echo esc_html($select['title']); ?></option>
            <?php
        }
        ?>
        </select>
        <?php
    }

    /**
     * 添加设置链接
     * @param array $links
     * @return array
     */
    public static function links($links)
    {
        $business_link = '<a href="https://www.ggdoc.cn/plugin/4.html" target="_blank">商业版</a>';
        array_unshift($links, $business_link);

        $settings_link = '<a href="options-general.php?page=content-hiding-setting">设置</a>';
        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * @return void
     */
    public static function admin_head()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', array('Content_Hiding_Plugin', 'hide_add_tinymce_plugin'));
            add_filter('mce_buttons', array('Content_Hiding_Plugin', 'hide_register_mce_button'));
        }
    }

    /**
     * 创建自定义插件
     * @param $plugin_array
     * @return mixed
     */
    public static function hide_add_tinymce_plugin($plugin_array)
    {
        $plugin_array['hide_button'] = plugins_url('/' . basename(CONTENT_HIDING_PLUGIN_DIR) . '/js/hide-button.js');
        return $plugin_array;
    }

    /**
     * 添加按钮
     * @param $buttons
     * @return mixed
     */
    public static function hide_register_mce_button($buttons)
    {
        $buttons[] = 'hide_button';
        return $buttons;
    }

    /**
     * 引入样式文件
     * @return void
     */
    public static function init()
    {
        wp_register_style('content-hiding-css', plugin_dir_url(CONTENT_HIDING_PLUGIN_FILE) . 'css/content-hiding.min.css', false, '0.0.2');
        wp_enqueue_style('content-hiding-css');
    }

    /**
     * 添加js
     * @return void
     */
    public static function wp_enqueue_scripts()
    {
        global $content_hiding_options;
        wp_enqueue_script('content-hiding-js', plugins_url('/js/content-hiding.min.js', CONTENT_HIDING_PLUGIN_FILE), array(), '0.0.2', true);
        wp_localize_script(
            'content-hiding-js',
            'content_hiding_js_obj',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'wechat_text' => !empty($content_hiding_options['wechat_text']) ? $content_hiding_options['wechat_text'] : ''
            )
        );
    }

    /**
     * 验证输入的密码
     * @return void
     */
    public static function check_password()
    {
        global $content_hiding_options;
        $password = !empty($_POST['password']) ? $_POST['password'] : '';
        $wechat_text = !empty($content_hiding_options['wechat_text']) ? $content_hiding_options['wechat_text'] : '';
        if (empty($password) || !is_string($password)) {
            wp_send_json(array(
                'status' => 2,
                'msg' => $wechat_text . '输入错误'
            ));
        }
        $password = trim($password);
        if (!empty($content_hiding_options['wechat_code']) && trim($content_hiding_options['wechat_code']) === $password) {
            // 保存cookie
            if (!empty($content_hiding_options['wechat_code_time'])) {
                $salt = '';
                if (!empty($content_hiding_options['salt'])) {
                    $salt = $content_hiding_options['salt'];
                }
                setcookie('content_hiding_pwd', md5($content_hiding_options['wechat_code'] . $salt), time() + ($content_hiding_options['wechat_code_time'] * 3600), '/');
            }
            wp_send_json(array(
                'status' => 1,
                'msg' => '验证成功'
            ));
        }
        wp_send_json(array(
            'status' => 2,
            'msg' => $wechat_text . '输入不正确'
        ));
    }

    /**
     * 解析简码
     * @param string $atts
     * @param string $content 隐藏的内容
     * @return string
     */
    public static function hide($atts, $content = '')
    {
        $content_key = md5($content);
        set_transient('content_hiding_' . $content_key, $content);
        return '<script src="' . esc_url(admin_url('admin-ajax.php')) . '?action=show_content&content_key=' . $content_key . '"></script>';
    }

    /**
     * 显示简码解析后的内容
     * @return void
     */
    public static function show_content()
    {
        global $content_hiding_options;
        $verify = false;
        // 微信关注
        if (!empty($_COOKIE['content_hiding_pwd']) && !empty($content_hiding_options['wechat_code'])) {
            $salt = '';
            if (!empty($content_hiding_options['salt'])) {
                $salt = $content_hiding_options['salt'];
            }
            if ($_COOKIE['content_hiding_pwd'] === md5($content_hiding_options['wechat_code'] . $salt)) {
                $verify = true;
            }
        }
        if ($verify) {
            $content = '';
            if (!empty($_GET['content_key'])) {
                $content_key = sanitize_key($_GET['content_key']);
                $content = get_transient('content_hiding_' . $content_key);
            }
            $html = '<p>' . $content . '</p>';
        } else {
            // 开始拼接返回代码
            $html = '<div class="content-hiding-box">';
            $wechat_qrcode = '';
            if (!empty($content_hiding_options['wechat_qrcode'])) {
                $wechat_qrcode = $content_hiding_options['wechat_qrcode'];
            }
            $account = '';
            if (!empty($content_hiding_options['account'])) {
                $account = $content_hiding_options['account'];
            }
            $wechat_name = '';
            if (!empty($content_hiding_options['wechat_name'])) {
                $wechat_name = $content_hiding_options['wechat_name'];
            }
            $wechat_text = '';
            if (!empty($content_hiding_options['wechat_text'])) {
                $wechat_text = $content_hiding_options['wechat_text'];
            }
            $html .= '<div class="content-hiding-wechat-box">';
            $html .= '<div class="content-hiding-wechat-qrcode">';
            $html .= sprintf('<img src="%s" alt="%s" />', esc_url($wechat_qrcode), esc_attr($wechat_text));
            $html .= '</div>';
            $html .= '<div class="content-hiding-wechat-box-content">';
            $html .= '<div class="content-hiding-wechat-box-content-item content-hiding-wechat-box-content-item-title">';
            $html .= sprintf('内容已被隐藏，请输入%s查看', esc_attr($wechat_text));
            $html .= '</div>';
            $html .= '<div class="content-hiding-wechat-box-content-item">';
            $html .= sprintf('<input class="content-hiding-password" type="text" id="content-hiding-password" placeholder="请输入%s" required="required">', esc_attr($wechat_text));
            $html .= '<input class="content-hiding-submit" type="button" id="content-hiding-submit" value="验证">';
            $html .= '<label class="content-hiding-error" id="content-hiding-error">验证失败</label>';
            $html .= '</div>';
            $html .= '<div class="content-hiding-wechat-box-content-item">';
            $html .= sprintf('关注%s（<span>%s</span>），发送<span>%s</span>获取', esc_attr($account), esc_attr($wechat_name), esc_attr($wechat_text));
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        @header('Content-Type: application/javascript');
        $html = str_replace('`', '\`', $html);
        echo 'document.write(`' . $html . '`);';
        exit();
    }
}