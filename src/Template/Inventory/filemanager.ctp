<?php
//$this->extend('/Common/menu')
// use Cake\Routing\Router;
use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

/********************************
Simple PHP File Manager
Copyright John Campbell (jcampbell1)

Liscense: MIT
********************************/

//Disable error report for undefined superglobals
error_reporting(0);

################## Google Cloud Credentials #########################################
$storageClient = new StorageClient([
	'projectId' => 'gym-management-system-188906',
	'keyFilePath' => WWW_ROOT .'/nghome/gym-management-system-188906-8be3c1fa2801.json',
]);

$bucket = $storageClient->bucket('gym-management-system-188906.appspot.com');

$adapter = new GoogleStorageAdapter($storageClient, $bucket);
$filesystem = new Filesystem($adapter);
################## Google Cloud Credentials #########################################
//Security options
$allow_delete = true; // Set to false to disable delete button and delete POST request.
$allow_upload = ($this->ERPfunction->retrive_accessrights($role,'inventoryfileadd')==1)?true:false; // Set to true to allow upload files
$allow_create_folder = true; // Set to false to disable folder creation
$allow_direct_link = false; // Set to false to only allow downloads and not direct link
$allow_show_folders = true; // Set to false to hide all subdirectories

$disallowed_extensions = ['php'];  // must be an array. Extensions disallowed to be uploaded
$hidden_extensions = ['php']; // must be an array of lowercase file extensions. Extensions hidden in directory index

$PASSWORD = '';  // Set the password, to access the file manager... (optional)

if($PASSWORD) {

	session_start();
	if(!$_SESSION['_sfm_allowed']) {
		// sha1, and random bytes to thwart timing attacks.  Not meant as secure hashing.
		$t = bin2hex(openssl_random_pseudo_bytes(10));
		if($_POST['p'] && sha1($t.$_POST['p']) === sha1($t.$PASSWORD)) {
			$_SESSION['_sfm_allowed'] = true;
			header('Location: ?');
		}
		echo '<html><body><form action=? method=post>PASSWORD:<input type=password name=p autofocus/></form></body></html>';
		exit;
	}
}

// must be in UTF-8 or `basename` doesn't work
setlocale(LC_ALL,'en_US.UTF-8');

$tmp_dir = dirname($_SERVER['SCRIPT_FILENAME']);

if(DIRECTORY_SEPARATOR==='\\') $tmp_dir = str_replace('/',DIRECTORY_SEPARATOR,$tmp_dir);
$tmp = get_absolute_path($tmp_dir . '/' .$_REQUEST['file']);

if($tmp === false)
	err(404,'File or Directory Not Found');
if(substr($tmp, 0,strlen($tmp_dir)) !== $tmp_dir)
	err(403,"Forbidden");
if(strpos($_REQUEST['file'], DIRECTORY_SEPARATOR) === 0)
	err(403,"Forbidden");

if(!$_COOKIE['_sfm_xsrf'])
	setcookie('_sfm_xsrf',bin2hex(openssl_random_pseudo_bytes(16)));
// if($_POST) {
	// if($_COOKIE['_sfm_xsrf'] !== $_POST['xsrf'] || !$_POST['xsrf'])
		// err(403,"XSRF Failure");
// }

