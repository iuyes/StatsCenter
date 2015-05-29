package com.duowan.modclient;

import java.io.FileReader;
import java.io.IOException;
import java.util.Properties;

import com.duowan.modclient.impl.SimpleModClientImpl;

public class ModClientFactory {
	
	private static ModClient client = null;
	
	private static final String CONF_FILE_NAME = "/etc/modclient/ModClient.conf";
	
	static {
		try {
			client = new SimpleModClientImpl(readConf());
		} catch (Exception e) {
			e.printStackTrace();
			//you can custom your log here
		}
	}
	
	private static ModClientConfig readConf() throws IOException {
		FileReader fr = new FileReader(CONF_FILE_NAME);
		try {
			Properties p = new Properties();
			p.load(fr);
			ModClientConfig config = new ModClientConfig();
			config.setServerIp(p.getProperty("serverIp"));
			config.setServerPort(Integer.parseInt(p.getProperty("serverPort")));
			config.setDefaultModId(Integer.parseInt(p.getProperty("defaultModId")));
			// System.out.println("load config " + config);
			return config;
		} finally {
			fr.close();
		}
	}
	
	public static ModClient getInstance() {
		return client;
	}

}
