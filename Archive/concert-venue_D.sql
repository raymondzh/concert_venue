drop table Meet;
drop table Protect;
drop table Watch;
drop table VIP;
drop table GeneralAdmission;
drop table Attendee_Seat_1;
drop table Attendee_Seat_2;
drop table Assist;
drop table In_Charge_Of;
drop table Security;
drop table Technician;
drop table Volunteer;
drop table Staff;
drop table SeatingSection;
drop table Performance_1;
drop table Travel_Entourage;
drop table Performer;
drop table Performance_2;
drop table EntertainmentCompany;

CREATE TABLE SeatingSection(
	sectnum int,
	price int,
	PRIMARY KEY (sectnum));
 
grant select on SeatingSection to public;

CREATE TABLE Attendee_Seat_2(
	name char(20),
	cardnum int,
	sectnum int NOT NULL,
	seatnum int NOT NULL,
	PRIMARY KEY (cardnum),
	FOREIGN KEY (sectnum) REFERENCES SeatingSection
	ON DELETE CASCADE);

grant select on Attendee_Seat_2 to public;

CREATE TABLE Attendee_Seat_1(
	resnum int,
	cardnum int NOT NULL,
	PRIMARY KEY (resnum),
	FOREIGN KEY (cardnum) REFERENCES Attendee_Seat_2
	ON DELETE CASCADE);
 
grant select on Attendee_Seat_1 to public;

CREATE TABLE GeneralAdmission(
	resnum int,
	PRIMARY KEY (resnum),
	FOREIGN KEY (resnum) REFERENCES Attendee_Seat_1
	ON DELETE CASCADE);

grant select on GeneralAdmission to public;

CREATE TABLE VIP(
	resnum int,
	PRIMARY KEY (resnum),
	FOREIGN KEY (resnum) REFERENCES Attendee_Seat_1
	ON DELETE CASCADE);

grant select on VIP to public;
 
CREATE TABLE Staff(
	staffID int,
	firstname char(20),
	lastname char(20),
	PRIMARY KEY (staffID));
 
grant select on Staff to public;
 
CREATE TABLE Volunteer(
	staffID int,
	PRIMARY KEY (staffID),
	FOREIGN KEY (staffID) REFERENCES Staff
	ON DELETE CASCADE);
 
grant select on Volunteer to public;
 
CREATE TABLE Technician(
	staffID int,
	specialization char(20),
	PRIMARY KEY (staffID),
	FOREIGN KEY (staffID) REFERENCES Staff
	ON DELETE CASCADE);

grant select on Technician to public;

CREATE TABLE Security(
	staffID int,
	company char(20),
	PRIMARY KEY (staffID),
	FOREIGN KEY (staffID) REFERENCES Staff
	ON DELETE CASCADE);

grant select on Security to public;

CREATE TABLE EntertainmentCompany(
	cname char(20),
	PRIMARY KEY (cname));

grant select on EntertainmentCompany to public;

CREATE TABLE Performer(
	pname char(20),
	cname char(20),
	PRIMARY KEY (pname),
	FOREIGN KEY (cname) REFERENCES EntertainmentCompany
	ON DELETE CASCADE);

grant select on Performer to public;

CREATE TABLE Travel_Entourage(
	ename char(20),
	relationship char(20),
	pname char(20),
	PRIMARY KEY (ename, pname),
	FOREIGN KEY (pname) REFERENCES Performer
	ON DELETE CASCADE);

grant select on Travel_Entourage to public;

CREATE TABLE Performance_2(
	title char(20),
	type char(20),
	duration int,
	PRIMARY KEY (title));

grant select on Performance_2 to public;

CREATE TABLE Performance_1(
	pdate date,
	pname char(20),
	title char(20),
	PRIMARY KEY (pname, pdate),
	FOREIGN KEY (pname) REFERENCES Performer
	ON DELETE CASCADE,
    FOREIGN KEY (title) REFERENCES Performance_2
    ON DELETE CASCADE);

grant select on Performance_1 to public;

CREATE TABLE In_Charge_Of(
	staffID int,
    sectnum int,
    shift char(20),
	PRIMARY KEY (staffID, sectnum),
	FOREIGN KEY (staffID) REFERENCES Staff
	ON DELETE CASCADE,
    FOREIGN KEY (sectnum) REFERENCES SeatingSection
    ON DELETE CASCADE);

grant select on In_Charge_Of to public;

CREATE TABLE Protect(
	staffID int,
	pname char(20),
	PRIMARY KEY (staffID, pname),
	FOREIGN KEY (staffID) REFERENCES Security
	ON DELETE CASCADE,
    FOREIGN KEY (pname) REFERENCES Performer
    ON DELETE SET NULL);

grant select on Protect to public;

CREATE TABLE Assist(
	staffID int,
	pname char(20),
	PRIMARY KEY (staffID, pname),
	FOREIGN KEY (staffID) REFERENCES Technician
	ON DELETE CASCADE,
    FOREIGN KEY (pname) REFERENCES Performer
    ON DELETE SET NULL);