$file = $_REQUEST['file'] ?: '.';
if($_GET['do'] == 'list') {
	if (is_dir($file)) {
		var_dump($file);die;
		$directory = $file;
		$result = [];
		$files = array_diff(scandir($directory), ['.','..']);
		foreach ($files as $entry) if (!is_entry_ignored($entry, $allow_show_folders, $hidden_extensions)) {
		$i = $directory . '/' . $entry;
		$stat = stat($i);
	        $result[] = [
	        	'mtime' => $stat['mtime'],
	        	'size' => $stat['size'],
	        	'name' => basename($i),
	        	'path' => preg_replace('@^\./@', '', $i),
	        	'is_dir' => is_dir($i),
	        	'is_deleteable' => $allow_delete && ((!is_dir($i) && is_writable($directory)) ||
                                                           (is_dir($i) && is_writable($directory) && is_recursively_deleteable($i))),
	        	'is_readable' => is_readable($i),
	        	'is_writable' => is_writable($i),
	        	'is_executable' => is_executable($i),
	        ];
	    }
	} else {
		err(412,"Not a Directory");
	}
	echo json_encode(['success' => true, 'is_writable' => is_writable($file), 'results' =>$result]);
	exit;
} elseif ($_POST['do'] == 'delete') {
	//debug($file);die;
	if($allow_delete) {
		$type = $_REQUEST['type'];
		if($type == 'dir')
		{
			$files = $filesystem->listContents("/$file", false);
			if(!empty($files))
			{
				echo false;
			}else{
			    $responseDir = $filesystem->deleteDir($file);
				echo true;
			}
		}else{
			$exists = $filesystem->has("archive/".$file);
			if($exists)
			{
				$from = $file;
				$to = "archive/".$file;
				$filesystem->delete("archive/".$file);
				$response = $filesystem->copy($from, $to);
				if($response){
					$responseFile = $filesystem->delete($file);
					echo true;
				}
			}else{
				$from = $file;
				$to = "archive/".$file;
				$response = $filesystem->copy($from, $to);
				if($response){
					$responseFile = $filesystem->delete($file);
					echo true;
				}
			}
			
		}
		exit;
	}
	exit;
} elseif ($_POST['do'] == 'mkdir' && $allow_create_folder) {
	// don't allow actions outside root. we also filter out slashes to catch args like './../outside'
	$dir = $_POST['name'];
	$folder_location = "";
	if($file == '.')
	{
		$folder_location = $dir;
	}else{
		$folder_location = $file.'/'.$dir;
	}
	// $dir = str_replace('/', '', $dir);
	// if(substr($dir, 0, 2) === '..')
	    // exit;
	// chdir($file);
	// @mkdir($_POST['name']);
	// var_dump($folder_location);
	// var_dump($dir);die;
	$exists = $filesystem->has($folder_location);
	// var_dump($exists);die;
	$response = $filesystem->createDir($folder_location);
	exit;
} elseif ($_POST['do'] == 'upload' && $allow_upload) {
	###################################################################
	// $my_bucket = "gs://".$bucket->name()."/";
	// var_dump($my_bucket);die;
	// if($file == '.')
	// {
		// $upload_location = $my_bucket.$_FILES['file_data']['name'];
	// }else{
		// $upload_location = $my_bucket.$file.'/'.$_FILES['file_data']['name'];
	// }
	// var_dump($upload_location);die;
	// $options = array('gs'=>array('acl'=>'public-read','Content-Type' => $_FILES['file_data']['type']));
	// $ctx = stream_context_create($options);
	// $saved = file_put_contents($upload_location, file_get_contents($_FILES['file_data']['tmp_name']), 0, $ctx);
	// var_dump($saved);die;
	###################################################################
	###################################################################
	// $file_name = $_FILES['file_data']['name'];
	// $temp_name = $_FILES['file_data']['tmp_name'];
	// $my_bucket = $bucket->name();
	// if($file == '.')
	// {
		// $upload_location = $objectName;
	// }else{
		// $upload_location = $file.'/'.$objectName;
	// }
	
	// $ok = move_uploaded_file($temp_name, "gs://${$my_bucket}/${file_name}");
	// var_dump($ok);die;
	#####################################################################
	############## Directly Upload ###############
	// $content = file_get_contents($_FILES['file_data']['tmp_name']);
    // $objectName = $_FILES["file_data"]["name"];
	
	// if($file == '.')
	// {
		// $upload_location = $objectName;
	// }else{
		// $upload_location = $file.'/'.$objectName;
	// }
	
    // $object = $bucket->upload($content, [
        // 'name' => $upload_location
    // ]);
	// echo "https://storage.googleapis.com/".$bucket->name()."/".$objectName;die;
	############## Directly Upload ###############
	
	// $url = $adapter->getUrl($file);
	// var_dump($url);die;
	// $ok = move_uploaded_file( $_FILES["file_data"]["tmp_name"], $url . $_FILES["file_data"]["name"]);
	// var_dump($ok);die;
	// exit;
	############### Directly Upload ###############
	
	foreach($disallowed_extensions as $ext)
		if(preg_match(sprintf('/\.%s$/',preg_quote($ext)), $_FILES['file_data']['name']))
			err(403,"Files of this type are not allowed.");
	$file_name = $_FILES['file_data']['name'];
	// $res = move_uploaded_file($_FILES['file_data']['tmp_name'], $file.'/'.$_FILES['file_data']['name']);
	$response = $filesystem->write("$file/".$file_name, file_get_contents($_FILES['file_data']['tmp_name']));
	exit;
} elseif ($_GET['do'] == 'download') {
	
	$contents = $filesystem->read($file);
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	$filename = pathinfo($file, PATHINFO_BASENAME);
	touch($filename); // create a local EMPTY copy
	file_put_contents($filename, $contents);
	
	//$filename = basename('gcs.'.$ext);
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	header('Content-Type: ' . finfo_file($finfo, $filename));
	header('Content-Length: '. $filesystem->getSize($file));
	header(sprintf('Content-Disposition: attachment; filename=%s',
		strpos('MSIE',$_SERVER['HTTP_REFERER']) ? rawurlencode($filename) : "\"$filename\"" ));
	ob_flush();
	readfile($filename);
	unlink($filename);	
	exit;
}

