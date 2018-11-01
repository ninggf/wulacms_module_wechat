<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>第三方平台授权结果</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
</head>
<body>
{if $msg}
    <h1 style="text-align: center"> "{$msg}"授权成功，请关闭本页~</h1>
{else}
    <div style="text-align: center;">
        <a href="{'wechat/service/grant'|app}">点击重新授权</a>
    </div>
{/if}
</body>
</html>