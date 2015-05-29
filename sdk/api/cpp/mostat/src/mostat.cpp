#include "mostat.hh"
#include <time.h>
#include <string.h>
#include <iostream>

StatsCenter::StatsCenter(int module_id)
{
	this->module_id = module_id;
	host = STAT_SERVER;
	port = STAT_PORT;
}

StatsCenter::~StatsCenter(void)
{
	if (pkgNum > 0)
	{
		sendPkg();
	}
	close(fd);
}

bool StatsCenter::connect(void)
{
	struct sockaddr_in address;

	bzero(&address, sizeof(address));
	address.sin_family = AF_INET;
	address.sin_addr.s_addr = inet_addr(host.c_str());
	address.sin_port = htons(port);
	fd = socket(AF_INET, SOCK_DGRAM, 0);
	addr = address;

	return true;
}

bool StatsCenter::tick(int interface_id)
{
	this->interface_id = interface_id;
	this->start_ms = getCurrentTime();
	return true;
}

bool StatsCenter::report(bool sucess, int retCode, string& callIp)
{
	//NNCNNNN
	MostatPkg pkg;
	bzero(&pkg, sizeof(pkg));

	//接口id
	pkg.interface_id = (uint32_t) htonl(interface_id);
	pkg.module_id = (uint32_t) htonl(module_id);
	pkg.success = (uint8_t) sucess;
	pkg.ret_code = (uint32_t) htonl(retCode);
	pkg.server_ip = in_s2n(callIp);
	long end_ms;
	end_ms = getCurrentTime();
	pkg.use_ms = (uint32_t) htonl(end_ms-start_ms);
	time_t timeval;
	timeval=time(NULL);
	pkg.addtime = (uint32_t) htonl(timeval);
	memcpy(buf + pkgNum * pkgLen, &pkg, pkgLen);
	pkgNum++;

	if (pkgNum >= STAT_PKG_NUM)
	{
		sendPkg();
	}
	return true;
}

void StatsCenter::sendPkg(void)
{
	sendto(fd, buf, pkgNum * pkgLen, 0, (struct sockaddr *) &addr, sizeof(addr));
	pkgNum = 0;
}

uint32_t StatsCenter::in_s2n(string &strIP)
{
	struct in_addr sinaddr;
	int ret = inet_pton(AF_INET, strIP.c_str(), &sinaddr);
	if (ret <= 0)
	{
		return -1;
	}
	else
	{
		return sinaddr.s_addr;
	}
}

long StatsCenter::getCurrentTime()
{
   struct timeval tv;
   gettimeofday(&tv,NULL);
   return tv.tv_sec * 1000 + tv.tv_usec / 1000;
}

