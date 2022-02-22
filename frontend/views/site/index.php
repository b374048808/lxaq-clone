<div>

</div>
<script>
    var timestamp = 0;
    var offset = 0;
    
    function prodcut(){
        $.ajax({
            headers: {
                application: '8m6WF3jb5yb',
                timestamp: new Date().getTime() + offset,
                signature: 'rlLuL7QK3abT3cfXG4zoIXayXig=',
                sdk: 0,
                version: '20181031202055',
            },
            url: 'http://ag-api.ctwing.cn/aep_product_management/product',
            type: 'get',
            // 设置的是请求参数
            data: {
                "productId": "15128976"
            },
            // 用于设置响应体的类型 注意 跟 data 参数没关系！！！
            dataType: 'json',
            success: function(res) {
                console.log('2')
                // 一旦设置的 dataType 选项，就不再关心 服务端 响应的 Content-Type 了
                // 客户端会主观认为服务端返回的就是 JSON 格式的字符串
                console.log(res)
            }
        })
    }
    $.ajax({
        headers: {
            application: '8m6WF3jb5yb',
            signature: 'rlLuL7QK3abT3cfXG4zoIXayXig=',
            sdk: 0,
            version: '20181031202055',
        },
        url: 'http://ag-api.ctwing.cn/echo',
        type: 'get',
        // 用于设置响应体的类型 注意 跟 data 参数没关系！！！
        dataType: 'json',
        complete: function(xhr, data) {
            // 获取相关Http Response header
            var wpoInfo = {
                // 服务器端时间
                "date": xhr.getResponseHeader('Date'),
                // 如果开启了gzip，会返回这个东西
                "contentEncoding": xhr.getResponseHeader('Content-Encoding'),
                // keep-alive ？ close？
                "connection": xhr.getResponseHeader('Connection'),
                // 响应长度
                "contentLength": xhr.getResponseHeader('Content-Length'),
                // 服务器类型，apache？lighttpd？
                "server": xhr.getResponseHeader('Server'),
                "vary": xhr.getResponseHeader('Vary'),
                "transferEncoding": xhr.getResponseHeader('Transfer-Encoding'),
                // text/html ? text/xml?
                "contentType": xhr.getResponseHeader('Content-Type'),
                "cacheControl": xhr.getResponseHeader('Cache-Control'),
                // 生命周期？
                "exprires": xhr.getResponseHeader('Exprires'),
                "lastModified": xhr.getResponseHeader('Last-Modified')
            };
            // 在这里，做想做的事。。。
            console.log();
            offset = xhr.getResponseHeader('timestamp') - new Date().getTime();
            prodcut();
            // 一旦设置的 dataType 选项，就不再关心 服务端 响应的 Content-Type 了
            // 客户端会主观认为服务端返回的就是 JSON 格式的字符串
            console.log('1')
        },
    })

</script>