function is_entry_ignored($entry, $allow_show_folders, $hidden_extensions) {
	if ($entry === basename(__FILE__)) {
		return true;
	}

	if (is_dir($entry) && !$allow_show_folders) {
		return true;
	}

	$ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
	if (in_array($ext, $hidden_extensions)) {
		return true;
	}

	return false;
}

function rmrf($dir) {
	if(is_dir($dir)) {
		$files = array_diff(scandir($dir), ['.','..']);
		foreach ($files as $file)
			rmrf("$dir/$file");
		rmdir($dir);
	} else {
		unlink($dir);
	}
}
function is_recursively_deleteable($d) {
	$stack = [$d];
	while($dir = array_pop($stack)) {
		if(!is_readable($dir) || !is_writable($dir))
			return false;
		$files = array_diff(scandir($dir), ['.','..']);
		foreach($files as $file) if(is_dir($file)) {
			$stack[] = "$dir/$file";
		}
	}
	return true;
}

// from: http://php.net/manual/en/function.realpath.php#84012
function get_absolute_path($path) {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $absolutes = [];
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return implode(DIRECTORY_SEPARATOR, $absolutes);
    }

function err($code,$msg) {
	http_response_code($code);
	echo json_encode(['error' => ['code'=>intval($code), 'msg' => $msg]]);
	exit;
}

function asBytes($ini_v) {
	$ini_v = trim($ini_v);
	$s = ['g'=> 1<<30, 'm' => 1<<20, 'k' => 1<<10];
	return intval($ini_v) * ($s[strtolower(substr($ini_v,-1))] ?: 1);
}
$MAX_UPLOAD_SIZE = min(asBytes(ini_get('post_max_size')), asBytes(ini_get('upload_max_filesize')));
?>

<style>
body {font-family: "lucida grande","Segoe UI",Arial, sans-serif; font-size: 14px;width:1024;padding:1em;margin:0;}
th {font-weight: normal; color: #1F75CC; background-color: #F0F9FF; padding:.5em 1em .5em .2em;
	text-align: left;cursor:pointer;user-select: none;}