grant select on Assist to public;

CREATE TABLE Watch(
	resnum int,
	pname char(20),
	pdate date,
	PRIMARY KEY (resnum, pname, pdate),
	FOREIGN KEY (resnum) REFERENCES Attendee_Seat_1
	ON DELETE CASCADE,
    FOREIGN KEY (pname, pdate) REFERENCES Performance_1
    ON DELETE CASCADE);

grant select on Watch to public;

CREATE TABLE Meet(
	resnum int,
	pname char(20),
	PRIMARY KEY (resnum, pname),
	FOREIGN KEY (resnum) REFERENCES VIP
	ON DELETE CASCADE,
    FOREIGN KEY (pname) REFERENCES Performer
    ON DELETE CASCADE);

grant select on Meet to public;


insert into SeatingSection
values(100, 140);
 
insert into SeatingSection
values(300, 70);
 
insert into SeatingSection
values(203, 110);
 
insert into SeatingSection
values(101, 150);
 
insert into SeatingSection
values(105, 150);
 
insert into SeatingSection
values(209, 100);
 
insert into SeatingSection
values(222, 100);
 
insert into SeatingSection
values(102, 135);
 
insert into SeatingSection
values(301, 70);
 
insert into SeatingSection
values(109, 140);
 
insert into Attendee_Seat_2
values('Andy Dwyer', 1234567890123456, 100, 2);
 
insert into Attendee_Seat_2
values('April Ludgate', 1324567890123456, 300, 3);
 
insert into Attendee_Seat_2
values('Ron Swanson', 1423567890123456, 203, 14);
 
insert into Attendee_Seat_2
values('Leslie Knope', 1523467890123456, 101, 76);
 
insert into Attendee_Seat_2
values('Tom Haverford', 1623457890123456, 105, 57);
 
insert into Attendee_Seat_2
values('Jerry Gergich', 1723456890123456, 209, 3);
 
insert into Attendee_Seat_2
values('Ann Perkins', 1823456790123456, 222, 35);
 
insert into Attendee_Seat_2
values('Ben Wyatt', 1923456780123456, 102, 32);
 
insert into Attendee_Seat_2
values('Chris Traeger', 1023456789123456, 301, 14);
 
insert into Attendee_Seat_2
values('Donna Meagle', 2134567890123456, 109, 3);
 
insert into Attendee_Seat_1
values(35142, 1234567890123456);

insert into Attendee_Seat_1
values(49532, 1324567890123456);
 
insert into Attendee_Seat_1
values(52341, 1423567890123456);

insert into Attendee_Seat_1
values(21435, 1523467890123456);

insert into Attendee_Seat_1
values(31524, 1623457890123456);

insert into Attendee_Seat_1
values(79384, 1723456890123456);
 
insert into Attendee_Seat_1
values(92448, 1823456790123456);

insert into Attendee_Seat_1
values(41245, 1923456780123456);
 
insert into Attendee_Seat_1
values(21433, 1023456789123456);
 
insert into Attendee_Seat_1
values(35193, 2134567890123456);
 
insert into GeneralAdmission
values(35142);
 
insert into GeneralAdmission
values(49532);
 
insert into GeneralAdmission
values(52341);

insert into GeneralAdmission
values(79384);
 
insert into GeneralAdmission
values(41245);

insert into VIP
values(21435);
 
insert into VIP
values(31524);
 
insert into VIP
values(92448);
 
insert into VIP
values(21433);
 
insert into VIP
values(35193);

insert into Staff
values(1001, 'Michael', 'Scott');

insert into Staff
values(1002, 'Kevin', 'Malone');

insert into Staff
values(1003, 'Jim', 'Halpert');

insert into Staff
values(1004, 'Pam', 'Beesly');

insert into Staff
values(1005, 'Merideth', 'Palmer');

insert into Staff
values(1006, 'Stanley', 'Hudson');

insert into Staff
values(1007, 'Dwight', 'Schrute');

insert into Staff
values(1008, 'Angela', 'Martin');

insert into Staff
values(1009, 'Oscar', 'Martinez');

insert into Staff
values(1010, 'Phyllis', 'Vance');

insert into Staff
values(1011, 'Kelly', 'Kapur');

insert into Staff
values(1012, 'Ryan', 'Howard');

insert into Staff
values(1013, 'Toby', 'Flenderson');

insert into Staff
values(1014, 'Daryl', 'Philbin');

insert into Staff
values(1015, 'Jan', 'Levinson');

insert into Volunteer
values(1001);

insert into Volunteer
values(1005);

insert into Volunteer
values(1009);

insert into Volunteer
values(1010);

insert into Volunteer
values(1011);

insert into Technician
values(1002, 'lighting');

insert into Technician
values(1004, 'sound');

insert into Technician
values(1006, 'lighting');

insert into Technician
values(1008, 'sound');

insert into Technician
values(1013, 'electrical');

insert into Security
values(1003, 'Securitee');

insert into Security
values(1007, 'Securitee');

insert into Security
values(1012, 'SecureRUs');

