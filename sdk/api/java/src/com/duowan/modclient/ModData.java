package com.duowan.modclient;

/**
 * 模块调用的相关统计数据
 */
public class ModData {
	
	/**
	 * 接口ID
	 */
	private int apiId;

	/**
	 * 接口是否调用成功
	 */
	private boolean success;

	/**
	 * 错误码
	 */
	private int errorCode;

	/**
	 * 被调用的API的IP
	 */
	private String apiServerIp;

	/**
	 * 接口调用耗时
	 */
	private int usedTimeMs;
	
	/**
	 * 当前时间戳，精确到秒
	 */
	private int currentTimeSec;

	public int getApiId() {
		return apiId;
	}

	public void setApiId(int apiId) {
		this.apiId = apiId;
	}

	public boolean isSuccess() {
		return success;
	}

	public void setSuccess(boolean success) {
		this.success = success;
	}

	public int getErrorCode() {
		return errorCode;
	}

	public void setErrorCode(int errorCode) {
		this.errorCode = errorCode;
	}

	public int getUsedTimeMs() {
		return usedTimeMs;
	}

	public void setUsedTimeMs(int usedTimeMs) {
		this.usedTimeMs = usedTimeMs;
	}

	public int getCurrentTimeSec() {
		return currentTimeSec;
	}

	public void setCurrentTimeSec(int currentTimeSec) {
		this.currentTimeSec = currentTimeSec;
	}

	public String getApiServerIp() {
		return apiServerIp;
	}

	public void setApiServerIp(String apiServerIp) {
		this.apiServerIp = apiServerIp;
	}

}
