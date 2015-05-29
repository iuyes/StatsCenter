package com.duowan.modclient.impl;

import java.io.ByteArrayOutputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.List;
import java.util.StringTokenizer;

import com.duowan.modclient.ModClient;
import com.duowan.modclient.ModClientConfig;
import com.duowan.modclient.ModData;
import com.duowan.modclient.ModFuture;

public class SimpleModClientImpl implements ModClient {
	
	private static ThreadLocal<List<ModData>> currentModList = new ThreadLocal<List<ModData>>();
	
	//private static final Logger logger = LoggerFactory.getLogger(SimpleModClientImpl.class);
	
	private ModClientConfig config;
	
	private InetAddress serverAddr;
	
	public SimpleModClientImpl(ModClientConfig config) throws UnknownHostException {
		if (config == null) {
			throw new NullPointerException();
		}
		this.serverAddr = InetAddress.getByName(config.getServerIp());
		this.config = config;
	}

	@Override
	public void pushDataToCurrentThread(ModData data) {
		List<ModData> lst = currentModList.get();
		if (lst == null) {
			lst = new ArrayList<ModData>();
			currentModList.set(lst);
		}
		lst.add(data);
	}

	@Override
	public int sendDataOfCurrentThread() {
		List<ModData> lst = currentModList.get();
		if (lst == null || lst.isEmpty()) {
			return 0;
		}
		try {
			sentModData(lst);
		} catch (IOException e) {
			//logger.warn("sendDataOfCurrentThread error {}", e);
		}
		currentModList.remove();
		return lst.size();
	}

	@Override
	public void sentModDataImmediately(List<ModData> modList) {
		try {
			sentModData(modList);
		} catch (IOException e) {
			//logger.warn("sentModDataImmediately error {}", e);
		}
	}

	private void sentModData(List<ModData> modList) throws IOException {
		ByteArrayOutputStream baos = null;
		DataOutputStream dos = null;
		
		DatagramSocket client = new DatagramSocket();
		
		try {
			for (ModData data : modList) {
				if (baos == null) {
					baos = new ByteArrayOutputStream();
					dos = new DataOutputStream(baos);
				}
				// (1) 接口ID （4字节）
				// (2) 模块ID （4字节）
				// (3) 是否成功 （1字节，1是成功，0是失败）
				// (4) 返回码（4字节）
				// (5) IP地址 （4字节）
				// (6) 耗时（精确到毫秒）（4字节）
				// (7) 当前时间（精确到秒）（4字节）
				dos.writeInt(data.getApiId());
				dos.writeInt(config.getDefaultModId());
				dos.writeByte(data.isSuccess() ? 1 : 0);
				dos.writeInt(data.getErrorCode());
				
				//apiServerIp是允许为空的
				String apiIp = data.getApiServerIp();
				if (apiIp == null || apiIp.isEmpty()) {
					dos.writeInt(0);
				} else {
					dos.write(ipToBytes(apiIp));
				}
				
				dos.writeInt(data.getUsedTimeMs());
				dos.writeInt(data.getCurrentTimeSec());
				
				if (baos.size() > 1024) {
					byte[] b = baos.toByteArray();
					DatagramPacket sendPacket = new DatagramPacket(b, b.length, this.serverAddr, config.getServerPort());
					client.send(sendPacket);
					//logger.debug("success send udp pack, size is {}", b.length);
					//System.out.println("success send udp pack, size is {}" + b.length);
					baos = null;
					dos = null;
				}
			}
			if (baos.size() > 0) {
				byte[] b = baos.toByteArray();
				DatagramPacket sendPacket = new DatagramPacket(b, b.length, this.serverAddr, config.getServerPort());
				client.send(sendPacket);
				//logger.debug("success send udp pack, size is {}", b.length);
			}
		} finally {
			client.close();
		}
	}
	
	private byte[] ipToBytes(String ipAddr) {
		byte[] ret = new byte[4];
		StringTokenizer token = new StringTokenizer(ipAddr, ".", false);
		for (int i = 0; i < 4; i++) {
			String s = token.nextToken();
			ret[i] = (byte) (Integer.parseInt(s) & 0xFF);
		}
		return ret;
	}  

	@Override
	public void cleanCurrentThread() {
		currentModList.remove();
	}

	@Override
	public ModFuture initModFuture(int apiId, String apiServerIp) {
		return new SimpleModFutureImpl(this, apiId, apiServerIp);
	}

}
