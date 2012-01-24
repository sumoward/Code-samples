//============================================================================
// Name        : program19.cpp
// Author      : Brian G Ward
// Date	       : 9 Apr 2011 
// Description : 
//============================================================================
#include <iostream>
#include <fstream>
using namespace std;
#include "Schedule.h"

int main() {

	const int numCrews = 3;
	int numJobs;
	int i;
	MaintenanceList mlist();
	Crew crewlist[numCrews];
	Job job;
	fstream str("jobs.list", ios::in);

	for (i = 0; i < numCrews; i++) {
		Crew(crewlist[i], i + 1, 100 * (i + 1));
	}

	numJobs = 0;
	while (job.jobRead(str) != -1) {
		numJobs++;
		mlist.maintenanceListAddJob(job);
	}

	for (i = 0; i < numJobs; i++) {

		job = mlist.maintenanceListJobNumber(i);

		Crew crew = crew.jobFindCrewForJob(job, crewlist, numCrews);

		crew.crewAddtoSchedule(job);
	}

	for (i = 0; i < numCrews; i++) {
		cout << "Crew " << i << "\n";
		//schedulePrintOut(crewSchedule(crewlist[i]));
		crewlist[i].Schedule.schedulePrintOut(crewlist[i]);


		//schedulePrintOut (crewlist[i].schedule);
		//schedule input, cant be called alone

	}

}
