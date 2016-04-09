Blocks = Class.create();
Blocks.prototype = {

    initialize: function () {
        
    },

    show: function(id)
    {
        id = id || '';
        if (id == '') {
            return false;
        }
        $(id).show();
        return true;
    },

    hide: function(id)
    {
        id = id || '';
        if (id == '') {
            return false;
        }
        $(id).hide();
        return true;
    },

    remove: function(id)
    {
        id = id || '';
        if (id == '') {
            return false;
        }
        $(id).remove();
        return true;
    },

    clear: function(id)
    {
        id = id || '';
        if (id == '') {
            return false;
        }
        $(id).innerHTML = '';
        return true;
    },

    getPreparedId: function(object)
    {
        var id = object.readAttribute('id');
        return id;
    },

    getPreparedTitle: function(object)
    {
        var title = object.readAttribute('title');
        if (typeof title != 'string') {
            title = '';
        }
        object.writeAttribute('title','');
        return title;
    },

    getPreparedSubTitle: function(object)
    {
        var subtitle = object.readAttribute('subtitle');
        object.writeAttribute('subtitle','');
        return subtitle;
    },

    getHeaderHtml: function(id,title,subtitle)
    {
        var titleHtml = '';
        if (title != '') {
            titleHtml = '<span class="title">'+title+'</span>';
        }

        var subtitleHtml = '';
        if (subtitle != '') {
            subtitleHtml = '<span class="subtitle">'+subtitle+'</span>';
        }

        var arrowHtml = '';

        if (titleHtml == '' && subtitleHtml == '' && arrowHtml == '') {
            return '';
        }

        var leftHtml = titleHtml + '&nbsp;&nbsp;' + subtitleHtml + '&nbsp;&nbsp;' + arrowHtml;

        var rightHtml = '';

        return '<div class="blocks_header">' +
                    '<div class="blocks_header_left">' +
                        leftHtml +
                    '</div>' +
                    '<div class="blocks_header_right">' +
                        rightHtml +
                    '</div>' +
                    '<div style="clear: both;"></div>' +
                '</div>';
    },

    getContentHtml: function(id,content)
    {
        contentHtml = '<div class="blocks_content">';

        contentHtml = contentHtml + '<div>' + content + '</div></div>';

        return contentHtml;
    },

    getFinalHtml: function(headerHtml,contentHtml)
    {
        if (headerHtml == '') {
            return contentHtml;
        }

        var search = '<div class="blocks_content" style="';
        var replace = '<div class="blocks_content" style="margin-top: 5px;';

        var tempBefore = contentHtml;
        contentHtml = contentHtml.replace(search,replace);
        var tempAfter = contentHtml;

        if (tempBefore == tempAfter) {
            search = '<div class="blocks_content"';
            replace = '<div class="blocks_content" style="margin-top: 5px;"';
            contentHtml = contentHtml.replace(search,replace);
        }

        return headerHtml + '<div style="clear: both;"></div>' + contentHtml;
    },

    prepare: function(object)
    {
        var id = this.getPreparedId(object);
        var title = this.getPreparedTitle(object);
        var subtitle = this.getPreparedSubTitle(object);

        var headerHtml = this.getHeaderHtml(id,title,subtitle);
        var contentHtml = this.getContentHtml(id,object.innerHTML);
        object.innerHTML = this.getFinalHtml(headerHtml,contentHtml);

        object.removeClassName('wizard_block_hidden');
        object.addClassName('blocks');
    }

};