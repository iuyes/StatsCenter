#include "mostat.hh"

using namespace std;

int main(int argc, char **argv)
{
	StatsCenter *sc = new StatsCenter(1000238);
	sc->connect();

	string serverip = "127.0.0.1";

	for (int i = 0; i < 1; i++)
	{
		sc->tick(5000372);
		usleep(200000);
		sc->report(true, 1001, serverip);
	}
	delete sc;
}
