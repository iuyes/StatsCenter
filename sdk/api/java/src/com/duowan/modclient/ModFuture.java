package com.duowan.modclient;

/**
 * 模块调用工具类
 */
public interface ModFuture {
	
	/**
	 * 完成任务并且push到当前的线程。
	 * @param success
	 * @param errorCode
	 */
	public void finishAndSend(boolean success, int errorCode);

}
