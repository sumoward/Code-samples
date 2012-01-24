// a class for airline scheduling program

#define true 1
#define false 0

typedef int AircraftId;
typedef int Time;
typedef int Level;
typedef int Cost;

const int MaxJobs = 100;
const int MaxListSize = 100;

class Job;
class Schedule;
class Crew;
class MaintenanceList;

//create a Job class
class Job {

private:

	AircraftId id;
	Level level;
	Time startTime;
	Time duration;

public:

	Job();

	int jobRead(fstream& str);
	Level jobLevel(Job job) const;
	Time jobStartTime(Job job) const;
	AircraftId jobId(Job job) const;
	Time jobDuration(Job job) const;
	Time jobSetStartTime(Job job, Time time);
};
//new class called schedule

class Schedule {

private:
	int noJobs;
	Job jobList[MaxJobs];

public:

	//Schedule();

	int scheduleNumJobs(Schedule schedule) const;
	const Job scheduleLastJob(Schedule schedule) const;
	void scheduleAddJob(Schedule schedule, Job job);
	void schedulePrintOut(Schedule schedule) const;
	Time scheduleMinStartTime(Schedule schedule) const;
	const Job& scheduleJobNumber(Schedule schedule, int number) const;

	//(c) provide a method called validate bound to the Schedule class, that checks a schedule and returns true if it is valid and false otherwise.
	//Test the program by running on a large input File and checking if the program successfully computes a valid schedule.

	//bool scheduleValidate();

};

class Crew {

private:
	Level level;
	Cost cost;
	Schedule schedule;

public:

	Crew();
	Crew(Crew crew, Level inlevel, Cost incost);

	Crew jobFindCrewForJob(Job job, Crew Crewlist[], int no_crews);

	void crewAddtoSchedule(Job job);
	Level crewLevel(Crew crew) const;
	const Schedule crewSchedule(Crew crew) const;
	Schedule crewGetSchedule();
	Cost crewCost(Crew crew) const;

	//(a)the list of crews is read from a file called crews.list.
	//int crewRead(fstream& strcrew);

	//(b) the crew that can nish the job with the least cost is selected;
	//Crew jobFindCheapestCrewForJob(Job job, Crew crewlist[], int no_crews);


};

class MaintenanceList {

private:
	int noJobs;
	Job jobList[MaxListSize];
public:

	MaintenanceList();
	const Job maintenanceListJobNumber(int jobNumber);
	void maintenanceListAddJob(Job job);

};

