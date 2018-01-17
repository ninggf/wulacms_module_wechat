<div class="hbox wulaui stretch">
    <section class="vbox wulaui" id="fans-workset">
            <header class="bg-light header b-b clearfix">
                <div class="row m-t-sm">
                    <div class="col-sm-3 m-b-xs">
                        <a href="#" class="btn btn-sm btn-default">
                           <i class="fa fa-list"></i>  粉丝列表
                        </a>
                    </div>
                    <div class="col-sm-9 m-b-xs text-right">
                        <form data-table-form="#core-admin-table" class="form-inline">
                            <div class="input-group input-group-sm">
                                <input type="text" name="nick" class="input-sm form-control" placeholder="{'昵称'|t}"/>
                            </div>
                            {*<div class="input-group input-group-sm">*}
                                {*<input type="text" name="tag" class="input-sm form-control" placeholder="{'标签'|t}"/>*}
                            {*</div>*}
                            <div class="input-group input-group-sm">
                                <input type="text" name="city" class="input-sm form-control" placeholder="{'城市'|t}"/>
                            </div>
                            {*<div class="input-group input-group-sm">*}
                                {*<input type="text" name="wx_ac" class="input-sm form-control" placeholder="{'所属公众号'|t}"/>*}
                            {*</div>*}
                            <div class="input-group input-group-sm">
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
                    <table id="core-admin-table" data-auto data-table="{'wechat/fans/data'|app}" data-sort="id,d"
                           style="min-width: 800px">
                        <thead>
                        <tr>
                            <th width="20">
                                <input type="checkbox" class="grp"/>
                            </th>
                            <th width="60" data-sort="id,d">ID</th>
                            <th width="100" data-sort="wx_name,a">昵称</th>
                            <th width="80" >头像</th>
                            <th width="100">区域</th>
                            <th width="100">标签</th>
                            <th width="100">关注状态</th>
                            <th width="100">备注</th>
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
		layui.use(['jquery', 'layer', 'wulaui'], function($, layer){
//			layer.msg('ok');
        });
    </script>
{/literal}
