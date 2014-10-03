<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>HFICampus Netdisk</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/netdisk/index.css');?>">
	</head>
	<body>
		<div id="header">
			<div id="toolBar" class="fleft">
				<button type="button" id="createFolder" class="button">Create Folder</button>
				<button type="button" id="upload" class="button">Upload</button>
				<div id="dynamicTools" class="hidden">
					<div id="singleDirTools" class="hidden tools">
						<button type="button" class="button open">Open</button>

						<button type="button" class="button delete">Delete</button>
					</div>
					<div id="singleFileTools" class="hidden tools">
						<button type="button" class="button download">Download</button>

						<button type="button" class="button delete">Delete</button>
					</div>
					<div id="multipleSelectionTools" class="hidden tools">
						<button type="button" class="button" id="deleteSelected">Delete Selected</button>
					</div>
					<button type="button" id="clearSelection" class="button">Clear Selection</button>
				</div>
				
			</div>
			
		</div>
		<div id="container">
			<div id="infoBar">
				<div id="currentSet" class="fleft">
					<div id="currentSet-name" class="inline"><!-- Here will be filled by js --></div>
					<div id="currentSet-path" class="inline"><!-- Here will be filled by js --></div>
				</div>
			</div>
			<div id="list"><!-- Here will be filled by js --></div>
			<div id="emptyList" class="hidden">This folder is empty. <a href="">Back</a> </div>
		</div>
		<div id="frontend">
			<div id="mask" class="hidden"></div>
			<div id="loading" class="modal hidden">Loading...</div>
			<div id="dirContextMenu" class="contextMenu hidden">
				<ul class="contextMenuItems">
					<li class="contextMenuItem contextMenuButton open">Open</li>
					<li class="contextMenuItem"><hr /></li>
					
					<li class="contextMenuItem contextMenuButton delete">Delete</li>
				</ul>
			</div>
			<div id="fileContextMenu" class="contextMenu hidden">
				<ul class="contextMenuItems">
					<li class="contextMenuItem contextMenuButton download">Download</li>
					<li class="contextMenuItem"><hr /></li>
					
					<li class="contextMenuItem contextMenuButton delete">Delete</li>
				</ul>
			</div>
			<div id="uploadBox" class="modal hidden">
				<form action="<?php echo site_url('netdisk/upload');?>" class="dialog" enctype="multipart/form-data" id="uploadForm" method="post" target="upload">
				    <input id="path" name="path" type="hidden" />
	        		<input id="file" name="file" type="file" multiple />
	        		<div id="uploadProgressBar" class="hidden"></div>
	        		<div class="buttons">
						<button type="submit" id="uploadForm-submit" class="button ok">OK</button>
					    <button type="button" id="uploadForm-cancel" class="button cancel">Cancel</button>
					</div>
	    		</form>
			</div>
			<div id="createFolderBox" class="modal hidden">
				<form action="" class="dialog" id="createFolderForm" method="post">
					<p id="createFolderInfo" class="info">Please input the new folder name below:</p>
					<input class="inputField" type="text" id="objectName" />
					<div class="processing hidden">Processing...</div>
					<div class="error hidden">
						<p class="message"></p>
						<button type="button" id="renameForm-close" class="button close">Close</button>
					</div>
					<div class="buttons">
						<button type="submit" id="createFolderForm-submit" class="button ok">OK</button>
					    <button type="button" id="createFolderForm-cancel" class="button cancel">Cancel</button>
					</div>
				</form>
			</div>
			
			<div id="deleteBox" class="modal hidden">
				<form action="" class="dialog" id="deleteForm" method="post">
					<p id="deletionInfo" class="info"></p>
					<div class="processing hidden">Processing...</div>
					<div class="error hidden">
						<p class="message"></p>
						<button type="button" id="renameForm-close" class="button close">Close</button>
					</div>
					<div class="buttons">
						<button type="submit" id="deleteForm-submit" class="button ok">Yes</button>
						<button type="button" id="deleteForm-cancel" class="button cancel">No</button>
					</div>
				</form>
			</div>
		</div>
		<div id="backend" class="hidden">
			<iframe src="" frameborder="0" id="download"></iframe>
			<iframe src="" frameborder="0" id="uploadFrame" name="upload"></iframe>
		</div>
	</body>
	<script type="text/javascript" src="<?php echo base_url('js/json/json2.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/netdisk/jquery.plugin.halfcoder.js');?>?20130928"></script>
	<script type="text/javascript" src="<?php echo base_url('js/underscore.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/netdisk/index.js');?>?20130928"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			var options = {
				"download" : "<?php echo site_url('netdisk/download');?>",
				"api" : "<?php echo site_url('netdisk/api');?>"
			};
			window.netdisk.Run(options);
		});
	</script>
</html>