<?php

xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */

/**
	区分开发模式.但是在实际开发中,从线下开发和上线都需要修改这个常量,还是有些麻烦;
	可以采用在nginx里设置$_SERVER变量的方式.来进行处理:
		1.fastcgi_param HOST_URL 'www.liwenrui.com';(不过据说配置信息通过nginx的fastcgi_param来设置的话，当nginx和php交互时，会带来大量的数据传输。)
		2.env[HOST_URL] = www.liwenrui.com (这个设置必须放在主配置文件php-fpm.conf里)
 */

	define('ENVIRONMENT', 'development');
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

/**
	对不同的环境应用不同的错误级别,也还可以加入一些其他的需要判定的设置
 */
if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
		break;

		case 'testing':
		/**
			这里原来ci没有.有点小洁癖就给加上了.因为swithch里只有碰见break才会跳出switch,否则就会执行下面的case(不会管case值是否符合了)
			例如:
			$a = '1';
			switch($a)
			{
				case '1':
					echo '1111';
				case '2';
					echo '2222';
				default:
					echo '3333';
			}
			实际输出会是 111122223333
		 */
		break;
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}
else
{
	/**
		原来的ci里面没有这个else处理.如有没有定义ENVIRONMENT常量,也应该报错,停止脚本
	 */
	exit('The application environment is not set correctly.');
}

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
	/**
		系统文件夹名称.
	 */
	$system_path = 'system';
/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
	/**
		应用文件夹名称
	 */
	$application_folder = 'application';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
	// The directory name, relative to the "controllers" folder.  Leave blank
	// if your controller is not in a sub-folder within the "controllers" folder
	/**
		指定controllers下访问的目录
		如设置为index.则查找的目录为application/controllers/index/
		如设置为admin.则查找的目录为application/controllers/admin/
		可在index.php入口里设置成index;在admin.php入口里设置成admin
	 */
	// $routing['directory'] = '';

	// The controller class file name.  Example:  Mycontroller
	/**
		指定默认的控制器.如果这里不设置则为routes.php默认设置的welcome值
		如果这里和routes.php里都设置了以这里的为主(优先)
		另外:
			如果这里设置为一个不存在的,即使routes.php设置正确,也会引起致命错误.
			如果这里设置正确,routes.php里设置错误,会引起404 Page Not Found.
			这里的控制器名称不要设置成index.否则执行两次index()方法
			@todo 以后查一下为什么会执行2次index()方法 
	 */
	// $routing['controller'] = '';

	// The controller function you wish to be called.
	/**
		指定默认的方法
	 */
	// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
	/**
		@todo 后续看一下
	 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));	//改变到当前目录
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';		//存在这个目录，尾部斜杠
	}

	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';		//确保尾部加斜杠

	// Is the system path correct?
	if ( ! is_dir($system_path))						//如果不存在这个目录，或者不识别，报错
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	/**
		一些常量的定义
	 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */
/**
	调用CodeIgniter.php文件进入系统引导程序
 */
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */

//xhprof性能测试工具
$data = xhprof_disable();

include_once "/Applications/XAMPP/xamppfiles/htdocs/xhprof/xhprof_lib/utils/xhprof_lib.php";
include_once "/Applications/XAMPP/xamppfiles/htdocs/xhprof/xhprof_lib/utils/xhprof_runs.php";

$objXhprofRun = new XHProfRuns_Default();
// 第一个参数j是xhprof_disable()函数返回的运行信息
// 第二个参数是自定义的命名空间字符串(任意字符串),
// 返回运行ID,用这个ID查看相关的运行结果
$run_id = $objXhprofRun->save_run($data, "xhprof");
