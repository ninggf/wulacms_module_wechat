<tbody data-total="{$total}" class="wulaui">
{foreach $rows as $row}
    <tr>
        <td>
            <input type="checkbox" value="{$row.id}" class="grp"/>
        </td>
        <td>{$row.id}</td>
        <td>{$row.wx_name}</td>
        <td>{date('y-m-d',$row.sta_date)}</td>
        <td>{if $row.target_user_total}{$row.target_user_total}{else}0{/if}</td>
        <td>{if $row.new_user_total}{$row.new_user_total}{else}0{/if}</td>
        <td>{if $row.cancel_user_total}{$row.cancel_user_total}{else}0{/if}</td>
        <td>{if $row.cumulate_user}{$row.cumulate_user}{else}0{/if}</td>
        <td>{date('y-m-d',$row.create_time)}</td>
        <td class="text-right toolbar">
            {if $row.article_detail}
                <a class="layui-btn layui-btn-xs " box="article-{$row.id}" data-type="auto"
                   data-method="offset">文章详情</a>
                <div id="article-{$row.id}" style="display:none;">
                    <table class="layui-table" lay-size="sm" lay-even>
                        <colgroup>
                            <col width="300">
                            <col width="400">
                            <col width="300">
                            <col width="300">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>文章标题</th>
                            <th>详细数据(-)</th>
                            <th>详细数据(二)</th>
                            <td>详细数据(三)</td>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $row.article_detail as $k=>$art }
                            <tr>
                                <td>{$art.title}({$k})</td>
                                <td class="text-left">
                                    {$art.add_to_fav_user_zh}:{if $art.add_to_fav_use}{$art.add_to_fav_use}{else}0{/if}
                                    <br> {$art.add_to_fav_count_zh}
                                    :{if $art.add_to_fav_count}{$art.add_to_fav_count}{else}0{/if}
                                    <br>{$art.target_user_zh}:{if $art.target_user}{$art.target_user}{else}0{/if}
                                    <br>{$art.share_user_zh}:{$art.share_user}
                                    <br>{$art.share_count_zh}:{$art.share_count}
                                    <br>{mb_substr($art.ori_page_read_user_zh,0,6)}:{$art.ori_page_read_user}
                                    <br>{$art.ori_page_read_count_zh}:{$art.ori_page_read_count}
                                </td>
                                <td class="text-left">
                                    <br>{$art.int_page_read_user_zh}:{$art.int_page_read_user}
                                    <br>{$art.int_page_read_count_zh}:{$art.int_page_read_count}
                                    <br>{$art.int_page_from_session_read_user_zh}:{$art.int_page_from_session_read_user}
                                    <br>{$art.int_page_from_session_read_count_zh}
                                    :{$art.int_page_from_session_read_count}
                                    <br>{$art.int_page_from_other_read_user_zh}:{$art.int_page_from_other_read_user}
                                    <br>{$art.int_page_from_other_read_count_zh}:{$art.int_page_from_other_read_count}
                                    <br>{$art.int_page_from_hist_msg_read_user_zh}
                                    :{$art.int_page_from_hist_msg_read_user}
                                    <br>{$art.int_page_from_hist_msg_read_count_zh}
                                    :{$art.int_page_from_hist_msg_read_count}
                                    <br>{$art.int_page_from_friends_read_user_zh}:{$art.int_page_from_friends_read_user}
                                    <br>{$art.int_page_from_friends_read_count_zh}
                                    :{$art.int_page_from_friends_read_count}
                                    <br>{$art.int_page_from_feed_read_user_zh}:{$art.int_page_from_feed_read_user}
                                    <br>{$art.int_page_from_feed_read_count_zh}:{$art.int_page_from_feed_read_count}
                                </td>
                                <td class="text-left">
                                    <br>{$art.feed_share_from_session_user_zh}:{$art.feed_share_from_session_user}
                                    <br>{$art.feed_share_from_session_cnt_zh}:{$art.feed_share_from_session_cnt}
                                    <br>{$art.feed_share_from_other_user_zh}:{$art.feed_share_from_other_user}
                                    <br>{$art.feed_share_from_other_cnt_zh}:{$art.feed_share_from_other_cnt}
                                    <br>{$art.feed_share_from_feed_user_zh}:{$art.feed_share_from_feed_user}
                                    <br>{$art.feed_share_from_feed_cnt_zh}:{$art.feed_share_from_feed_cnt}
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
                {else}
                <a class="layui-btn layui-btn-disabled layui-btn-xs">暂无反馈</a>
            {/if}
            {if $row.user_detail}
                <a class="layui-btn layui-btn-xs " box="fans-{$row.id}" data-type="auto" data-method="offset">粉丝详情</a>
                <div id="fans-{$row.id}" style="display: none;">
                    <table class="layui-table" lay-size="sm" lay-even>
                        <colgroup>
                            <col width="200">
                            <col width="200">
                            <col width="200">
                            <col width="200">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="">来源</th>
                            <th class="">新增</th>
                            <th class="">取关</th>
                            <td class="">实增</td>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $row.user_detail as $usr}
                            <tr>
                                <td class="text-left">{$usr.user_source_zh}</td>
                                <td class="text-left">{$usr.new_user}</td>
                                <td class="text-left">{$usr.cancel_user}</td>
                                <td>{$usr.new_user - $usr.cancel_user}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
                {else}
                <a class="layui-btn layui-btn-disabled layui-btn-xs">暂无反馈</a>
            {/if}

            <a href="{'wechat/dc/del'|app}/{$row.id}" data-confirm="你真的要删除?" data-ajax class="btn btn-xs edit-admin">
                <i class="layui-icon" style="font-size: 20px;color: #FF5722;">&#xe640;</i>
            </a>
        </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="{'core.admin.table'|tablespan:5}" class="text-center">暂无相关数据!</td>
    </tr>
{/foreach}
<script>
	layui.use('layer', function () { //独立版的layer无需执行这一句
		var $      = layui.jquery, layer = layui.layer;
		var active = {
			offset: function (othis) {
				var type = othis.attr('type'), target_id = othis.attr('box');
				console.log(target_id);
				layer.open({
					type      : 1
					, area    : '1000px'
					, title   : false
					, shade   : 0.8
					, offset  : type //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
					, id      : 'lay-tb' + type //防止重复弹出
					, content : $('#' + target_id)
					, btn     : '关闭'
					, btnAlign: 'c' //按钮居中
					, yes     : function () {
						layer.closeAll();
					}
				});
			}
		};
		$('.toolbar .layui-btn').on('click', function () {
			var othis = $(this), method = othis.data('method');
			active[method] ? active[method].call(this, othis) : '';
		});
	});
</script>
</tbody>