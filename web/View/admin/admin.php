<?php

require_once __DIR__.'/../../../vendor/autoload.php';
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

//Email Service
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
//Database Service
define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
define('DB_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT'));
define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
define('DB_PASS', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
define('DB_NAME', getenv('OPENSHIFT_GEAR_NAME'));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => DB_HOST,
		'port' => DB_PORT,
        'dbname' => DB_NAME,
        'user' => DB_USER,
        'password' => DB_PASS,
		'charset'   => 'utf8'
    ),
));

/*//Database Service
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
		'port' => '3306',
        'dbname' => 'lamaisondefuji',
        'user' => 'admink2PrdiS',
        'password' => '7p6s5CmiiN42',
		'charset'   => 'utf8'
    ),
));*/

//Session Service
$app->register(new Silex\Provider\SessionServiceProvider());
//Twig Service
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'',
));



$app->get('/', function(Application $app, Request $req) use ($app) {
    return $app->sendFile('login.html');
});

/* Gain of informations */

$app->get('/sendEmail', function(Request $request) use ($app) {
	//get company's introduction.
	/*$sql = "SELECT * FROM Company";
    $intro = $app['db']->fetchAll($sql);*/
		
	return $app->redirect('emailSender.html');
});

$app->get('/employee', function(Request $request) use ($app) {
	//get all the employees' information.
	$sql = "SELECT * FROM Employee";
	$employees = $app['db']->fetchAll($sql);
	$htmlEmps = '<table class="list"><caption><h1>职 员 名 单</h1>( '. count($employees) .' 条记录 )</caption>
					<tr>
						<td class="info">
							职位
						</td>
						<td class="info">
							姓名
						</td>
						<td class="edit">
							操作
						</td>
					</tr>';
	foreach($employees as $employee)
	{
		$htmlEmps .= '<tr>
						<td class="info">
							'. $employee['Position'] .'
						</td>
						<td class="info">
							'. $employee['Name'] .'
						</td>
						<td class="edit">
							<a href="javascript:window.location.href=\'../admin.php/employee_edit.html
							?ID='.addcslashes($employee['ID'],"'").'
							&Name='.addcslashes($employee['Name'],"'").'
							&Position='.addcslashes($employee['Position'],"'").'
							&Quote='.addcslashes($employee['Quote'],"'").'
							&Resume='.addcslashes($employee['Resume'],"'").'
							&Photo='.addcslashes($employee['Photo'],"'").'\'">编辑</a>
							<a href="javascript:deleteRecord(\'Employee\', \''. $employee['ID'] .'\', \''. $employee['Photo'] .'\')">删除</a>
						</td>
					</tr>';
	}
	$htmlEmps.= '</table>';
	
	return $app['twig']->render('employee_list.html', array(
        'htmlEmployees' => $htmlEmps
    ));
});

$app->get('/project', function(Request $request) use ($app) {
	//get all the projects.
	$sql = "SELECT * FROM Project";
	$projects = $app['db']->fetchAll($sql);
	$htmlProjects = '<table class="list"><caption><h1>项 目 列 表</h1>( '. count($projects) .' 条记录 )</caption>
						<tr>
						<td class="info">
							项目名
						</td>
						<td class="info">
							项目类型
						</td>
						<td class="edit">
							操作
						</td>
					</tr>';
	foreach($projects as $project)
	{
		$htmlProjects .= '<tr>
						<td class="info">
							'. $project['Name'] .'
						</td>
						<td class="info">
							'. $project['Type'] .'
						</td>
						<td class="edit">
							<a href="javascript:window.location.href=\'../admin.php/project_edit.html
							?ID='.addcslashes($project['ID'],"'").'
							&Name='.addcslashes($project['Name'],"'").'
							&Resume='.addcslashes($project['Resume'],"'").'
							&Details='.addcslashes($project['Details'],"'").'
							&Photo='.addcslashes($project['Photo'],"'").'
							&Type='.addcslashes($project['Type'],"'").'\'">编辑</a>
							<a href="javascript:deleteRecord(\'Project\', \''. $project['ID'] .'\', \''. $project['Photo'] .'\')">删除</a>
						</td>
					</tr>';
	}
	$htmlProjects.= '</table>';
	
	return $app['twig']->render('project_list.html', array(
        'htmlProjects' => $htmlProjects
    ));
});