th .indicator {margin-left: 6px }
thead {border-top: 1px solid #82CFFA; border-bottom: 1px solid #96C4EA;border-left: 1px solid #E7F2FB;
	border-right: 1px solid #E7F2FB; }
#top {height:52px;}
#mkdir {display:inline-block;float:right;padding-top:16px;}
label { display:block; font-size:11px; color:#555;}
#file_drop_target {width:500px; padding:12px 0; border: 4px dashed #ffffff;font-size:12px;color:#FFFFFF;
	text-align: center;float:right;margin-right:20px;}
#file_drop_target.drag_over {border: 4px dashed #96C4EA; color: #96C4EA;}
#upload_progress {padding: 4px 0;}
#upload_progress .error {color:#a00;}
#upload_progress > div { padding:3px 0;}
.no_write #mkdir, .no_write #file_drop_target {display: none}
.progress_track {display:inline-block;width:200px;height:10px;border:1px solid #333;margin: 0 4px 0 10px;}
.progress {background-color: #82CFFA;height:10px; }
footer {font-size:11px; color:#bbbbc5; padding:4em 0 0;text-align: left;}
footer a, footer a:visited {color:#bbbbc5;}
#breadcrumb { padding-top:34px; font-size:15px; color:#aaa;display:inline-block;float:left;}
#folder_actions {width: 50%;float:right;}
a, a:visited { color:#00c; text-decoration: none}
a:hover {text-decoration: underline}
.sort_hide{ display:none;}
table {border-collapse: collapse;width:100%;}
thead {max-width: 1024px}
td { padding:.2em 1em .2em .2em; border-bottom:1px solid #def;height:30px; font-size:12px;white-space: nowrap;}
td.first {font-size:14px;white-space: normal;}
td.empty { color:#777; font-style: italic; text-align: center;padding:3em 0;}
.is_dir .size {color:transparent;font-size:0;}
.is_dir .size:before {content: "--"; font-size:14px;color:#333;}
.is_dir .download{visibility: hidden}
a.delete {display:inline-block;
	background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADtSURBVHjajFC7DkFREJy9iXg0t+EHRKJDJSqRuIVaJT7AF+jR+xuNRiJyS8WlRaHWeOU+kBy7eyKhs8lkJrOzZ3OWzMAD15gxYhB+yzAm0ndez+eYMYLngdkIf2vpSYbCfsNkOx07n8kgWa1UpptNII5VR/M56Nyt6Qq33bbhQsHy6aR0WSyEyEmiCG6vR2ffB65X4HCwYC2e9CTjJGGok4/7Hcjl+ImLBWv1uCRDu3peV5eGQ2C5/P1zq4X9dGpXP+LYhmYz4HbDMQgUosWTnmQoKKf0htVKBZvtFsx6S9bm48ktaV3EXwd/CzAAVjt+gHT5me0AAAAASUVORK5CYII=) no-repeat scroll 0 5px;
	color:#d00;	margin-left: 15px;font-size:11px;padding:0 0 0 13px;
}
.name {
	background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABAklEQVRIie2UMW6DMBSG/4cYkJClIhauwMgx8CnSC9EjJKcwd2HGYmAwEoMREtClEJxYakmcoWq/yX623veebZmWZcFKWZbXyTHeOeeXfWDN69/uzPP8x1mVUmiaBlLKsxACAC6cc2OPd7zYK1EUYRgGZFkG3/fPAE5fIjcCAJimCXEcGxKnAiICERkSIcQmeVoQhiHatoWUEkopJEkCAB/r+t0lHyVN023c9z201qiq6s2ZYA9jDIwx1HW9xZ4+Ihta69cK9vwLvsX6ivYf4FGIyJj/rg5uqwccd2Ar7OUdOL/kPyKY5/mhZJ53/2asgiAIHhLYMARd16EoCozj6EzwCYrrX5dC9FQIAAAAAElFTkSuQmCC) no-repeat scroll 0px 12px;
	padding:15px 0 10px 40px;
}
.is_dir .name {
	background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAADdgAAA3YBfdWCzAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAI0SURBVFiF7Vctb1RRED1nZu5977VQVBEQBKZ1GCDBEwy+ISgCBsMPwOH4CUXgsKQOAxq5CaKChEBqShNK222327f79n0MgpRQ2qC2twKOGjE352TO3Jl76e44S8iZsgOww+Dhi/V3nePOsQRFv679/qsnV96ehgAeWvBged3vXi+OJewMW/Q+T8YCLr18fPnNqQq4fS0/MWlQdviwVqNpp9Mvs7l8Wn50aRH4zQIAqOruxANZAG4thKmQA8D7j5OFw/iIgLXvo6mR/B36K+LNp71vVd1cTMR8BFmwTesc88/uLQ5FKO4+k4aarbuPnq98mbdo2q70hmU0VREkEeCOtqrbMprmFqM1psoYAsg0U9EBtB0YozUWzWpVZQgBxMm3YPoCiLpxRrPaYrBKRSUL5qn2AgFU0koMVlkMOo6G2SIymQCAGE/AGHRsWbCRKc8VmaBN4wBIwkZkFmxkWZDSFCwyommZSABgCmZBSsuiHahA8kA2iZYzSapAsmgHlgfdVyGLTFg3iZqQhAqZB923GGUgQhYRVElmAUXIGGVgedQ9AJJnAkqyClCEkkfdM1Pt13VHdxDpnof0jgxB+mYqO5PaCSDRIAbgDgdpKjtmwm13irsnq4ATdKeYcNvUZAt0dg5NVwEQFKrJlpn45lwh/LpbWdela4K5QsXEN61tytWr81l5YSY/n4wdQH84qjd2J6vEz+W0BOAGgLlE/AMAPQCv6e4gmWYC/QF3d/7zf8P/An4AWL/T1+B2nyIAAAAASUVORK5CYII=) no-repeat scroll 0px 10px;
	padding:15px 0 10px 40px;
}
.download {
	background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAB2klEQVR4nJ2ST2sTQRiHn5mdmj92t9XmUJIWJGq9NHrRgxQiCtqbl97FqxgaL34CP0FD8Qv07EHEU0Ew6EXEk6ci8Q9JtcXEkHR3k+zujIdUqMkmiANzmJdnHn7vzCuIWbe291tSkvhz1pr+q1L2bBwrRgvFrcZKKinfP9zI2EoKmm7Azstf3V7fXK2Wc3ujvIqzAhglwRJoS2ImQZMEBjgyoDS4hv8QGHA1WICvp9yelsA7ITBTIkwWhGBZ0Iv+MUF+c/cB8PTHt08snb+AGAACZDj8qIN6bSe/uWsBb2qV24/GBLn8yl0plY9AJ9NKeL5ICyEIQkkiZenF5XwBDAZzWItLIIR6LGfk26VVxzltJ2gFw2a0FmQLZ+bcbo/DPbcd+PrDyRb+GqRipbGlZtX92UvzjmUpEGC0JgpC3M9dL+qGz16XsvcmCgCK2/vPtTNzJ1x2kkZIRBSivh8Z2Q4+VkvZy6O8HHvWyGyITvA1qndNpxfguQNkc2CIzM0xNk5QLedCEZm1VKsf2XrAXMNrA2vVcq4ZJ4DhvCSAeSALXASuLBTW129U6oPrT969AK4Bq0AeWARs4BRgieMUEkgDmeO9ANipzDnH//nFB0KgAxwATaAFeID5DQNatLGdaXOWAAAAAElFTkSuQmCC) no-repeat scroll 0px 5px;
	padding:4px 0 4px 20px;
}
input {
    -webkit-writing-mode: horizontal-tb !important;
    text-rendering: auto;
    color: initial;
    letter-spacing: normal;
    word-spacing: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    text-align: start;
    -webkit-appearance: textfield;
    background-color: white;
    -webkit-rtl-ordering: logical;
    cursor: text;
    margin: 0em;
    font: 400 13.3333px Arial;
    padding: 1px 0px;
    border-width: 2px;
    border-style: inset;
    border-color: initial;
    border-image: initial;
}
input[type="file" i] {
	float: right;
    width: 306px;
    -webkit-appearance: initial;
    background-color: initial;
    cursor: default;
    align-items: baseline;
    color: inherit;
    text-align: start !important;
    padding: initial;
    border: initial;
}
#createfolder {
    -webkit-appearance: push-button;
    user-select: none;
    white-space: pre;
    align-items: flex-start;
    text-align: center;
    cursor: default;
    color: buttontext;
    background-color: buttonface;
    box-sizing: border-box;
    padding: 1px 6px;
    border-width: 2px;
    border-style: outset;
    border-color: buttonface;
    border-image: initial;
	width:60px;
}
#dirname{
	width:120px;
}
</style>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
<script>
(function($){
	$.fn.tablesorter = function() {
		var $table = this;
		this.find('th').click(function() {
			var idx = $(this).index();
			var direction = $(this).hasClass('sort_asc');
			$table.tablesortby(idx,direction);
		});
		return this;
	};
	$.fn.tablesortby = function(idx,direction) {
		var $rows = this.find('tbody tr');
		function elementToVal(a) {
			var $a_elem = $(a).find('td:nth-child('+(idx+1)+')');
			var a_val = $a_elem.attr('data-sort') || $a_elem.text();
			return (a_val == parseInt(a_val) ? parseInt(a_val) : a_val);
		}
		$rows.sort(function(a,b){
			var a_val = elementToVal(a), b_val = elementToVal(b);
			return (a_val > b_val ? 1 : (a_val == b_val ? 0 : -1)) * (direction ? 1 : -1);
		})
		this.find('th').removeClass('sort_asc sort_desc');
		$(this).find('thead th:nth-child('+(idx+1)+')').addClass(direction ? 'sort_desc' : 'sort_asc');
		for(var i =0;i<$rows.length;i++)
			this.append($rows[i]);
		this.settablesortmarkers();
		return this;
	}
	$.fn.retablesort = function() {
		var $e = this.find('thead th.sort_asc, thead th.sort_desc');
		if($e.length)
			this.tablesortby($e.index(), $e.hasClass('sort_desc') );

		return this;
	}
	$.fn.settablesortmarkers = function() {
		this.find('thead th span.indicator').remove();
		this.find('thead th.sort_asc').append('<span class="indicator">&darr;<span>');
		this.find('thead th.sort_desc').append('<span class="indicator">&uarr;<span>');
		return this;
	}
})(jQuery);
$(function(){
	var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)')||0)[2];
	var MAX_UPLOAD_SIZE = <?php echo $MAX_UPLOAD_SIZE ?>;
	var $tbody = $('#list');
	$(window).on('hashchange',list).trigger('hashchange');
	$('#table').tablesorter();

	$('#table').on('click','.delete',function(data) {
		var type = $(this).attr('data-type');
		var hashval = $(this).attr('data-file');
		// $.post("",{'do':'delete',file:$(this).attr('data-file'),xsrf:XSRF},function(response){
			// list();
		// },'json');
		var fd = new FormData();
		fd.append('type',type);
		fd.append('file',hashval);
		fd.append('xsrf',XSRF);
		fd.append('do','delete');
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '?');
		xhr.onload = function() {
    		list();
  		};
	    xhr.send(fd);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == XMLHttpRequest.DONE) {
				if(xhr.responseText == false){
					alert("Directory has a files, you can't delete it.");
				}
			}
		}
		return false;
	});

	$('#mkdir').submit(function(e) {
		var hashval = decodeURIComponent(window.location.hash.substr(1)),
			$dir = $(this).find('[name=name]');
		// e.preventDefault();
		// $dir.val().length && $.post('?',{'do':'mkdir',name:$dir.val(),xsrf:XSRF,file:hashval},function(data){

			// list();
		// },'json');
		var fd = new FormData();
		fd.append('name',$dir.val());
		fd.append('file',hashval);
		fd.append('xsrf',XSRF);
		fd.append('do','mkdir');
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '?');
		xhr.onload = function() {
    		list();
  		};
		
		xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				
		   }
		};
	    xhr.send(fd);
		$dir.val('');
		return false;
	});
