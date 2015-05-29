#ifndef MOSTAT_HH_
#define MOSTAT_HH_

#include <arpa/inet.h>
#include <netinet/in.h>
#include <sys/socket.h>
#include <sys/time.h>
#include <unistd.h>
#include <assert.h>
#include <string>

#define STAT_PKG_LEN 25
#define STAT_PKG_NUM 58
#define SEND_PKG_BUFFER 8192

#define STAT_SERVER "127.0.0.1"
#define STAT_PORT 9903

using namespace std;
//Ninterface_id/Nmodule_id/Csuccess/Nret_code/Nserver_ip/Nuse_ms/Naddtime

#pragma pack(1)
typedef struct
{
	uint32_t interface_id;
	uint32_t module_id;
	uint8_t success;
	uint32_t ret_code;
	uint32_t server_ip;
	uint32_t use_ms;
	uint32_t addtime;
} MostatPkg;
#pragma pack()

class StatsCenter
{
protected:
	uint32_t module_id;
	uint32_t interface_id;
	static const int pkgLen = 25;
	uint32_t pkgNum;
	char buf[SEND_PKG_BUFFER];

	string host;
	int port;
	long start_ms;

public:
	int fd;
	struct sockaddr_in addr;

	StatsCenter(int module_id);
	~StatsCenter();
	bool connect(void);

	bool tick(int interface_id);
	bool report(bool sucess, int retCode, string& serverIp);;

	void sendPkg(void);
	uint32_t in_s2n(string &strIP);
	long getCurrentTime();
};

#endif /* MOSTAT_HH_ */