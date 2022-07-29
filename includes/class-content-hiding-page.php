<?php

class Content_Hiding_Page
{
    // 初始化页面
    public static function init_page()
    {
        global $content_hiding_options;
        // 注册一个新页面
        register_setting('content_hiding_page', 'content_hiding_options', array('Content_Hiding_Page', 'handle_file_upload'));

        add_settings_section(
            'content_hiding_page_section',
            null,
            null,
            'content_hiding_page'
        );

        add_settings_field(
            'account',
            '自媒体平台',
            array('Content_Hiding_Plugin', 'field_callback'),
            'content_hiding_page',
            'content_hiding_page_section',
            array(
                'label_for' => 'account',
                'form_type' => 'input',
                'type' => 'text',
                'form_desc' => '填写需要关注的自媒体平台。例如：微信公众号、百家号'
            )
        );

        add_settings_field(
            'wechat_qrcode',
            '二维码图片',
            array('Content_Hiding_Plugin', 'field_callback'),
            'content_hiding_page',
            'content_hiding_page_section',
            array(
                'label_for' => 'wechat_qrcode',
                'form_type' => 'input',
                'type' => 'file',
                'form_extend' => array(
                    'accept' => 'image/png,image/jpeg',
                ),
                'form_desc' => '上传自媒体账号的二维码图片'
            )
        );

        if (!empty($content_hiding_options['wechat_qrcode'])) {
            add_settings_field(
                'wechat_qrcode_image_url',
                null,
                function () use ($content_hiding_options) {
                    if (!empty($content_hiding_options['wechat_qrcode'])) {
                        ?>
                        <img src="<?php echo esc_url($content_hiding_options['wechat_qrcode']); ?>" width="150"
                             height="150">
                        <?php
                    }
                },
                'content_hiding_page',
                'content_hiding_page_section'
            );
        }

        add_settings_field(
            'wechat_name',
            '名称',
            array('Content_Hiding_Plugin', 'field_callback'),
            'content_hiding_page',
            'content_hiding_page_section',
            array(
                'label_for' => 'wechat_name',
                'form_type' => 'input',
                'type' => 'text',
                'form_desc' => '自媒体账号名称'
            )
        );

        add_settings_field(
            'wechat_text',
            '关键词回复',
            array('Content_Hiding_Plugin', 'field_callback'),
            'content_hiding_page',
            'content_hiding_page_section',
            array(
                'label_for' => 'wechat_text',
                'form_type' => 'input',
                'type' => 'text',
                'form_desc' => '回复这个关键词就可以获取到验证码'
            )
        );

        add_settings_field(
            'wechat_code',
            '验证码',
            array('Content_Hiding_Plugin', 'field_callback'),
            'content_hiding_page',
            'content_hiding_page_section',
            array(
                'label_for' => 'wechat_code',
                'form_type' => 'input',
                'type' => 'text',
                'form_desc' => '您设置的验证码要和自媒体平台关键词回复的验证码内容一致'
            )
        );

        add_settings_field(
            'wechat_code_time',
            '验证码有效期',
            array('Content_Hiding_Plugin', 'field_callback'),
            'content_hiding_page',
            'content_hiding_page_section',
            array(
                'label_for' => 'wechat_code_time',
                'form_type' => 'input',
                'type' => 'number',
                'form_desc' => '单位：小时。在有效期内，用户可直接查看隐藏内容'
            )
        );
    }

    /**
     * 处理文件上传
     * @param array $option
     * @return array
     */
    public static function handle_file_upload($option)
    {
        global $content_hiding_options;
        if (is_uploaded_file($_FILES['content_hiding_options']['tmp_name']['wechat_qrcode'])) {
            if (empty($_FILES['content_hiding_options']['type']['wechat_qrcode']) || !in_array($_FILES['content_hiding_options']['type']['wechat_qrcode'], array(
                    'image/jpeg',
                    'image/png'
                ))) {
                wp_die('仅支持jpg、png格式的图片');
            }
            $ext = '.jpg';
            if ($_FILES['content_hiding_options']['type']['wechat_qrcode'] === 'image/png') {
                $ext = '.png';
            }
            $basename = 'content-hiding-qrcode-image' . $ext;
            $file = wp_upload_bits($basename, '', file_get_contents($_FILES['content_hiding_options']['tmp_name']['wechat_qrcode']));
            if (!empty($file['url'])) {
                $option['wechat_qrcode'] = $file['url'];
            } else {
                wp_die($file['error']);
            }
        } else {
            if (!empty($content_hiding_options['wechat_qrcode'])) {
                $option['wechat_qrcode'] = $content_hiding_options['wechat_qrcode'];
            }
        }
        return $option;
    }
}