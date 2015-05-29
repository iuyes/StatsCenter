package com.duowan.modclient;

import static org.junit.Assert.*;

import org.junit.After;
import org.junit.Before;
import org.junit.Test;

public class ModClientTester {
	
	ModClient client;

	@Before
	public void setUp() throws Exception {
		client = ModClientFactory.getInstance();
	}

	@After
	public void tearDown() throws Exception {
	}

	@Test
	public void testSendDataOfCurrentThread() {
		client.cleanCurrentThread();
		int appId = 5001806;

		for (int i = 0; i < 1000; i++) {
			ModData data = new ModData();
			data.setApiId(appId);
			data.setCurrentTimeSec((int)(System.currentTimeMillis()/1000));
			data.setErrorCode(i);
			data.setSuccess(true);
			data.setUsedTimeMs(10);
			data.setApiServerIp("192.168.2.1");
			client.pushDataToCurrentThread(data);
			
			ModData data1 = new ModData();
			data1.setApiId(appId);
			data1.setCurrentTimeSec((int)(System.currentTimeMillis()/1000));
			data1.setErrorCode(3);
			data1.setSuccess(false);
			data1.setUsedTimeMs(11);
			data1.setApiServerIp("192.168.2.2");
			client.pushDataToCurrentThread(data1);
		}
		
		client.sendDataOfCurrentThread();
	}

	@Test
	public void testSentModDataImmediately() throws InterruptedException {
		ModFuture future = client.initModFuture(5001806, "192.168.1.100");
		Thread.sleep(100);
		future.finishAndSend(true, 100);
	}

}