<?php if($allow_upload): ?>
	// file upload stuff
	$('#file_drop_target').on('dragover',function(){
		$(this).addClass('drag_over');
		return false;
	}).on('dragend',function(){
		$(this).removeClass('drag_over');
		return false;
	}).on('drop',function(e){
		e.preventDefault();
		var files = e.originalEvent.dataTransfer.files;
		$.each(files,function(k,file) {
			uploadFile(file);
		});
		$(this).removeClass('drag_over');
	});
	$('input[type=file]').change(function(e) {
		e.preventDefault();
		$.each(this.files,function(k,file) {
			uploadFile(file);
		});
	});


	function uploadFile(file) {
		var folder = decodeURIComponent(window.location.hash.substr(1));

		if(file.size > MAX_UPLOAD_SIZE) {
			var $error_row = renderFileSizeErrorRow(file,folder);
			$('#upload_progress').append($error_row);
			window.setTimeout(function(){$error_row.fadeOut();},5000);
			return false;
		}

		var $row = renderFileUploadRow(file,folder);
		$('#upload_progress').append($row);
		var fd = new FormData();
		fd.append('file_data',file);
		fd.append('file',folder);
		fd.append('xsrf',XSRF);
		fd.append('do','upload');
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '?');
		xhr.onload = function() {
			$row.remove();
    		list();
  		};
		xhr.upload.onprogress = function(e){
			if(e.lengthComputable) {
				$row.find('.progress').css('width',(e.loaded/e.total*100 | 0)+'%' );
			}
		};
	    xhr.send(fd);
	}
	function renderFileUploadRow(file,folder) {
		return $row = $('<div/>')
			.append( $('<span class="fileuploadname" />').text( (folder ? folder+'/':'')+file.name))
			.append( $('<div class="progress_track"><div class="progress"></div></div>')  )
			.append( $('<span class="size" />').text(formatFileSize(file.size)) )
	};
	function renderFileSizeErrorRow(file,folder) {
		return $row = $('<div class="error" />')
			.append( $('<span class="fileuploadname" />').text( 'Error: ' + (folder ? folder+'/':'')+file.name))
			.append( $('<span/>').html(' file size - <b>' + formatFileSize(file.size) + '</b>'
				+' exceeds max upload size of <b>' + formatFileSize(MAX_UPLOAD_SIZE) + '</b>')  );
	}