insert into Security
values(1014, 'SecureRUs');

insert into Security
values(1015, 'SeSecure');

insert into EntertainmentCompany
values('Entertainment 720');

insert into EntertainmentCompany
values('Big Records');

insert into EntertainmentCompany
values('Sick Beats');

insert into EntertainmentCompany
values('Hype Tunez');

insert into EntertainmentCompany
values('XL Entertainment');

insert into Performer
values('Duke Silver', 'Entertainment 720');

insert into Performer
values('Scrantonicity', 'Big Records');

insert into Performer
values('Mouse Rat', 'Hype Tunez');

insert into Performer
values('Ping', 'XL Entertainment');

insert into Performer
values('DJ Disco', 'Sick Beats');

insert into Travel_Entourage
values('Diane Lewis', 'wife', 'Duke Silver');

insert into Travel_Entourage
values('Jon Swanson', 'son', 'Duke Silver');

insert into Travel_Entourage
values('Ting', 'friend', 'Ping');

insert into Travel_Entourage
values('King', 'friend', 'Ping');

insert into Travel_Entourage
values('Crystahl', 'girlfriend', 'DJ Disco');

insert into Performance_2
values('Smooth as Silver', 'jazz', 120);

insert into Performance_2
values('Hahaha', 'comedy', 90);

insert into Performance_2
values('Hahaha2', 'comedy', 90);

insert into Performance_2
values('Reunion Show', 'rock', 120);

insert into Performance_2
values('Mouse Rat', 'rock', 150);

insert into Performance_2
values('DJ Disco Halloween', 'rock', 180);

insert into Performance_1
values(TO_DATE('2022-02-13', 'YYYY-MM-DD'), 'Duke Silver', 'Smooth as Silver');

insert into Performance_1
values(TO_DATE('2022-02-14', 'YYYY-MM-DD'), 'Duke Silver', 'Smooth as Silver');

insert into Performance_1
values(TO_DATE('2023-02-14', 'YYYY-MM-DD'), 'Ping', 'Hahaha2');

insert into Performance_1
values(TO_DATE('2022-04-24', 'YYYY-MM-DD'), 'Ping', 'Hahaha');

insert into Performance_1
values(TO_DATE('2023-09-29', 'YYYY-MM-DD'), 'Ping', 'Hahaha2');

insert into Performance_1
values(TO_DATE('2022-06-30', 'YYYY-MM-DD'), 'Scrantonicity', 'Reunion Show');

insert into Performance_1
values(TO_DATE('2024-08-15', 'YYYY-MM-DD'), 'Mouse Rat', 'Mouse Rat');

insert into Performance_1
values(TO_DATE('2021-10-30', 'YYYY-MM-DD'), 'DJ Disco', 'DJ Disco Halloween');

insert into Performance_1
values(TO_DATE('2021-10-31', 'YYYY-MM-DD'), 'DJ Disco', 'DJ Disco Halloween');

insert into In_Charge_Of
values(1001, 100, '13/02/2022');

insert into In_Charge_Of
values(1009, 300, '15/08/2024');

insert into In_Charge_Of
values(1010, 203, '30/10/2021');

insert into In_Charge_Of
values(1011, 101, '30/10/2021');

insert into Assist
values(1002, 'Duke Silver');

insert into Assist
values(1004, 'Scrantonicity');

insert into Assist
values(1006, 'Mouse Rat');

insert into Assist
values(1008, 'Ping');

insert into Assist
values(1013, 'DJ Disco');

insert into Watch
values(35142, 'Ping', TO_DATE('2022-4-24', 'YYYY-MM-DD'));

insert into Watch
values(49532, 'Mouse Rat', TO_DATE('2024-8-15', 'YYYY-MM-DD'));

insert into Watch
values(52341, 'Scrantonicity', TO_DATE('2022-6-30', 'YYYY-MM-DD'));

insert into Watch
values(21435, 'Duke Silver', TO_DATE('2022-2-14', 'YYYY-MM-DD'));

insert into Watch
values(31524, 'DJ Disco', TO_DATE('2021-10-31', 'YYYY-MM-DD'));

insert into Watch
values(79384, 'Duke Silver', TO_DATE('2022-2-14', 'YYYY-MM-DD'));

insert into Watch
values(92448, 'Mouse Rat', TO_DATE('2024-8-15', 'YYYY-MM-DD'));

insert into Watch
values(41245, 'Ping', TO_DATE('2023-9-29', 'YYYY-MM-DD'));

insert into Watch
values(21433, 'Scrantonicity', TO_DATE('2022-6-30', 'YYYY-MM-DD'));

insert into Watch
values(35193, 'DJ Disco', TO_DATE('2021-10-31', 'YYYY-MM-DD'));

insert into Meet
values(21435, 'Duke Silver');

insert into Meet
values(31524, 'DJ Disco');

insert into Meet
values(92448, 'Mouse Rat');

insert into Meet
values(21433, 'Scrantonicity');

insert into Meet
values(35193, 'DJ Disco');