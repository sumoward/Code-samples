//============================================================================
// Name        : crew.cpp
// Author      : Brian G Ward
// Date	       : 9 Apr 2011 
// Description : 
//============================================================================

#include <iostream>
#include <fstream>
using namespace std;
#include "Schedule.h"

// ///////////////////////// //
// Interface for the Job ADT
// ///////////////////////// //

AircraftId Job::jobId(Job job) const {

	return id;
}

Level Job::jobLevel(Job job) const {
	return level;
}

Time Job::jobSetStartTime(Job job, Time time) {
	job.startTime = time;
	return time;
}

int Job::jobRead(fstream& str) {
	str >> id;
	if (str.fail())
		return -1;

	str >> level;
	if (str.fail())
		return -1;

	str >> duration;
	if (str.fail())
		return -1;

	return 1;
}

Time Job::jobStartTime(Job job) const {
	return job.startTime;
}

Time Job::jobDuration(Job job) const {
	return job.duration;
}

//(a)the list of crews is read from a file called crews.list.
//int Crew::crewRead(fstream& strcrew) {
//	strcrew >> id;
//	if (str.fail())
//		return -1;
//
//	strcrew >> level;
//	if (str.fail())
//		return -1;
//
//}

//(b) the crew that can Finish the job with the least cost is selected;
//Crew Crew::jobFindCheapestCrewForJob(Job job, Crew crewlist[], int no_crews) {
//	int i;
//	Cost jobcost;//cost of the job
//	Cost minCost;//cheapest cost
//	int found;
//	int cindex;
//
//	// Find the crew that can do the job cheapest
//
//	jobcost = crewCost();
//	found = false;
//
//	for (i = 0; i < no_crews; i++) {
//		if (crewLevel(crewlist[i]) >= jobcost) {
//			if (!found || scheduleMinStartTime(crewSchedule(crewlist[i]))
//					< minCost) {
//				minCost = scheduleMinStartTime(crewSchedule(crewlist[i]));
//				found = true;
//				cindex = i;
//			}
//		}
//	}
//	return crewlist[cindex];
//}

// ////////////////////////////   //
// Interface for the Schedule ADT //
// ////////////////////////////   //

//Schedule::Schedule() {
//	noJobs = 0;
//}

// Schedule::Schedule(Schedule schedule) {
//
//}

void Schedule::scheduleAddJob(Schedule schedule, Job job) {
	job.jobSetStartTime(job, scheduleMinStartTime(schedule));
	job = schedule.jobList[schedule.noJobs++];
}

const Job Schedule::scheduleLastJob(Schedule schedule) const {
	return schedule.jobList[schedule.noJobs - 1];
}

int Schedule::scheduleNumJobs(Schedule schedule) const {
	return noJobs;
}

void Schedule::schedulePrintOut(Schedule schedule) const {
	int i;
	Time start, duration;
	AircraftId id;
	Level level;

	for (i = 0; i < schedule.noJobs; i++) {
		id = schedule.jobList[i].jobId(jobList[i]);
		start = schedule.jobList[i].jobStartTime(jobList[i]);
		duration = schedule.jobList[i].jobDuration(jobList[i]);
		level = schedule.jobList[i].jobLevel(jobList[i]);
		cout << "[Start= " << start << ", End= " << start + duration
				<< ", Level= " << level << ", Id= " << id << "]\n";
	}
}

Time Schedule::scheduleMinStartTime(Schedule schedule) const {
	Time time;

	if (scheduleNumJobs(schedule) > 0) {

		Job job = scheduleLastJob(schedule);

		time = job.jobStartTime(job) + job.jobDuration(job);

	} else
		time = 0;

	return time;

}

const Job& Schedule::scheduleJobNumber(Schedule schedule, int number) const {
	return jobList[number];
}

//(c) provide a method called validate bound to the Schedule class, that checks a schedule and returns true if it is valid and false otherwise.
//Test the program by running on a large input File and checking if the program successfully computes a valid schedule.

//	bool scheduleValidate(){
//		int i;
//			Time start, duration;
//			AircraftId id;
//			Level level;
//
//			for (i = 0; i < noJobs; i++) {
//				id = jobId(i);
//				start = jobStartTime(i);
//				duration = jobDuration(i);
//				level = jobLevel(i);
//
//				if()
//				cout << "the schedule is valid\n";
//			}
//	}

// //////////////////////////// //
// Interface for the Crew ADT   //
// //////////////////////////// //

Crew::Crew() {
	level = 0;
	cost = 0;
}

Crew::Crew(Crew crew, Level inlevel, Cost incost) {

	level = inlevel;
	cost = incost;
}

Crew Crew::jobFindCrewForJob(Job job, Crew crewlist[], int no_crews) {
	int i;
	Level jlevel;
	Time minTime;
	int found;
	int cindex;

	// Find the crew that can do the job soonest

	jlevel = job.jobLevel(job);
	found = false;

	for (i = 0; i < no_crews; i++) {
		if (crewlist[i].level >= jlevel) {
			Schedule s = crewGetSchedule();
			if (!found || (crewlist[i].schedule.scheduleMinStartTime(s))
					< minTime) {
				minTime = crewlist[i].schedule.scheduleMinStartTime(s);
				found = true;
				cindex = i;
			}
		}
	}
	return crewlist[cindex];
}

Level Crew::crewLevel(Crew crew) const {
	return crew.level;
}

void Crew::crewAddtoSchedule(Job job) {
	Schedule s = crewGetSchedule();

	s.scheduleAddJob(s,job);
}

Cost Crew::crewCost(Crew crew) const {
	return cost;
}

const Schedule Crew::crewSchedule(Crew crew) const {
	return schedule;
}

Schedule Crew::crewGetSchedule() {
	return schedule;
}

// //////////////////////////// //
// Interface for the maintenance ADT   //
// //////////////////////////// //

MaintenanceList::MaintenanceList() {
	noJobs = 0;
}

void MaintenanceList::maintenanceListAddJob(Job job) {
	jobList[noJobs++] = job;
}

const Job MaintenanceList::maintenanceListJobNumber(int jobNumber) {
	return jobList[jobNumber];
}
