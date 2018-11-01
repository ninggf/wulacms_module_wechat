<tbody data-total="{$total}" class="wulaui">
{foreach $rows as $row}
    <tr>
        <td><input type="checkbox" value="{$row.id}" class="grp"/></td>
        <td>{$row.id}</td>
        <td>
            {if $row.avatar}
                <p class="thumb-sm">
                    <img src="{$row.avatra}" alt="" class="img-rounded"/>
                </p>
            {/if}
        </td>
        <td>
            {$row.name}
        </td>
        <td>{$types[$row.type]}</td>
        <td>{$row.app_id}</td>
        <td>{$row.wxid}</td>
        <td>{$row.origin_id}</td>
        <td>{if $row.authed}Y{else}N{/if}</td>
        <td class="text-right">
            <a class="btn btn-xs btn-danger" href="{'weixin/account/del'|app}/{$row.id}" data-ajax
               data-confirm="你真的要删除这个公众号吗?">
                <i class="fa fa-trash-o"></i>
            </a>
            <a data-cls="layui-icon" data-tab="&#xe663;" data-title="公众号:{$row.name}"
               href="{'wechat/admin'|app}/{$row.id}" class="btn btn-xs btn-primary">
                <i class="fa fa-cog fa-fw"></i>
            </a>
        </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="9" class="text-center">无数据!</td>
    </tr>
{/foreach}
</tbody>