<?php endif; ?>
	function list() {
		var hashval = window.location.hash.substr(1);
		
		if(hashval != '' && hashval != 'inventory'){
		$.get('../Ajaxfunction/getcloudfiles?file='+ encodeURIComponent(hashval),function(data) {
			$tbody.empty();
			$('#breadcrumb').empty().html(renderBreadcrumbs(hashval));
			if(data.success) {
				$.each(data.results,function(k,v){
					$tbody.append(renderFileRow(v));
				});
				!data.results.length && $tbody.append('<tr><td class="empty" colspan=5>This folder is empty</td></tr>')
				// data.is_writable ? $('body').removeClass('no_write') : $('body').addClass('no_write');
				console.warn(data.error.msg);
			} else {
				console.warn(data.error.msg);
			}
			$('#table').retablesort();
		},'json');
		}
	}
	function renderFileRow(data) {
		var $link = $('<a class="name" />')
			.attr('href', data.is_dir ? '#' + encodeURIComponent(data.path) : './'+ encodeURIComponent(data.path))
			.text(data.name);
		var allow_direct_link = <?php echo $allow_direct_link?'true':'false'; ?>;
        	if (!data.is_dir && !allow_direct_link)  $link.css('pointer-events','none');
		var $dl_link = '';
		<?php if($this->ERPfunction->retrive_accessrights($role,'inventoryfiledownload')==1){?>
		var $dl_link = $('<a/>').attr('href','?do=download&file='+ encodeURIComponent(data.path))
			.addClass('download').text('download');
		<?php } ?>
		var $delete_link = '';
		<?php if($this->ERPfunction->retrive_accessrights($role,'inventoryfiledelete')==1){?>
		var $delete_link = $('<a href="#" data-type="'+data.type+'"/>').attr('data-file',data.path).addClass('delete').text('delete');
		<?php } ?>
		var perms = [];
		if(data.is_readable) perms.push('read');
		if(data.is_writable) perms.push('write');
		if(data.is_executable) perms.push('exec');
		var $html = $('<tr />')
			.addClass(data.is_dir ? 'is_dir' : '')
			.append( $('<td class="first" />').append($link) )
			.append( $('<td/>').attr('data-sort',data.is_dir ? -1 : data.size)
				.html($('<span class="size" />').text(formatFileSize(data.size))) )
			.append( $('<td/>').attr('data-sort',data.mtime).text(formatTimestamp(data.mtime)) )
			.append( $('<td/>').text(perms.join('+')) )
			.append( $('<td/>').append($dl_link).append( data.is_deleteable ? $delete_link : '') )
		return $html;
	}
	function renderBreadcrumbs(path) {
		var base = "inventory%2F",
			$html = $('<div/>').append( $('</div>') );
		var i = 0;
		$.each(path.split('%2F'),function(k,v){
			i++;
			if(i > 1)
			{
				if(v) {
					var v_as_text = decodeURIComponent(v);
					$html.append( $('<span/>').text(' ▸ ') )
						.append( $('<a/>').attr('href','#'+base+v).text(v_as_text) );
					base += v + '%2F';
				}
			}
		});
		return $html;
	}
	function formatTimestamp(unix_timestamp) {
		var m = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		var d = new Date(unix_timestamp*1000);
		return [m[d.getMonth()],' ',d.getDate(),', ',d.getFullYear()," ",
			(d.getHours() % 12 || 12),":",(d.getMinutes() < 10 ? '0' : '')+d.getMinutes(),
			" ",d.getHours() >= 12 ? 'PM' : 'AM'].join('');
	}
	function formatFileSize(bytes) {
		var s = ['bytes', 'KB','MB','GB','TB','PB','EB'];
		for(var pos = 0;bytes >= 1000; pos++,bytes /= 1024);
		var d = Math.round(bytes*10);
		return pos ? [parseInt(d/10),".",d%10," ",s[pos]].join('') : bytes + ' bytes';
	}
})

