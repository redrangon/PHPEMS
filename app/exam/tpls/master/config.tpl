{x2;include:header}<body>{x2;include:nav}<div class="container-fluid">	<div class="row-fluid">		<div class="main">            <div class="col-xs-2 leftmenu">                {x2;include:menu}            </div>            <div id="datacontent">				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">					<div class="col-xs-12">						<ol class="breadcrumb">							<li><a href="index.php?{x2;$_app}-master">{x2;$apps[$_app]['appname']}</a></li>							<li class="active">模块设置</li>						</ol>					</div>				</div>				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">					<h4 class="title" style="padding:10px;">						模块设置					</h4>					<form action="index.php?exam-master-config" method="post" class="form-horizontal" style="padding-top:10px;margin-bottom:0px;">						<div class="form-group">							<label class="col-sm-2 control-label">错题收录：</label>							<div class="col-sm-10">								<label class="radio-inline">									<input name="args[appsetting][autorecord]" type="radio" value="0" {x2;if:!$app['appsetting']['autorecord']}checked{x2;endif}/>关闭自动收录								</label>								<label class="radio-inline">									<input name="args[appsetting][autorecord]" type="radio" value="1" {x2;if:$app['appsetting']['autorecord']}checked{x2;endif}/>开启自动收录								</label>								<span class="help-block">由于自身的特殊性，主观题均不作为错题收录。</span>							</div>						</div>						<div class="form-group">							<label class="col-sm-2 control-label">错题清理：</label>							<div class="col-sm-10">								<label class="radio-inline">超过 </label>								<label class="radio-inline">									<input name="args[appsetting][recordperiodic]" class="form-control" type="text" value="{x2;$app['appsetting']['recordperiodic']}"/>								</label>								<label class="radio-inline"> 天自动清理</label>								<span class="help-block">填写超期的天数，0为不清理。</span>							</div>						</div>						<div class="form-group">							<label for="seo_description" class="col-sm-2 control-label"></label>							<div class="col-sm-9">								<button class="btn btn-primary" type="submit">提交</button>								<input type="hidden" name="appconfig" value="1"/>								<input type="hidden" name="appid" value="{x2;$appid}"/>								{x2;tree:$search,arg,aid}								<input type="hidden" name="search[{x2;v:key}]" value="{x2;v:arg}"/>								{x2;endtree}							</div>						</div>					</form>				</div>			</div>		</div>	</div></div>{x2;include:footer}</body></html>