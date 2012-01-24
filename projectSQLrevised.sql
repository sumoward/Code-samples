-- sql Commands to set up a database for amateur dramatics
SET SERVEROUTPUT ON;--turn on server output

--drop tables in the appropriate order
DROP TABLE winners;
DROP TABLE Festivals;
DROP TABLE Awards;
DROP TABLE Casting;
DROP TABLE Accounts;
DROP TABLE adjudicator;
DROP TABLE Job;
DROP TABLE Member;
DROP TABLE Play;

--create the tables
CREATE TABLE Play (
  HmPlyID NUMBER(20),
  Play_name VARCHAR2(45),
  Author VARCHAR2(45),
  PRIMARY KEY(HmPlyID)
);

CREATE TABLE Member (
  idMember NUMBER(20),
  FirstName VARCHAR2(45),
  Surname VARCHAR2(45),
  Address VARCHAR2(20),
  PhoneNo VARCHAR2(25),
  PRIMARY KEY(idMember)
);

CREATE TABLE Job (
  JobId NUMBER(20),
  jobname varchar(45),
PRIMARY KEY(JobId)
);

CREATE TABLE adjudicator (
  idadjudicator NUMBER(20),
  Name NVARCHAR2(45),
  PRIMARY KEY(idadjudicator)
);

CREATE TABLE Accounts (
  Play_HmPlyID NUMBER(20),
  income NUMBER(20),
  expenditure NUMBER(20)  ,
  PRIMARY KEY(Play_HmPlyID),
  FOREIGN KEY(Play_HmPlyID)
    REFERENCES Play(HmPlyID)
);

CREATE TABLE Casting (
  Members_idMember NUMBER(20),
  Play_HmPlyID NUMBER(20),
  Job_JobId NUMBER(20),
  pDate DATE,
  PRIMARY KEY(Members_idMember, Play_HmPlyID, Job_JobId),
  FOREIGN KEY(Members_idMember)
    REFERENCES Member(idMember),
  FOREIGN KEY(Play_HmPlyID)
    REFERENCES Play(HmPlyID),
  FOREIGN KEY(Job_JobId)
    REFERENCES Job(JobId)  
);

CREATE TABLE Awards (
  idAwards NUMBER(20),
  Cstng_Jb_JobId NUMBER(20),
  Csting_Ply_HmPlyID NUMBER(20),
  Casting_Member_idMember NUMBER(20),
  Award_Name VARCHAR2(45),
  PRIMARY KEY(idAwards),
  FOREIGN KEY(Casting_Member_idMember, Csting_Ply_HmPlyID, Cstng_Jb_JobId)
    REFERENCES Casting(Members_idMember, Play_HmPlyID, Job_JobId)
);

CREATE TABLE Festivals (
  idFstvls NUMBER(20),
  Csting_Ply_HmPlyID NUMBER(20),
  Casting_Member_idMember NUMBER(20),
  Cstng_Jb_JobId NUMBER(20),
  adjudicator_idadjudicator NUMBER(20),
  Festival_name VARCHAR2(45),
  Festivaldate DATE,
  PRIMARY KEY(idFstvls, Csting_Ply_HmPlyID, Casting_Member_idMember, Cstng_Jb_JobId),
  FOREIGN KEY(adjudicator_idadjudicator)
    REFERENCES adjudicator(idadjudicator),
  FOREIGN KEY(Casting_Member_idMember, Csting_Ply_HmPlyID, Cstng_Jb_JobId)
    REFERENCES Casting(Members_idMember, Play_HmPlyID, Job_JobId)
    );

CREATE TABLE winners (
  Fstvl_Casting_Member_idMember NUMBER(20),
  Festival_Csting_Ply_HmPlyID NUMBER(20),
  Awards_idAwards NUMBER(20),
  Festivals_idFstvls NUMBER(20),
  PRIMARY KEY(Fstvl_Casting_Member_idMember,Festival_Csting_Ply_HmPlyID,Festivals_idFstvls,Awards_idAwards),
  FOREIGN KEY(Awards_idAwards)
    REFERENCES Awards(idAwards)
);

