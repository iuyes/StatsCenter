package com.duowan.modclient;

import java.util.List;

/**
 * 模调接口的调用
 */
public interface ModClient {

	/**
	 * 把模块调用的统计信息，推送到当前的线程数据里
	 */
	public void pushDataToCurrentThread(ModData data);
	
	/**
	 * 把当前线程数据池里的统计信息发送到远程的统计服务器，并且清空当前的数据池
	 * @return 返回数据池里的数据记录数
	 */
	public int sendDataOfCurrentThread();
	
	/**
	 * 清空数据池
	 */
	public void cleanCurrentThread();
	
	/**
	 * 立即发送统计数据到远程的统计服务器
	 * @param data
	 */
	public void sentModDataImmediately(List<ModData> modList);
	
	public ModFuture initModFuture(int apiId, String apiServerIp);
	
}