$app->get('/protype', function(Request $request) use ($app) {
	//get all the categories of design(level 2)
	$sql = "SELECT * FROM ProType WHERE Level=2";
	$types = $app['db']->fetchAll($sql);
	$htmlTypes = '<table class="list"><caption><h1>项 目 分 类 列 表</h1>( '. count($types) .' 条记录 )</caption>
					<tr>
						<td class="info">
							类型
						</td>
						<td class="info">
							层级（默认为2）
						</td>
						<td class="edit">
							操作
						</td>
					</tr>';
	foreach($types as $type)
	{
		$htmlTypes .= '<tr>
						<td class="info">
							'. $type['Type'] .'
						</td>
						<td class="info">
							'. $type['Level'] .'
						</td>
						<td class="edit">
							<a href="javascript:window.location.href=\'../admin.php/protype_edit.html
							?ID='.addcslashes($type['ID'],"'").'
							&Type='.addcslashes($type['Type'],"'").'
							&Level='.addcslashes($type['Level'],"'").'\'">编辑</a>
							<a href="javascript:deleteRecord(\'ProType\', \''. $type['ID'] .'\', \'\')">删除</a>
						</td>
					</tr>';
	}
	
	return $app['twig']->render('protype_list.html', array(
        'htmlTypes' => $htmlTypes
    ));
});

$app->get('/partner', function(Request $request) use ($app) {
	//get all the partner
	$sql = "SELECT * FROM Partner";
	$partners = $app['db']->fetchAll($sql);
	$htmlPartners = '<table class="list"><caption><h1>合 作 伙 伴 列 表</h1>( '. count($partners) .' 条记录 )</caption>
						<tr>
							<td class="info">
								合作伙伴
							</td>
							<td class="edit">
								操作
							</td>
						</tr>';
	foreach($partners as $partner)
	{
		$htmlPartners .= '<tr>
							<td class="info">
								'. $partner['Name'] .'
							</td>
							<td class="edit">
								<a href="javascript:window.location.href=\'../admin.php/partner_edit.html
								?ID='.addcslashes($partner['ID'],"'").'
								&Name='.addcslashes($partner['Name'],"'").'
								&Resume='.addcslashes($partner['Resume'],"'").'
								&Link='.addcslashes($partner['Link'],"'").'
								&Photo='.addcslashes($partner['Photo'],"'").'\'">编辑</a>
								<a href="javascript:deleteRecord(\'Partner\', \''. $partner['ID'] .'\', \''. $partner['Photo'] .'\')">删除</a>
							</td>
						</tr>';
	}
	
	return $app['twig']->render('partner_list.html', array(
        'htmlPartners' => $htmlPartners
    ));
});

/* End Gain of informations */



/* Delete of records */

$app->post('/delete', function(Request $request) use ($app) {
	$Table = $request->get('Table');
	$ID = $request->get('ID');
	$Photo = "";
	if($Table != 'ProType')
	{
		$Photo = $request->get('Photo');
		if(file_exists($Photo))
		{
			unlink($Photo);
		}
	}
	$sql = 'DELETE FROM '. $Table .' WHERE ID = ? ';
	$result = $app['db']->executeUpdate($sql, array((int)$ID));
	
	$data = array('result' => $result);
	return $app->json($data);
});

/* End Delete of records */


/* Add information */

$app->post('/addEmployee', function(Request $request) use ($app) {
	$Name = $request->get('Name');
	$Position = $request->get('Position');
	$Quote = $request->get('Quote');
	$Resume = str_replace("&#39;", "'", $request->get('Resume'));	//数据库里的单引号必须直接显示，否则后面的替换函数识别不了。
	$Resume = str_replace('"', "'" ,$Resume);
	$Photo = $request->get('Photo');
	
	$sql = 'INSERT INTO Employee(Name, Position, Quote, Resume, Photo) VALUES(?,?,?,?,?)';
	$result = $app['db']->executeUpdate($sql, array($Name, $Position, $Quote, $Resume, $Photo));
	
	$data = array('result' => $result);
	return $app->json($data);
});

