package com.duowan.modclient;

public class ModClientConfig {
	
	private String serverIp;
	
	private int serverPort;
	
	private int defaultModId;
	
	@Override
	public String toString() {
		return "ModClientConfig{"
				+ "serverIp=" + serverIp + ","
				+ "serverPort=" + serverPort + ","
				+ "defaultModId=" + defaultModId + "}";
	}

	public String getServerIp() {
		return serverIp;
	}

	public void setServerIp(String serverIp) {
		this.serverIp = serverIp;
	}

	public int getServerPort() {
		return serverPort;
	}

	public void setServerPort(int serverPort) {
		this.serverPort = serverPort;
	}

	public int getDefaultModId() {
		return defaultModId;
	}

	public void setDefaultModId(int defaultModId) {
		this.defaultModId = defaultModId;
	}

}
