(function() {
    //定义全局变量
    window.netdisk = {
        /*=== 属性部分 ===*/
        //数据存储
        "data" : {
            "currentSet" : {
                "path" : "",
                "data" : []
            },
            "storedSets" : {},
            "objectsToBeDeleted" : []
        },
        //配置存储
        "options" : {},

        /*=== 方法部分 ===*/
        //后台动作
        "action" : {
            //对应服务器上的各种api
            "download" : function(filename) {
                var file = window.netdisk.data.currentSet.path + '/' + filename;
                $("#download").attr("src", window.netdisk.options.download + "?file=" + file);
            },
            "listFiles" : function() {
                $.ajax({
                    "url" : window.netdisk.options.api,
                    "type" : "post",
                    "data" : "requests=" + "{\"currentPath\" : \"" + window.netdisk.data.currentSet.path 
                        + "\", \"operation\" : \"list\" }",
                    "success" : function(data) {
                        window.netdisk.data.currentSet.data = data;
                        if(data.dirNum !== 0 || data.fileNum !== 0) {
                            $("#emptyList").hide();
                            window.netdisk.view.showCurrentSetInDetail();
                            $("#list").show();
                        }
                        else {
                            $("#list").hide();
                            $("#emptyList").show();
                        }
                        $("#loading").hide();
                    }
                });
            }
        },
        "view" : {
            "clearTools" : function() {
                $(".tools.inline").removeClass("inline").addClass("hidden");
                $("#dynamicTools").removeClass("inline").addClass("hidden");
            },
            "setTools" : function() {
                var checkBoxes = $("#entries input:checked");
                switch(checkBoxes.length) {
                    case 0:
                        window.netdisk.view.clearTools();
                        break;
                    case 1:
                        if($("tr.over").hasClass("dir")) {
                            $("#singleDirTools").removeClass("hidden").addClass("inline");
                            $("#dynamicTools").removeClass("hidden").addClass("inline");
                        }
                        else {
                            $("#singleFileTools").removeClass("hidden").addClass("inline");
                            $("#dynamicTools").removeClass("hidden").addClass("inline");
                        }
                        $("#multipleSelectionTools").removeClass("inline").addClass("hidden");
                        break;
                    default:
                        $(".tools.inline").removeClass("inline").addClass("hidden");
                        $("#multipleSelectionTools").removeClass("hidden").addClass("inline");
                        $("#dynamicTools").removeClass("hidden").addClass("inline");
                }
            },
            "setCurrentSet" : function() {
                window.netdisk.data.currentSet.path = window.location.hash.substring(1);
                window.netdisk.data.currentSet.pathSegments = window.netdisk.data.currentSet.path.split('/');
                $("#currentSet-name").html(_.last(window.netdisk.data.currentSet.pathSegments));
                $("#currentSet-path").html(window.netdisk.data.currentSet.pathSegments.join(' > '));

                window.netdisk.action.listFiles();
                window.netdisk.view.clearTools();
            },
            "showCurrentSetInDetail" : function() {
                $("#list").html(_.template('<table id="details">' 
                    + '<thead class="titles"><td><input type="checkbox" id="selectedAll" class="check" /></td>' 
                    + '<td>Name</td><td>Modified&nbsp;Time</td><td>Size</td></thead><tbody id="entries">'
                    + '<% _.each(dirs, function(entry) { %>'
                    + '<tr class="dir row"><td class="check"><input class="checkbox" name="check_<%= entry.name %>" type="checkbox" /></td>' 
                    + '<td class="dir name">' 
                    + '<a href="#" class="nameLink"><%= entry.name %></a></td>'
                    + '<td class="modifiedTime"></td>' 
                    + '<td class="size"></tr>'
                    + '<% }); %>' 
                    + '<% _.each(files, function(entry) { %>'
                    + '<tr class="file row"><td class="check"><input class="checkbox" name="check_<%= entry.Name %>" type="checkbox" /></td>' 
                    + '<td class="file name">' 
                    + '<a href="#" class="nameLink"><%= entry.Name %></a></td>'
                    + '<td class="modifiedTime"><%= entry.uploadtime %></td>' 
                    + '<td class="size"><%= entry.length %></td></tr>'
                    + '<% }); %>' 
                    + '</tbody></table>' 
                    , window.netdisk.data.currentSet.data));
                //清除文件夹标志
                $("input[name=check_dir]").parent().parent().remove();
                //注册事件
                $("#details #selectedAll").click(function() {
                    $("#details #entries input:checkbox").each(function() {
                        $(this).attr("checked", $("#details #selectedAll").get(0).checked);
                    });
                });
                $("#details #entries input:checkbox").click(function(){
                    if($(this).attr("checked")) {
                        $(this).parent().parent().addClass("over");
                    }
                    else {
                        $($(this).parent().get(0)).parent().removeClass("over");
                    }
                    window.netdisk.view.setTools();
                });
                $(".nameLink").click(function() {
                    if($(this).parent().hasClass("dir")) {
                        window.location.hash = window.location.hash + "/" +$(this).html();
                    }
                    else{
                        window.netdisk.action.download($(this).html());
                    }
                    return false;
                });
                $("#entries tr.dir").contextMenu("#dirContextMenu");
                $("#entries tr.file").contextMenu("#fileContextMenu");
            }
        },

        //主方法
        "Run" : function(options) {
            this.options = options;

            /*=== 开始配置，开启loading窗口 ===*/
            $("#loading").modal("show");

            /*=== 初始化当前集合 ===*/
            window.netdisk.view.setCurrentSet();

            /*
             * 路由函数，使用jQuery一个鲜为人知的事件
             * 来自http://stackoverflow.com/questions/680785/on-window-location-hash-change
             */
            $(window).on("hashchange", window.netdisk.view.setCurrentSet);

            /*=== 工具栏部分按钮事件的绑定 ===*/
            //绑定createFolder按钮事件
            $("#createFolder").click(function() {
                $("#createFolderBox").modal("show");
            });
            //绑定upload按钮事件
            $("#upload").click(function() {
                $("#uploadForm input[name=path]").val(window.netdisk.data.currentSet.path);
                $("#uploadBox").modal("show");
            });
            //绑定deleteSelected按钮事件
            $("#deleteSelected").click(function() {
                $("tr.over .nameLink").each(function() {
                    window.netdisk.data.objectsToBeDeleted.push($(this).html());
                });
                $("#deleteForm .info").html(_.template('Sure to delete files/directories below?' 
                    + '<ul><% _.each(objectsToBeDeleted, function(object) { %>'
                    + '<li><%= object %></li><% }); %></ul>', window.netdisk.data));
                $("#deleteBox").modal("show");
            });
            //绑定clearSelection按钮事件
            $("#clearSelection").click(function() {
                $("#entries input:checked").removeAttr("checked");
                $("tr.over").removeClass("over");
                window.netdisk.view.setTools();
            });

            /*=== 对话框部分按钮事件的绑定 ===*/
            //绑定遮罩层的事件
            $("#mask").click(function() {
                $(this).hide();
                $(".modal").hide();
            });
            //绑定各种对话框中cancel按钮的事件
            $("button.close, button.cancel").click(function(event) {
                event.preventDefault();
                $(".modal").hide();
                $("#mask").hide();
            });

            //绑定uploadForm-submit按钮事件
            $("#uploadForm-submit").click(function(){
                $("#uploadProgressBar").html("Uploading...").show();
            });

            //绑定createFolderForm-submit按钮事件
            $("#createFolderForm-submit").click(function(event) {
                var options = {
                    "data" : 'requests={"currentPath" : "' + window.netdisk.data.currentSet.path  
                        + '", "objectName" : "' + $("#objectName").val() 
                        + '", "operation" : "create" }',
                    "finish" : window.netdisk.view.setCurrentSet
                };
                $("#createFolderForm").ajaxSubmit(options);
                event.preventDefault();
            });

            
            //绑定deleteForm-submit按钮事件
            $("#deleteForm-submit").click(function(event) {
                var str = "requests=[";
                for(i = 0; i < window.netdisk.data.objectsToBeDeleted.length; i++) {
                    if(i !== 0) {
                        str += ", "
                    }
                    str += "{\"currentPath\" : \"" + window.netdisk.data.currentSet.path + "\", "
                        + "\"objectName\" : \"" + window.netdisk.data.objectsToBeDeleted[i] + "\", "
                        + "\"operation\" : \"delete\"}"
                }
                str += "]";
                var options = {
                    "data" : str,
                    "finish" : window.netdisk.view.setCurrentSet
                };
                $("#deleteForm").ajaxSubmit(options);
                window.netdisk.data.objectsToBeDeleted = [];
                event.preventDefault();
            });

            /*=== 复选框事件的绑定 ===*/
            

            /*=== 工具栏与右键菜单部分按钮事件的绑定 ===*/
            //绑定open按钮的事件
            $(".open").click(function() {
                window.location.hash = window.location.hash + "/" + $("tr.over .nameLink").html();
            });
            
            //绑定delete按钮的事件
            $(".delete").click(function() {
                window.netdisk.data.objectsToBeDeleted.push($("tr.over .nameLink").html())
                $("#deleteForm .info").html("Sure to delete \"" 
                    + $("tr.over .nameLink").html() + "\" ?");
                $("#deleteBox").modal("show");
            });
            //绑定download按钮的事件
            $(".download").click(function(event) {
                window.netdisk.action.download($("tr.over .nameLink").html());
                event.preventDefault();
            });

            /*=== 上传幕后iframe事件的绑定 ===*/
            $("#uploadFrame").load(function(){
                var result = $(this).contents().find("body").html();
                if(result === "0"){
                    $("#uploadProgressBar").html("Succeed!");
                }
                else if(result) {
                    alert(result);
                }
                else {
                    alert("Fail to upload the file! Please contact the webmaster.");
                }
                $("#uploadProgressBar").html("").hide();
                $(".modal").hide();
                $("#mask").hide();
                window.netdisk.view.setCurrentSet();
            });

            /*=== 全部操作完成，关闭loading提示 ===*/
            $("#loading").modal("hide");
        }
    };
})();