--create an Index for members

Create Index idx_member ON member(surname);


--populate the tables
--for play
INSERT INTO Play VALUES (1,'The Beauty Queen of Lehnane','Enda McDonagh');
INSERT INTO Play VALUES (2,'Shining City','Conor McPherson');
INSERT INTO Play VALUES (3,'The Chasitiute','J B Keane');
INSERT INTO Play VALUES (4,'The History Boys','Alan Bennett');
INSERT INTO Play VALUES (5,'The Seafarer','Conor McPherson');
INSERT INTO Play VALUES (6,'Angels in America','');

--for Member
INSERT INTO Member VALUES (1,'Gus','Ward','Kivvy','39626');
INSERT INTO Member VALUES (2,'Ronan','Ward','Kivvy','49626');
INSERT INTO Member VALUES (3,'Seamus','ORourke','Aughvas','739626');
INSERT INTO Member VALUES (4,'Ann','Kiernan','Aughvas','3562f6');
INSERT INTO Member VALUES (5,'Maura','McGuinneas','Arva','356256');
INSERT INTO Member VALUES (6,'Killian','','Kivvy','356777');
-- for adjudicator
INSERT INTO adjudicator VALUES (1,'Barry Cassins');
INSERT INTO adjudicator VALUES (2,'Marty Phelan');
INSERT INTO adjudicator VALUES (3,'Elena Magno');
INSERT INTO adjudicator VALUES (4,'Colm Nolan');
INSERT INTO adjudicator VALUES (5,'Viorel Horselcu');

--for Accounts
INSERT INTO Accounts VALUES (1,10000, 5000);
INSERT INTO Accounts VALUES (2,9000, 6000);
INSERT INTO Accounts VALUES (3,15000, 3000);
INSERT INTO Accounts VALUES (4,12000, 2000);
INSERT INTO Accounts VALUES (5,11000, 5000);
--job
INSERT INTO Job VALUES (1,'Director');
INSERT INTO Job VALUES (2,'Cast');
INSERT INTO Job VALUES (3,'crew - lighting');
INSERT INTO Job VALUES (4,'crew - sound');
INSERT INTO Job VALUES (5,'crew- backstage');

--casting
INSERT INTO Casting VALUES (1,1,1,'10-may-2011');
INSERT INTO Casting VALUES (2,2,2,'11-may-2011');
INSERT INTO Casting VALUES (3,3,3,'10-may-2011');
INSERT INTO Casting VALUES (4,4,4,'10-may-2011');
INSERT INTO Casting VALUES (5,5,5,'10-may-2011');

--Festivals
INSERT INTO Festivals VALUES (1,1,1,1,1,'Athlone','10-may-2011');
INSERT INTO Festivals VALUES (2,2,2,2,2,'Shercock','11-may-2011');
INSERT INTO Festivals VALUES (3,3,3,3,3,'Cavan','12-may-2009');
INSERT INTO Festivals VALUES (4,4,4,4,4,'Cootehill','15-may-2010');
INSERT INTO Festivals VALUES (5,5,5,5,5,'Ballinamore','20-may-2010');

--awards
INSERT INTO Awards VALUES (1,1,1,1,'Best Actor');
INSERT INTO Awards VALUES (2,2,2,2,'Best Set');
INSERT INTO Awards VALUES (3,3,3,3,'Best Play');
INSERT INTO Awards VALUES (4,4,4,4,'Best Director');
INSERT INTO Awards VALUES (5,5,5,5,'Best Lighting');

--winners
INSERT INTO winners VALUES (1,1,1,1);
INSERT INTO winners VALUES (2,2,2,2);
INSERT INTO winners VALUES (4,4,4,4);
INSERT INTO winners VALUES (5,5,5,5);
INSERT INTO winners VALUES (3,3,3,3);