$app->post('/addProject', function(Request $request) use ($app) {
	$Name = $request->get('Name');
	$Resume = $request->get('Resume');
	$Details = str_replace("&#39;", "'", $request->get('Details'));	//数据库里的单引号必须直接显示，否则后面的替换函数识别不了。
	$Details = str_replace('"', "'" ,$Details);
	$Photo = $request->get('Photo');
	$Type = $request->get('Type');
	
	$sql = 'INSERT INTO Project(Name, Resume, Details, Photo, Time, Type) VALUES(?,?,?,?, NOW(),?)';
	$result = $app['db']->executeUpdate($sql, array($Name, $Resume, $Details, $Photo, $Type));
	
	$data = array('result' => $result);
	return $app->json($data);
});

$app->post('/addProType', function(Request $request) use ($app) {
	$Type = $request->get('Type');
	
	$sql = 'INSERT INTO ProType(Type, Level) VALUES(?,2)';
	$result = $app['db']->executeUpdate($sql, array($Type));
	
	$data = array('result' => $result);
	return $app->json($data);
});

$app->post('/addPartner', function(Request $request) use ($app) {
	$Name = $request->get('Name');
	$Resume = $request->get('Resume');
	$Link = $request->get('Link');
	$Photo = $request->get('Photo');
	
	$sql = 'INSERT INTO Partner(Name, Resume, Link, Photo) VALUES(?,?,?,?)';
	$result = $app['db']->executeUpdate($sql, array($Name, $Resume, $Link, $Photo));
	
	$data = array('result' => $result);
	return $app->json($data);
});

/* End Add information */


/* Save of informations */

$app->post('/saveCompany', function(Request $request) use ($app) {
	require_once('../../emailSender/gmailSender.php');
	
	$Title = $request->get('Title');
	$Content = str_replace("&#39;", "'", $request->get('Content'));	//数据库里的单引号必须直接显示，否则后面的替换函数识别不了。
	$Content = str_replace('"', "'" ,$Content);
	/*$Photo = $request->get('Photo');*/
	
	//Configurations
	$emailServer = 'atelier19.infos@gmail.com';//'bruce.gong.france@gmail.com';
	$pwdServer = '2014atelier19';
	$emailResto = 'lamaisondefuji@gmail.com';//'bruce.gong.tmax@gmail.com';
	$pwdResto = 'lamaisondefujihuang';
	$nicknameResto = 'La Maison de FUJI';
	$endWORD = '<br>Bien cordialement,<br><br>La maison de FUJI <br>79 Rue de la Paroisse,<br>78000 Versailles';
	
	
	
	//send second email from Resto to Client
	$Account = $emailResto;
	$Pwd = $pwdResto;
	$From = array($Account => $nicknameResto);
	//set tolist
	$sql = "SELECT distinct(email) FROM command";
	$emails = $app['db']->fetchAll($sql);
	$ToList = array();
	array_push($ToList, $Account);
	foreach($emails as $emailClient)
	{
		array_push($ToList, $emailClient['email']);
	}
		
	$Subjet = $Title;
	$Content = 'Bonjour,<br><br>'.$Content.$endWORD;
	$result = sendGmail($app, $Account, $Pwd, $From, $ToList, $Subjet, $Content);
	
	$data = array('result' => $result);
	return $app->json($data);
});

$app->post('/saveEmployee', function(Request $request) use ($app) {
	$ID = $request->get('ID');
	$Name = $request->get('Name');
	$Position = $request->get('Position');
	$Quote = $request->get('Quote');
	$Resume = str_replace("&#39;", "'", $request->get('Resume'));	//数据库里的单引号必须直接显示，否则后面的替换函数识别不了。
	$Resume = str_replace('"', "'" ,$Resume);
	$Photo = $request->get('Photo');
	
	$sql = 'UPDATE Employee SET Name = ? , Position = ? , Quote = ? , Resume = ? , Photo= ? WHERE ID= ? ';
	$result = $app['db']->executeUpdate($sql, array($Name, $Position, $Quote, $Resume, $Photo, $ID));
	
	$data = array('result' => $result);
	return $app->json($data);
});

