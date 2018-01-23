<div class="container-fluid m-t-md">
    <form id="core-admin-form" class="layui-form" action="/wechat/account/save" data-ajax
          data-ajax-done="reload:#core-admin-table" method="post" data-loading style="padding-top: 10px;">
        <div class="layui-form-item">
            <label class="layui-form-label">公众号名称</label>
            <div class="layui-input-block">
                <input type="text" name="wx_name" required value="{$row.wx_name}" lay-verify="required"
                       placeholder="公众号名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">公众号名称(非汉字)</label>
            <div class="layui-input-block">
                <input type="text" name="wx_nick" required value="{$row.wx_nick}" lay-verify="required"
                       placeholder="公众号名称(非汉字)" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">微信appID</label>
            <div class="layui-input-block">
                <input type="text" name="wx_app_id" required value="{$row.wx_app_id}" lay-verify="required"
                       placeholder="微信appID" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">微信令牌</label>
            <div class="layui-input-block">
                <input type="text" name="wx_token" required value="{$row.wx_token}" lay-verify="required"
                       placeholder="微信令牌" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开发密钥</label>
            <div class="layui-input-block">
                <textarea name="wx_app_ecret" placeholder="开发密钥" class="layui-textarea">{$row.wx_app_ecret}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">消息加解密密钥</label>
            <div class="layui-input-block">
                <textarea name="wx_en_key" placeholder="消息加解密密钥" class="layui-textarea">{$row.wx_app_ecret}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="备注" class="layui-textarea">{$row.remark}</textarea>
            </div>
        </div>

        {*<div class="layui-form-item">*}
        {*<label class="layui-form-label">选择框</label>*}
        {*<div class="layui-input-block">*}
        {*<select name="city" lay-verify="required">*}
        {*<option value=""></option>*}
        {*<option value="0">北京</option>*}
        {*<option value="1">上海</option>*}
        {*<option value="2">广州</option>*}
        {*<option value="3">深圳</option>*}
        {*<option value="4">杭州</option>*}
        {*</select>*}
        {*</div>*}
        {*</div>*}
        {*<div class="layui-form-item">*}
        {*<label class="layui-form-label">复选框</label>*}
        {*<div class="layui-input-block">*}
        {*<input type="checkbox" name="like[write]" title="写作">*}
        {*<input type="checkbox" name="like[read]" title="阅读" checked>*}
        {*<input type="checkbox" name="like[dai]" title="发呆">*}
        {*</div>*}
        {*</div>*}
        <div class="layui-form-item">
            <label class="layui-form-label">启用</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="0" title="开启" {if $row.wx_type==0} checked {/if} >
                <input type="radio" name="status" value="1" title="关闭" {if $row.wx_type==1} checked {/if} >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">帐号类型</label>
            <div class="layui-input-block">
                <input type="radio" name="wx_type" value="0" title="订阅号" {if $row.wx_type==0} checked {/if} >
                <input type="radio" name="wx_type" value="1" title="服务号" {if $row.wx_type==1} checked {/if} >
                <input type="radio" name="wx_type" value="3" title="私人订阅号" {if $row.wx_type==3} checked {/if} >
            </div>
        </div>
        <input type="hidden" name="id" value="{$row.id}"/>
    </form>
</div>

{literal}
    <script>
		layui.use('form', function () {
			var form = layui.form;
			form.render();
		});
    </script>
{/literal}
