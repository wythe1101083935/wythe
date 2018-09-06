<?php
/**
 +----------------------------------------------------------
 * 处理http请求
 +----------------------------------------------------------
 * CODE:
 +----------------------------------------------------------
 * TIME:2018-09-04 09:56:30
 +----------------------------------------------------------
 * author:wythe(汪志虹)
 +----------------------------------------------------------
 */
 namespace Wythe\System;
 class Pipeline{
 	/*请求*/
 	protected $request;

 	/*管道*/
 	protected $pipeline;

 	/*指定调用的管道方法*/
 	protected $method = 'handle';

 	protected function createPipeline(){
 		return array_reduce($this->pipeline,function($last,$next){
 			return function($request) use ($last,$next){
 				if(is_callable($next)){
 					return $next($last($request));
 				}elseif(is_object($next)){
 					return $next->{$this->method}($last($request));
 				}else{
 					return $last($request);
 				}
 			}
 		});
 	}

 	public function send($request){
 		$this->request = $request;
 		return $this;
 	}

 	public function through(array $pipeline,$first=false){
 		if($first !== false){
 			$pipeline = 	array_unshift($pipeline,$first);
 		}
 		$this->pipeline = $pipeline;
 		return $this;
 	}

 	public function then(){
 		$pipeline = $this->createPipeline();
 		return $pipeline($this->request);
 	}

 }