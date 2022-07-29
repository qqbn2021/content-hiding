(function () {
    tinymce.PluginManager.add('hide_button', function (editor, url) {
        editor.addButton('hide_button', {
            text: '隐藏内容',
            icon: false,
            onclick: function () {
                let selected = tinyMCE.activeEditor.selection.getContent();
                let content = '';
                if (selected) {
                    content = '[hide]' + selected + '[/hide]';
                } else {
                    content = '[hide][/hide]';
                }
                editor.insertContent(content);
            }
        });
    });
})();