</script>
<div class="col-md-10" >
<?php
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>
<div class="block">
<div class="content">
		<div class="col-md-10 filter-form">
			
		<?php echo $this->Form->Create('form15',['id'=>'user_form22','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data']);?>
					
						
					<div class="form-row">
							<div class="col-md-2">Project Name</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="project_id" id="project_id">
								<option value="">Select Project</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
							<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="searchbyproject" id="go" class="btn btn-primary" value="Search"/></div></div>
                    </div>
                    	
					<div class="form-row">
						
					</div>
		
		<?php echo $this->Form->end(); ?>
			</div>
			</div>
			</div>
			<?php } ?>
			</div>
<?php
if($location != '')
{
?>
<div class="col-md-10" >
<div id="top">
   <?php if($allow_create_folder): ?>
	<form action="?" method="post" id="mkdir" />
		<label for=dirname>Create New Folder</label><input id=dirname type=text name=name value="" />
		<input type="submit" id="createfolder" value="create" />
	</form>

   <?php endif; ?>

   <?php if($allow_upload): ?>

	<div id="file_drop_target">
		<b>Drag Files Here To Upload
		or</b>
		<input type="file" multiple />
	</div>
   <?php endif; ?>
	<div id="breadcrumb">&nbsp;</div>
</div>

<div id="upload_progress"></div>
<table id="table" class="table"><thead id="accounts"><tr>
	<th>Name</th>
	<th>Size</th>
	<th>Modified</th>
	<th>Permissions</th>
	<th>Actions</th>
</tr></thead><tbody id="list">

</tbody></table>
<?php } ?>
</div>
<script>
$(function(){
	var hash = window.location.hash.substr(1);
	var getUrl = window.location;
    <!-- var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1]; -->
	var baseUrl = '<?php echo $baseurl; ?>';
	var sub_location = '<?php echo $location; ?>';
	if(hash != 'inventory')
	{
		if(sub_location != '')
		{
			window.location.href = baseUrl+"#inventory%2F"+sub_location;
			return false;
		}else{
			window.location.href = baseUrl+"#inventory";
			return false;
		}
	}
	// else{
		// window.location.href = baseUrl+"/Accounts/filemanager#account"+sub_location;
		// return false;
	// }
});
</script>
<style type="text/css">
	#accounts{
		border: none;
	}
	#accounts th{
		background: #5e737d!important;
		color: white!important;
	}
	#list{
		background: white;
		color: black;
	}
	td.first:hover a{
		color: blue;
		text-decoration: none;
	}
	.download:hover{
		color: blue;
		text-decoration: none;
	}
	#file_drop_target {
    border: 4px dashed #5E737D;
    color: #5E737D;
    }
</style>