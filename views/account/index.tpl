<div class="hbox stretch wulaui layui-hide" id="workspace">
    <aside class="aside aside-xs b-r">
        <div class="vbox">
            <header class="bg-light header b-b clearfix">
                <p>第三方平台</p>
            </header>
            <section class="hidden-xs scrollable m-t-xs">
                <ul class="nav nav-pills nav-stacked no-radius" id="acc-types">
                    <li class="active">
                        <a href="javascript:void(0)" rel="">全部</a>
                    </li>
                    {foreach $types as $gp=>$name}
                        <li>
                            <a href="javascript:void(0)" rel="{$gp}"> {$name}</a>
                        </li>
                    {/foreach}
                </ul>
            </section>
        </div>
    </aside>
    <section class="vbox">
        <header class="header bg-light clearfix b-b">
            <div class="row m-t-sm">
                <div class="col-xs-4 m-b-xs">
                    <a href="{$grantURL}" data-dialog class="btn btn-primary btn-sm grant" data-title="公众号授权">
                        <i class="fa fa-plus-square-o"></i> 扫码授权</a>
                    <a href="{'weixin/account/del'|app}" data-ajax data-grp="#table tbody input.grp:checked"
                       data-confirm="你真的要删除这些公众号吗？" data-warn="请选择要删除的公众号" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i> {'Delete'|t}</a>
                </div>
                <div class="col-xs-8 text-right m-b-xs">
                    <form data-table-form="#table" id="search-form" class="form-inline">
                        <input type="hidden" name="type" id="type" value=""/>
                        <div class="checkbox m-l-xs m-r-xs">
                            <label>
                                <input type="radio" name="authed" value="" checked="checked"/> 全部
                            </label>
                            <label>
                                <input type="radio" name="authed" value="1"/> 已认证
                            </label>
                            <label>
                                <input type="radio" name="authed" value="0"/> 未认证
                            </label>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" name="wxid" class="input-sm form-control" placeholder="微信号"/>
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-info" id="btn-do-search" type="submit">Go!</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </header>
        <section class="w-f">
            <div class="table-responsive">
                <table id="table" data-auto data-table="{'wechat/account/data'|app}" data-sort="id,d"
                       style="min-width: 1000px">
                    <thead>
                    <tr>
                        <th width="10">
                            <input type="checkbox" class="grp"/>
                        </th>
                        <th width="60" data-sort="id,d">ID</th>
                        <th width="80"></th>
                        <th>名称</th>
                        <th width="80">类型</th>
                        <th width="120">APPID</th>
                        <th width="120">微信号</th>
                        <th width="150">原始ID</th>
                        <th width="60" data-sort="authed,d">认证</th>
                        <th width="80"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </section>
        <footer class="footer b-t">
            <div data-table-pager="#table" data-limit="30"></div>
        </footer>
    </section>
</div>
<script type="text/javascript">
    layui.use(['jquery', 'clipboard', 'bootstrap', 'wulaui'], function ($, cp, b, wulaui) {
        var group = $('#acc-types');
        group.find('a').click(function () {
            var me = $(this), mp = me.closest('li');
            if (mp.hasClass('active')) {
                return;
            }
            group.find('li').not(mp).removeClass('active');
            mp.addClass('active');
            $('#type').val(me.attr('rel'));
            if (!me.attr('rel')) {
                $('#edit-url').attr('href', '{'wechat/account/edit'|app}');
            } else {
                $('#edit-url').attr('href', '{'wechat/account/edit'|app}/0/' + me.attr('rel'));
            }
            $('#search-form').submit();
            return false;
        });

        $('#workspace').on('before.dialog', '.new-item', function (e) {
            e.options.btn = ['保存', '取消'];
            e.options.yes = function () {
                $('#form').submit();
                return false;
            };
        }).on('before.dialog', '.grant', function (e) {
            e.options.end = function () {
                $('#table').reload();
            };
        }).on('click', '.copy', function () {
            cp.copy({
                "text/plain": $(this).data('clipboardText')
            }).then(function () {
                wulaui.toast.success('已复制');
            }, function () {
                wulaui.toast.error('请手工复制');
            });
        }).removeClass('layui-hide');

        $('body').on('ajax.success', '#form', function () {
            layer.closeAll();
        });
    })
</script>