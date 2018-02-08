<tbody data-total="{$total}" class="wulaui">
{foreach $rows as $row}
    <tr>
        <td>
            <input type="checkbox" value="{$row.id}" class="grp"/>
        </td>
        <td>{$row.id}</td>
        <td>{$row.wx_name}</td>
        <td>{$row.wx_nick}</td>
        <td>{$row.wx_app_id}</td>
        <td>
            {if $row.wx_type==0}订阅号{/if}
            {if $row.wx_type==1}服务号{/if}
            {if $row.wx_type==2}私人帐号{/if}
        </td>
        <td>
            {if $row.type==0}开发者模式{/if}
            {if $row.type==1}第三方授权{/if}
        </td>
        <td>{$row.create_time|date_format:"%Y-%m-%d"}</td>
        <td class="text-right">
            {if $row.type ==1}
                <a href="{'wechat/account/edit'|app}/{$row.id}" data-ajax="dialog" data-area="800px,500px"
                   data-title="查看『{$row.wx_name|escape}』" class="btn btn-xs edit-admin">
                    <i class="layui-icon" style="font-size: 20px;color: #01AAED;">&#xe605;</i>
                </a>
                {else}
                <a href="{'wechat/account/edit'|app}/{$row.id}" data-ajax="dialog" data-area="800px,500px"
                   data-title="编辑『{$row.wx_name|escape}』" class="btn btn-xs edit-admin">
                    <i class="layui-icon" style="font-size: 20px;color: #01AAED;">&#xe642;</i>
                </a>
            {/if}
            <a href="{'wechat/account/del'|app}/{$row.id}" data-confirm="你真的要删除?" data-ajax
               class="btn btn-xs edit-admin">
                <i class="layui-icon" style="font-size: 20px;color: #FF5722;">&#xe640;</i>
            </a>
        </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="{'core.admin.table'|tablespan:5}" class="text-center">暂无相关数据!</td>
    </tr>
{/foreach}
</tbody>