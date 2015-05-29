package com.duowan.modclient.impl;

import java.util.ArrayList;
import java.util.List;

import com.duowan.modclient.ModData;
import com.duowan.modclient.ModFuture;

/**
 * 模块调用工具类
 */
public class SimpleModFutureImpl implements ModFuture {
	
	private SimpleModClientImpl client;
	
	private int apiId;
	
	private String apiServerIp;
	
	private long last;

	public SimpleModFutureImpl(SimpleModClientImpl client, int apiId, String apiServerIp) {
		this.client = client;
		this.apiId = apiId;
		this.apiServerIp = apiServerIp;
		this.last = System.currentTimeMillis();
	}

	@Override
	public void finishAndSend(boolean success, int errorCode) {
		ModData data = new ModData();
		long cur = System.currentTimeMillis();
		int usedTimeMs = (int)(cur - this.last);
		data.setApiId(this.apiId);
		data.setApiServerIp(this.apiServerIp);
		data.setCurrentTimeSec((int) (cur/1000));
		data.setErrorCode(errorCode);
		data.setSuccess(success);
		data.setUsedTimeMs(usedTimeMs);
		
		List<ModData> list = new ArrayList<ModData>(1);
		list.add(data);
		
		this.client.sentModDataImmediately(list);
	}

}
