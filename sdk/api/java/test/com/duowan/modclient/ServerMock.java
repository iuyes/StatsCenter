package com.duowan.modclient;

import java.io.IOException;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetSocketAddress;
import java.nio.ByteBuffer;
import java.sql.Date;
import java.text.SimpleDateFormat;

public class ServerMock {
	
	public static String bytesToIp(byte[] bytes) {   
        return new StringBuffer().append(bytes[0] & 0xFF).append('.').append(   
                bytes[1] & 0xFF).append('.').append(bytes[2] & 0xFF)   
                .append('.').append(bytes[3] & 0xFF).toString();   
    } 
	
	public static void main(String[] args) throws IOException {
		DatagramSocket server = new DatagramSocket(new InetSocketAddress(9903));
		byte[] bs = new byte[25];
		byte[] oneByte = new byte[1];
		byte[] byte4 = new byte[4];
		ByteBuffer bbuf = null;
		DatagramPacket data = new DatagramPacket(bs, bs.length);
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		//server.setSoTimeout(1000 * 10); // set timeout
		while (true) { // 一直监听
			server.receive(data);
			System.out.println("==================================");
			bbuf = ByteBuffer.wrap(bs, 0, bs.length);
			System.out.println("apiId=" + bbuf.getInt());
			System.out.println("modId=" + bbuf.getInt());
			bbuf.get(oneByte);
			System.out.println("successFlag=" + (int)oneByte[0]);
			System.out.println("errorCode=" + bbuf.getInt());
			bbuf.get(byte4);
			System.out.println("serverIp=" + bytesToIp(byte4));
			System.out.println("usedTime(ms)=" + bbuf.getInt());
			int n = bbuf.getInt();
			Date d = new Date(n * 1000l);
			System.out.println("current(sec)=" + sdf.format(d));
		}
	}

}