$app->post('/saveProject', function(Request $request) use ($app) {
	$ID = $request->get('ID');
	$Name = $request->get('Name');
	$Resume = $request->get('Resume');
	$Details = str_replace("&#39;", "'", $request->get('Details'));	//数据库里的单引号必须直接显示，否则后面的替换函数识别不了。
	$Details = str_replace('"', "'" ,$Details);
	$Photo = $request->get('Photo');
	$Type = $request->get('Type');
	
	$sql = 'UPDATE Project SET Name = ? , Resume = ? , Details = ?, Photo= ? , Type = ?  WHERE ID= ? ';
	$result = $app['db']->executeUpdate($sql, array($Name, $Resume, $Details, $Photo, $Type, $ID));
	
	$data = array('result' => $result);
	return $app->json($data);
});

$app->post('/saveProType', function(Request $request) use ($app) {
	$ID = $request->get('ID');
	$Type = $request->get('Type');
	
	$sql = 'UPDATE ProType SET Type = ? WHERE ID = ? ';
	$result = $app['db']->executeUpdate($sql, array($Type, $ID));
	
	$data = array('result' => $result);
	return $app->json($data);
});

$app->post('/savePartner', function(Request $request) use ($app) {
	$ID = $request->get('ID');
	$Name = $request->get('Name');
	$Resume = $request->get('Resume');
	$Link = $request->get('Link');
	$Photo = $request->get('Photo');
	
	$sql = 'UPDATE Partner SET Name = ? , Resume = ?, Link = ? , Photo = ? WHERE ID = ? ';
	$result = $app['db']->executeUpdate($sql, array($Name, $Resume, $Link, $Photo, $ID));
	
	$data = array('result' => $result);
	return $app->json($data);
});

/* End Save of informations */



/* Functions */

$app->post('/uploadPhoto', function(Request $request) use ($app) {
    $folder = $request->get('folder');
	$errorMsg = '';
	$is_Success = 0;
	//文件存储路径
	$file_path="../images/$folder/";
	//664权限为文件属主和属组用户可读和写，其他用户只读。
	if(is_dir($file_path)!=TRUE) mkdir($file_path,0664) ;
	//定义允许上传的文件扩展名
	$ext_arr = array("jpg","png");
	
	if (empty($_FILES) === false) {
		//判断检查
		if($_FILES["file"]["size"] > 2097152){
			$errorMsg = "对不起，您上传的照片超过了2M。";
		}
		if($_FILES["file"]["error"] > 0){
			$errorMsg = "文件上传发生错误：".$_FILES["file"]["error"];
		}
	
		//获得文件扩展名
		$temp_arr = explode(".", $_FILES["file"]["name"]);
		$file_ext = array_pop($temp_arr);
		$file_ext = trim($file_ext);
		$file_ext = strtolower($file_ext);
		//检查扩展名
		if (in_array($file_ext, $ext_arr) === false) {
			$errorMsg = "上传文件扩展名是不允许的扩展名。";
		}
		//以时间戳重命名文件
		$new_name = time().".".$file_ext;
		//将文件移动到存储目录下
		move_uploaded_file($_FILES["file"]["tmp_name"],"$file_path" . $new_name);
		//向数据表写入文件存储信息以便管理
		//*********** 代码略 ***********//
		$is_Success = 1;
	} else {
		$errorMsg = "无正确的文件上传";
	}
	return '<script type=\'text/javascript\'> window.top.showPic("../'. $file_path . $new_name .'"); </script>';
});

$app->get('/getProtypes', function(Request $request) use ($app) {
	//get all the categories of design(level 2)
	$sql = "SELECT * FROM ProType";
	$types = $app['db']->fetchAll($sql);
	$htmlTypes = '';
	foreach($types as $type)
	{
		$htmlTypes .= '<option value="'. $type['Type'] .'">'. $type['Type'] .'</option>';
	}
	
	$data = array('htmlTypes' => $htmlTypes);
	return $app->json($data);
});

//这个Controller写的不好，必须放在程序的最后，当搜索完所有的Controller之后，没有得到结果时，就认为是网页跳转请求。
$app->get('/{page}', function(Application $app, Request $req, $page) use ($app) {
	//$page = $request->query('page');
    return $app->sendFile($page);
});

/* End Functions */



$app->error(function (\Exception $e, $code) use ($app) {
	if($app['debug']){
		return;
		}
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});

$app->run();
?>