{x2;if:!$userhash}
{x2;include:header}
<body>
<div class="pages">
    {x2;endif}
    <div class="page-tabs">
        <div class="page-header">
            <div class="col-10">搜索结果</div>
        </div>
        <div class="page-content header">
            <div class="list-box bg">
                <ol>
                    {x2;tree:$items['data'],item,iid}
                    <li class="unstyled">
                        <div class="col-4x">
                            <div class="rows illus">
                                <a href="index.php?item-phone-item&goodsid={x2;v:item['goodsid']}" class="ajax">
                                    <img src="{x2;if:v:item['itemimages'][0]}{x2;v:item['itemimages'][0]}{x2;else}files/public/img/paper.jpg{x2;endif}">
                                </a>
                            </div>
                        </div>
                        <div class="col-4l">
                            <a href="index.php?item-phone-item&goodsid={x2;v:item['goodsid']}" class="ajax">
                                <div class="rows info">
                                    <p class="intro">{x2;substring:v:item['goodstitle'],72}</p>
                                    <h5 class="title text-danger">￥{x2;v:item['goodsminprice']}</h5>
                                </div>
                            </a>
                        </div>
                    </li>
                    {x2;endtree}
                </ol>
            </div>
        </div>
    </div>
    {x2;if:!$userhash}
</div>
</body>
</html>
{x2;endif}