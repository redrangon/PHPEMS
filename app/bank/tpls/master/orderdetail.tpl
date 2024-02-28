{x2;if:!$userhash}{x2;include:header}<body>{x2;include:nav}<div class="container-fluid">	<div class="row-fluid">		<div class="main">			<div class="col-xs-2 leftmenu">				{x2;include:menu}			</div>			<div id="datacontent">{x2;endif}				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">					<div class="col-xs-12">						<ol class="breadcrumb">							<li><a href="index.php?{x2;$_app}-master">{x2;$apps[$_app]['appname']}</a></li>							<li><a href="index.php?{x2;$_app}-master-orders">订单列表</a></li>							<li class="active">订单详情</li>						</ol>					</div>				</div>				{x2;if:$order['orderapp'] == 'item'}				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">					<h4 class="title" style="padding:10px;">						订单号：{x2;$order['ordersn']} 当前状态：{x2;$orderstatus[$order['orderstatus']]}					</h4>					<table class="table table-hover table-bordered">						<thead>							<tr class="info">								<th width="120"></th>								<th>商品</th>								<th width="120">数量</th>								<th width="200">小计（元）</th>							</tr>						</thead>						<tbody>							{x2;tree:$order['orderitems'],cart,cid}							<tr>								<td><a href="index.php?item-master-items-edit&goodsid={x2;v:cart['goodsid']}" target="_blank"><img src="{x2;v:cart['itemthumb']}" style="width:80px;"/></a></td>								<td>									{x2;v:cart['goodstitle']}<br />									<span style="font-size:1rem;">									{x2;tree:$mfields[v:cart['goodsmoduleid']],field,fid}					            	{x2;if:v:field['fieldsystem']}					            	{x2;v:field['fieldtitle']}:{x2;v:cart[v:field['field']]}&nbsp;					            	{x2;endif}					            	{x2;endtree}					            	</span>									{x2;if:v:cart['goodsinkind'] && $order['orderdescribe'][v:cart['itemid']]}									{x2;tree:$order['orderdescribe'][v:cart['itemid']],code,coid}									<br /><span style="font-size:1rem;">虚拟券码：{x2;v:code}</span>									{x2;endtree}									{x2;endif}					            </td>								<td>{x2;v:cart['number']}</td>								<td>{x2;eval: echo v:cart['number']*v:cart['itemprice']}</td>							</tr>							{x2;endtree}						</tbody>					</table>					<h4 class="text-right" style="padding:10px;">						订单总额：{x2;$order['orderprice']}&nbsp;&nbsp;					</h4>				</div>				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">					<h4 class="title" style="padding:10px;">						电子发票						{x2;if:$order['orderbill']}						<a class="pull-right btn btn-primary" href="{x2;$order['orderbill']}">下载</a>						{x2;endif}					</h4>					<form action="index.php?bank-master-orders-setbill" method="post">						<div class="form-group">				            <div class="col-sm-10 form-inline">								<script type="text/template" id="pe-template-uploadfile">									<div class="qq-uploader-selector" qq-drop-area-text="可将图片拖拽至此处上传" style="clear:both;">										<ul class="qq-upload-list-selector list-unstyled pull-left" aria-live="polite" aria-relevant="additions removals" style="clear:both;">											<li class="text-center">												<input size="45" class="form-control qq-edit-filename-selector" type="text" name="uploadfile" tabindex="0" value="">											</li>										</ul>										<ul class="qq-upload-list-selector list-unstyled pull-left" aria-live="polite" aria-relevant="additions removals" style="clear:both;">											<li class="text-center">												<input size="45" class="form-control qq-edit-filename-selector" type="text" name="uploadfile" tabindex="0" value="">											</li>										</ul>										<div class="qq-upload-button-selector col-xs-2">											<button class="btn btn-primary" type="button">上传文件<span class="process"></span></button>										</div>											<div class="col-xs-1">											<button class="btn btn-primary">提交</button>										</div>									</div>								</script>								<div class="fineuploader" attr-type="files" attr-template="pe-template-uploadfile" attr-ftype="pdf"></div>								<input type="hidden" name="ordersn" value="{x2;$order['ordersn']}"/>							</div>				        </div>					</form>				</div>				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">					<h4 class="title" style="padding:10px;">						收货信息											</h4>					<p>						地址：{x2;$order['orderuserinfo']['province']}{x2;$order['orderuserinfo']['city']}{x2;$order['orderuserinfo']['area']}{x2;$order['orderuserinfo']['addressinfo']} <br />姓名： {x2;$order['orderuserinfo']['receiver']} <br />电话： {x2;$order['orderuserinfo']['phone']}					</p>				</div>				<div class="box itembox">					<h4 class="title" style="padding:10px;">						物流与发货					</h4>					{x2;if:$order['orderstatus'] == 1}					<h4 style="padding:10px;">						订单未支付					</h4>					{x2;elseif:$order['orderstatus'] == 2}					<form action="index.php?bank-master-orders-sendorder" method="post">						<div class="form-group">				            <div class="controls form-inline">					            <label class="radio-inline">					            	物流公司：<input type="text" class="form-control" name="postname" size="15" value="{x2;$order['orderpost']['postname']}">					            </label>					            <label class="radio-inline">					            	运单号：<input type="text" class="form-control" name="postorder" size="15" value="{x2;$order['orderpost']['postorder']}">					            </label>					            <label class="radio-inline">					            	<button class="btn btn-primary" type="submit">发货</button>					            	<input type="hidden" name="ordersn" value="{x2;$order['ordersn']}"/>					            </label>					        </div>				        </div>					</form>					{x2;elseif:$order['orderstatus'] == 99}					<p style="padding:10px;">						订单已经撤销了					</p>					{x2;else}					<p style="padding:10px;">						物流公司：{x2;$order['orderpost']['postname']} &nbsp;&nbsp; 运单号：{x2;$order['orderpost']['postorder']}					</p>					{x2;endif}				</div>				<div class="box itembox">					<h4 class="title" style="padding:10px;">						更改状态					</h4>					<form action="index.php?bank-master-orders-modifyorder" method="post">						<div class="form-group">				            <div class="controls form-inline">					            <label class="radio-inline">					            	<input type="radio" class="form-control" name="orderstatus" value="1"> 未支付					            </label>					            <label class="radio-inline">					            	<input type="radio" class="form-control" name="orderstatus" value="2"> 待发货					            </label>					            <label class="radio-inline">					            	<input type="radio" class="form-control" name="orderstatus" value="3"> 已发货					            </label>					            <label class="radio-inline">					            	<input type="radio" class="form-control" name="orderstatus" value="4"> 已完成					            </label>					            <label class="radio-inline">					            	<input type="radio" class="form-control" name="orderstatus" value="99"> 已取消					            </label>					            <label class="radio-inline">					            	更改原因：<input type="text" class="form-control" name="reason">					            </label>					            <label class="radio-inline">					            	<button class="btn btn-primary" type="submit">更改</button>					            	<input type="hidden" name="ordersn" value="{x2;$order['ordersn']}"/>					            </label>					        </div>				        </div>					</form>				</div>				<div class="box itembox" style="margin-bottom:0px;">					<h4 class="title" style="padding:10px;">						更改状态记录					</h4>					<table class="table table-hover table-bordered">						<thead>							<tr class="info">								<th>操作原因</th>								<th width="120">原状态</th>								<th width="120">修改后状态</th>								<th width="240">操作人</th>								<th width="200">操作时间</th>							</tr>						</thead>						<tbody>						{x2;tree:$order['orderfaq'],faq,fid}							<tr>								<td>{x2;v:faq['reason']}</td>								<td>{x2;$orderstatus[v:faq['prestatus']]}</td>								<td>{x2;$orderstatus[v:faq['status']]}</td>								<td>{x2;v:faq['username']}</td>								<td>{x2;date:v:faq['time'],'Y-m-d H:i:s'}</td>							</tr>						{x2;endtree}						</tbody>					</table>				</div>				{x2;else}				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">					<h4 class="title" style="padding:10px;">						订单号：{x2;$order['ordersn']} 当前状态：{x2;$orderstatus[$order['orderstatus']]}					</h4>					<table class="table table-bordered">						<tr class="info">							<td>充值金额</td>							<td>可兑换积分</td>							<td>下单时间</td>						</tr>						<tr>							<td>{x2;$order['orderprice']}</td>							<td>{x2;eval:echo $order['orderprice']*10}</td>							<td>{x2;date:$order['ordercreatetime'],'Y-m-d'}</td>						</tr>						<tr>							<td colspan="3"><p class="text-right">应付款：￥{x2;$order['orderprice']} 元</p></td>						</tr>					</table>					<h4 class="text-right" style="padding:10px;">						订单总额：{x2;$order['orderprice']}&nbsp;&nbsp;					</h4>				</div>				{x2;endif}			</div>{x2;if:!$userhash}		</div>	</div></div>{x2;include:footer}</body></html>{x2;endif}