--Inner Join queries

--Number 1 --Get the Nmaes of the adjudicators and the festivals they judged
SELECT  adjudicator.name, Festivals.Festival_name
FROM adjudicator
JOIN Festivals
ON idadjudicator=adjudicator_idadjudicator;

--Number 2 -- get the income for each play
SELECT  play.Play_name, accounts.income
FROM play
JOIN accounts
ON HmPlyID=Play_HmPlyID;

--Number 3 -- Get the names of plays and the awards they recieved
SELECT play.play_name, Awards.Award_name
FROM Play
JOIN Awards
ON HmPlyID=Csting_Ply_HmPlyID;

--Number 4 -- find the awards one at each festival
SELECT Festivals.Festival_name, Awards.Award_name
FROM Festivals
JOIN Awards
ON Festivals.Csting_Ply_HmPlyID=Awards.Csting_Ply_HmPlyID;

---------OUTER JOIN QUERIES-------

--Left Outer Join--Number 1 When were plays last performed
SELECT Play.Play_name, casting.pDate
FROM casting
left outer JOIN play
ON play_HmPlyID=HmPlyID;

--Left Outer Join--Number 2 - What member won which award 
SELECT Awards.award_Name, Member.firstname, Member.Surname
FROM Awards
left outer JOIN member
ON idawards=idmember;

--right outer join--Number 1 get the adjudicator id for each award given
SELECT  festivals.adjudicator_idadjudicator, awards.award_name
FROM festivals
RIGHT OUTER JOIN awards
ON festivals.Csting_Ply_HmPlyID= awards.Csting_Ply_HmPlyID;

--right outer join -- Number 2 The amount that each play has earned and the total takings 

SELECT  Play_name,
SUM(income)
FROM play
Right outer JOIN accounts
ON HmPlyID=Play_HmPlyID
GROUP BY (Play_name);

 --FULL Outer join Number 1 -- list the Job skills of members by their ID Id No.
SELECT  Members_idMember,jobname
FROM Casting
FULL OUTER JOIN Job
ON Job_JobId=JobId;

 --FULL Outer join Number 2 --List the members By their Job ID
 
SELECT Job_JobId, Firstname, surname
FROM Casting
FULL OUTER JOIN member
ON Members_idMember=idMember;
 
 ------CUBE QUERY-- to see what Play and Author took in the most income

SELECT  Play_name, author,
SUM(income)
FROM play
JOIN accounts
ON HmPlyID=Play_HmPlyID
GROUP BY CUBE(Play_name, author);
 

----5 SUBQUERIES

-- Number 1: When was the Seafarer last performed
SELECT Play.Play_name, casting.pDate
FROM casting
left outer JOIN play
ON play_HmPlyID=HmPlyID
WHERE Play.Play_name = 'The Seafarer';

--  Number 2: get the expenditure for Shining city

SELECT  play.Play_name, accounts.expenditure
FROM play
JOIN accounts
ON HmPlyID=Play_HmPlyID
WHERE play.Play_name = 'Shining City';

--Number 3: Get the contact details for each member from Kivvy

SELECT  firstname, Surname, phoneno
FROM Member
where address='Kivvy';

-- Number 4:-- What was the Award at the Cavan festival
SELECT Festivals.Festival_name, Awards.Award_name
FROM Festivals
JOIN Awards
ON Festivals.Csting_Ply_HmPlyID=Awards.Csting_Ply_HmPlyID
WHERE Festivals.Festival_name = 'Cavan';

--Number 5:  - What member won the award for best lighting?
SELECT Awards.award_Name, Member.firstname, Member.Surname
FROM Awards
left outer JOIN member
ON idawards=idmember
Where Awards.award_Name='Best Lighting';

--Number 6: -- Get the total expenditure for all playes
SELECT SUM(expenditure)
FROM accounts;

--Number 7: -- Get the average income for all playes
SELECT AVG(income)
 FROM accounts;







