/**
 * jQuery小插件 by halfcoder
 *
 * 重复发明轮子～包含：
 * modal式对话框
 * 右键菜单
 * ajax提交
 * 
 */
(function() {
    //modal
    $.fn.modal = function(action) {
        if(action === "show") {
            $(this).css({
                "top" : '' + $(document).height() / 2 - $(this).height() / 2 + "px",
                "left" : '' + $(document).width() / 2 - $(this).width() / 2 + "px"
            });
            $("#mask").show();
            $(this).show();
        }
        else {
            $(this).hide();
            $("#mask").hide();

        }
        return this;
    };
    //contextMenu
    $.fn.contextMenu = function(contextMenuSelector) {
        $(this).each(function() {
            $(this).bind('contextmenu',function(event) {
                $(contextMenuSelector).css({
                    "top" : event.clientY + 2,
                    "left" : event.clientX + 2
                });
                
                $(document).click(function() {
                    $(".over").removeClass("over");
                    $(contextMenuSelector).hide();
                });
                
                $(this).addClass("over");
                $(contextMenuSelector).show();
                event.preventDefault();
            });
        });
    };
    //ajaxSubmit
    $.fn.ajaxSubmit = function(options) {
        var object = this;
        $(object).children(".buttons").hide();
        $(object).children(".processing").show();

        $.ajax({
            "url" : window.netdisk.options.api,
            "type" : "post",
            "data" : options.data,
            "success" : function(data) {
                $("#mask").click();
                $(object).children(".buttons").show();
                $(object).children(".processing").hide();
                options.finish();
            },
            "error" : function(jqXHR, textStatus, errorThrown) {
                $(object).children(".error .message").html(jqXHR.responseText);
                $(object).children(".processing").hide();
                $(object).children(".error").show();
            }
        });
    };
})();