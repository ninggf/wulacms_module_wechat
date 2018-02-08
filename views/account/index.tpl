<div class="hbox wulaui stretch">
    <section id="core-users-workset">
        <header class="bg-light header b-b clearfix">
            <div class="row m-t-sm">
                <div class="col-sm-6 m-b-xs">
                    <a href="{'wechat/account/edit'|app}/0" class="btn btn-sm btn-success edit-admin" data-ajax="dialog"
                       data-area="800px,400px" data-title="新的公众号">
                        <i class="fa fa-plus"></i> 新加公众号(开发者模式)
                    </a>
                    <a href="{'wechat/service/auth_link'|app}" class="btn btn-sm btn-info "
                       target="_blank"
                       >
                        <i class="fa fa-plus"></i> 新加公众号(授权模式)
                    </a>
                </div>
                <div class="col-sm-6 m-b-xs text-right">
                    <form data-table-form="#core-admin-table" class="form-inline">
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" class="input-sm form-control" placeholder="{'搜索'|t}"/>
                            <span class="input-group-btn">
                            <button class="btn btn-sm btn-info" id="btn-do-search" type="submit">Go!</button>
                        </span>
                        </div>
                    </form>
                </div>
            </div>
        </header>
        <section class="w-f bg-white">
            <div class="table-responsive">
                <table id="core-admin-table" data-auto data-table="{'wechat/account/data'|app}" data-sort="id,d"
                       style="min-width: 800px">
                    <thead>
                    <tr>
                        <th width="20">
                            <input type="checkbox" class="grp"/>
                        </th>
                        <th width="60" data-sort="id,d">ID</th>
                        <th width="100" data-sort="wx_name,a">公众号名称</th>
                        <th width="180">简称</th>
                        <th width="100">微信appID</th>
                        <th width="100">公众号类型</th>
                        <th width="100">开发类型</th>
                        <th width="100">创建时间</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </section>
        <footer class="footer b-t">
            <div data-table-pager="#core-admin-table"></div>
        </footer>
    </section>

</div>
{literal}
    <script>
		layui.use(['jquery', 'layer', 'wulaui'], function ($, layer) {
			//对话框处理
			$('#core-users-workset').on('before.dialog', '.edit-admin', function (e) { // 增加编辑用户
				e.options.btn = ['保存', '取消'];
				e.options.yes = function () {
					$('#core-admin-form').on('ajax.success', function () {
						layer.closeAll()
					}).submit();
					return false;
				};
			});

		});
    </script>
{/literal}
