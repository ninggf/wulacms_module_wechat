<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>服务端 | 文档 - EasyWeChat - 世界上最好的微信开发 SDK</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
</head>
<body>
{if $msg=='ok'}
    <script>
        alert('授权成功!');
		window.opener=null;window.open('','_self');window.close();
    </script>
{else}
    {$msg}
{/if}
</body>
</html>