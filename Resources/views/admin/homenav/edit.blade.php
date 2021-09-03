@extends('admin.public.header')
@section('title',$title)
@section('listcontent')
    <div id="showRoute" style="display: none">
        <div class="layui-form layuimini-form">

            <div class="appletInfo"></div>
            <div class="appletParams"></div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" id="appletBtn" lay-submit lay-filter="appletBtn">确认</button>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-form layuimini-form">
        @if(isset($info->id))
        <input type="hidden" name="id" value="{{$info->id}}" />
        @endif

        <div class="layui-form-item">
            <label class="layui-form-label required">名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" lay-reqtext="名称不能为空" placeholder="请输入名称" value="{{$info->name ?? ''}}" class="layui-input" />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">排序</label>
            <div class="layui-input-block">
                <input type="number" name="sort" lay-verify="required" lay-reqtext="排序不能为空" placeholder="请输入排序" value="{{$info->sort ?? 100}}" class="layui-input" />
                <div style="font-size: 10px; color: red;">排序值只能为大于等于0 ~ 小于等于100。</div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">图标</label>
            <div class="layui-input-block">
                <div class="layui-upload-drag" id="upload1">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                    <br>
                    <div class="{{$info->show_pic ? '' : 'layui-hide'}}" id="uploadShowImg">
                        <img src="{{$info->show_pic ?? ''}}" alt="上传成功后渲染" style="max-width: 196px">
                    </div>
                    <input type="hidden" name="pic" lay-verify="required" lay-reqtext="图标不能为空" value="{{$info->pic ?? ''}}" />
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">链接</label>
            <div class="layui-input-block">
                <input type="text" name="route" value="{{$info->route ?? ''}}" class="layui-input layui-disabled" style="display: inline; width: 70%; position: absolute; background: #eceeef" disabled/>
                <a id="showRouteA" href="javascript:void(0)" style="position: absolute; right: 30%; display: block; line-height: 36px; color: #464a4c; padding: 0 13px; border: 1px solid #e6e6e6; background-color: #fff; border-radius: 2px;">选择链接</a>
                <input type="hidden" name="open_type" value="{{$info->open_type ?? ''}}" />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                @foreach($info->getIsShowArr() as $k=>$v)
                    @if(isset($info->is_show))
                    <input type="radio" name="is_show" value="{{$k}}" title="{{$v}}" @if($k == $info->is_show) checked="" @endif />
                    @else
                    <input type="radio" name="is_show" value="{{$k}}" title="{{$v}}" @if($k == 1) checked="" @endif />
                    @endif
                @endforeach
            </div>
        </div>

        <div class="hr-line"></div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" id="saveBtn" lay-submit lay-filter="saveBtn">保存</button>
            </div>
        </div>

    </div>
@endsection

