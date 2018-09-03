<?php

/**
 +----------------------------------------------------------
 * 应用容器
 +----------------------------------------------------------
 * CODE:
 +----------------------------------------------------------
 * TIME:2018-08-29 22:59:54
 +----------------------------------------------------------
 * author:wythe(汪志虹)
 +----------------------------------------------------------
 */
namespace Wythe\System;
use \Closure;
use \ReflectionParameter;
use \ReflectionClass;
class Container{
	/*所有实例化的接口*/
	protected $instances = [];

	/*绑定的类名*/
	protected $bindings = [];

	/*参数栈*/
	protected $with = [];

	/*
	 +----------------------------------------------------------
	 * 绑定
	 +----------------------------------------------------------
	 * @param  $abstract 要绑定的全类名 string;
	 * @param  $shared  要创建的对象是否为单态;
	 * @param  $concrete 默认为空false,用来直接创建对象;
	 +----------------------------------------------------------
	 * @return json{status:bool,msg:string,data:mix,code:int}
	 +----------------------------------------------------------
	*/
	public function bind($abstract,$concrete = null){
		/*如果concrete是空的，那么表示直接创建对象*/
		if(is_null($concrete)){
			$concrete = $abstract;
		}
		/*注册到绑定中去*/
		$this->bindings[$abstract] = compact('concrete','shared');
	}

	/*
	 +----------------------------------------------------------
	 * 实例化一个对象
	 +----------------------------------------------------------
	 * @param  $abstract 实例化的对象全类名;
	 * @param  $parameters 创建需要的额外参数;
	 +----------------------------------------------------------
	 * @return json{status:bool,msg:string,data:mix,code:int}
	 +----------------------------------------------------------
	*/
	public function make($abstract,$parameters = []){
		/*如果已经存在实例直接返回*/
		if(isset($this->instances[$abstract])){
			return $this->instances[$abstract];
		/*创建实例*/
		}else{
			/*存入参数*/
			$this->with[] = $parameters;
			if(isset($this->bindings[$abstract])){
				$concrete = $this->bindings[$abstract]['concrete'];
			}else{
				$concrete = $abstract;
			}
			/*如果闭包存在，则直接使用闭包创建*/
			if($concrete instanceof Closure){
				$object = call_user_func_array(self,$parameters);
			/*如果不是闭包，实例化这个类，自动注入依赖*/
			}else{
				$object = $this->build($concrete);
			}
			return $object;
		}
	}

	/*
	 +----------------------------------------------------------
	 * 循环实例化有依赖的对象
	 +----------------------------------------------------------
	 * @param  ;
	 +----------------------------------------------------------
	 * @return json{status:bool,msg:string,data:mix,code:int}
	 +----------------------------------------------------------
	*/
	protected function build($abstract){
		$reflector = new ReflectionClass($abstract);
		$constructor = $reflector->getConstructor();

		if(is_null($constructor)){
			$object = new $abstract;
		}else{
			/*获取参数和依赖*/
			$dependencies = $constructor->getParameters();
			$instances = $this->resolveDependencies($dependencies);
			/*根据参数实例化对象*/
			$object = $reflector->newInstanceArgs($instances);
		}
		/*如果是持续保存的，存入*/
		if(isset($object->wytheShared) && $object->wytheShared){
			$this->instances[$abstract] = $object;
		}
		return $object;
	}

	/*
	 +----------------------------------------------------------
	 * 获取参数和依赖
	 +----------------------------------------------------------
	 * @param  ;
	 +----------------------------------------------------------
	 * @return json{status:bool,msg:string,data:mix,code:int}
	 +----------------------------------------------------------
	*/
	protected function resolveDependencies(array $dependencies){
		$results = [];
		foreach ($dependencies as $dependency) {
			/*如果参数不是一个对象依赖*/
			if(is_null($dependency->getClass())){
				/*如果传入了参数*/
				if(isset($this->$with[$dependency->name])){
					$results[] = $this->$with[$dependency->name];
				/*如果参数有默认值*/
				}elseif($dependency->isDefaultValueAvailable()){
					$results[] = $dependency->getDefaultValue();
				}else{
					/*DEBUG 报错*/
				}
			/*如果参数是一个对象*/
			}else{
				$results[] = $this->build($dependency->getClass()->name);
			}
		}
		return $results;
	}
}
