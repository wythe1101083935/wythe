<?php
/**
 +----------------------------------------------------------
 * 设置框架文件目录和加载配置文件
 +----------------------------------------------------------
 * CODE:
 +----------------------------------------------------------
 * TIME:2018-08-31 10:34:3 +----------------------------------------------------------
 * author:wythe(汪志虹)
 +----------------------------------------------------------
 */
namespace Wythe\System;
use \ArrayAccess;
class Config implements ArrayAccess{
	protected $config = [];

	protected $configFilePath = '';

	public $wytheShared = true;

	public function __construct($configPath){
		$this->configFilePath = $configPath;
		/*1.加载环境变量*/
		//$this->loadEnv();
		/*2.加载配置文件*/
		$this->loadConfig();
	}

	/*加载环境变量*/
	protected function loadEnv(){

	}

	/*加载配置文件*/
	protected function loadConfig(){
		/*1.判断配置文件是否有所修改*/

		/*2.如果有修改，重新生成缓存配置文件*/

		/*3.加载缓存配置文件*/
		$this->loadCachedConfig();
	}

	/*加载缓存配置文件*/
	protected function loadCachedConfig(){
		if(is_file($this->configFilePath)){
			$this->config = include $this->configFilePath;
		}else{
			/*DEBUG EXCEPTION*/
		}
	}

	public function offsetExists($key){
		return isset($this->config[$key]);
	}

	public function offsetGet($key){
		return $this->config[$key];	
	}

	public function offsetSet($key,$value){
		$this->config[$key] = $value;
	}

	public function offsetUnset($key){
		unset($this->config[$key]);
	}
}