@section('listscript')
    <script type="text/javascript">
        layui.use(['iconPickerFa', 'form', 'layer', 'upload'], function () {
            var iconPickerFa = layui.iconPickerFa,
                form = layui.form,
                layer = layui.layer,
                upload = layui.upload,
                $ = layui.$;
            var domain = '<?php echo $domain;?>';
            var appletObj = eval('<?php echo json_encode($applet);?>');

            var indexOpen;
            // 显示弹框
            $(document).on("click", "#showRouteA", function () {
                let appletSelectDiv = '<div class="layui-form-item">';
                appletSelectDiv += '<label class="layui-form-label">可选链接</label>';
                appletSelectDiv += '<div class="layui-input-block">';
                appletSelectDiv += '<select lay-filter="appletSelect">';
                appletSelectDiv += '<option value="0">请选择链接</option>';
                for(let k in appletObj){
                    appletSelectDiv += '<option value="'+appletObj[k].id+'">'+appletObj[k].name+'</option>';
                }
                appletSelectDiv += '</select>';
                appletSelectDiv += '</div>';
                appletSelectDiv += '</div>';
                $("#showRoute .layui-form .appletInfo").html(appletSelectDiv);
                $("#showRoute .layui-form .appletParams").html("");
                form.render();

                 indexOpen = layer.open({
                    title: '选择链接',
                    type: 1,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['90%', '80%'],
                    content: $("#showRoute"),
                });
            })

            // 动态获取下拉框
            form.on("select(appletSelect)", function (data) {
                let params;
                let id = data.value;
                $("#showRoute .layui-form .appletParams").html("");
                form.render();
                for (let i in appletObj){
                    if(id == appletObj[i].id){
                        let info = "";
                        info += '<div class="layui-form-item">';
                        info += '<label class="layui-form-label">打开方式</label>';
                        info += '<div class="layui-input-block">';
                        info += '<input type="text" name="open_type" value="'+appletObj[i].open_type+'" class="layui-input layui-disabled" disabled />';
                        info += '</div>';
                        info += '</div>';

                        info += '<div class="layui-form-item">';
                        info += '<label class="layui-form-label">跳转地址</label>';
                        info += '<div class="layui-input-block">';
                        info += '<input type="text" name="route" value="'+appletObj[i].route+'" class="layui-input layui-disabled" disabled />';
                        info += '</div>';
                        info += '</div>';

                        for(let k in appletObj[i].params){
                            info += '<div class="layui-form-item">';
                            info += '<label class="layui-form-label">'+appletObj[i].params[k].name+'</label>';
                            info += '<div class="layui-input-block">';
                            if(appletObj[i].params[k].is_value == 1){
                                info += '<input type="text" name="'+appletObj[i].params[k].name+'" value="'+appletObj[i].params[k].value+'" class="layui-input" />';
                            }else{
                                info += '<input type="text" name="'+appletObj[i].params[k].name+'" value="'+appletObj[i].params[k].value+'" class="layui-input layui-disabled" disabled />';
                            }
                            if(appletObj[i].params[k].desc.length > 0) {
                                info += '<div style="font-size: 10px; color: #636c72;">' + appletObj[i].params[k].desc + '</div>';
                            }
                            info += '</div>';
                            info += '</div>';
                        }

                        $("#showRoute .layui-form .appletParams").html(info);
                        form.render();
                        break;
                    }
                }
            })

            //动态监听 选择链接 提交
            form.on('submit(appletBtn)', function(data){
                $("#appletBtn").addClass("layui-btn-disabled");
                $("#appletBtn").attr('disabled', 'disabled');
                let field = data.field;
                let open_type = "";
                if(field.hasOwnProperty("open_type")) open_type = field.open_type;
                let route = "";
                if(field.hasOwnProperty("route")) route = field.route;
                if(open_type == "navigate" || open_type == 'wxapp' || open_type == 'tel'){
                    route = "/" + route;
                    $.each(field, function (i, v){
                        if(i != "open_type" && i != "route"){
                            if(route.indexOf("?")!=-1){
                                route += "&" + i + "=" + v;
                            }else{
                                route += "?" + i + "=" + v;
                            }
                        }
                    })
                }
                $("input[name='route']").val(route);
                $("input[name='open_type']").val(open_type);

                $("#appletBtn").removeClass("layui-btn-disabled");
                $("#appletBtn").removeAttr('disabled');
                layer.close(indexOpen);
            });

            //拖拽上传
            upload.render({
                elem: '#upload1'
                ,url: '/admin/upload/upload' //改成您自己的上传接口
                ,accept: 'images'
                ,acceptMime: 'image/*'
                ,size: 400 //限制文件大小，单位 KB
                ,headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                ,done: function(res){
                    if(res.code==0){
                        layer.msg("上传成功",{icon: 1});
                        layui.$('#uploadShowImg').removeClass('layui-hide').find('img').attr('src', domain + "/" + res.data[0]);
                        $("input[name='pic']").val(res.data[0]);
                    }else{
                        layer.msg(res.message,{icon: 2});
                        layui.$('#uploadShowImg').addClass('layui-hide');
                        $("input[name='pic']").val('');
                    }
                }
            });

            //监听提交
            form.on('submit(saveBtn)', function(data){
                $("#saveBtn").addClass("layui-btn-disabled");
                $("#saveBtn").attr('disabled', 'disabled');
                $.ajax({
                    url:'/admin/homenav/edit',
                    type:'post',
                    data:data.field,
                    dataType:'JSON',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        if(res.code==0){
                            layer.msg(res.message,{icon: 1},function (){
                                parent.location.reload();
                            });
                        }else{
                            layer.msg(res.message,{icon: 2});
                            $("#saveBtn").removeClass("layui-btn-disabled");
                            $("#saveBtn").removeAttr('disabled');
                        }
                    },
                    error:function (data) {
                        layer.msg(res.message,{icon: 2});
                        $("#saveBtn").removeClass("layui-btn-disabled");
                        $("#saveBtn").removeAttr('disabled');
                    }
                });
            });
        });
    </script>
@endsection