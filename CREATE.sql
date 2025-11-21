--table creation
CREATE TABLE Employee (
  EmployeeID CHAR(50) PRIMARY KEY,
  FullName CHAR(100) NOT NULL,
  Age INT,
  SupervisorID CHAR(50) NULL,
  FOREIGN KEY (SupervisorID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Driver (
  EmployeeID CHAR(50) PRIMARY KEY,
  LicenseNo CHAR(50) UNIQUE,
  LicenseValidUntil DATE NOT NULL,
  FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Boat (
  BoatID CHAR(50) PRIMARY KEY,
  BoatName CHAR(50) NOT NULL,
  Colour CHAR(50),
  Size INT
);

CREATE TABLE Dock (
  DockID CHAR(50) PRIMARY KEY,
  DockName CHAR(50) NOT NULL
);

CREATE TABLE Route (
  RouteID CHAR(50) PRIMARY KEY,
  RouteName CHAR(50) NOT NULL
);

CREATE TABLE Route_Stop (
  RouteID CHAR(50),
  SeqNo INT,
  DockID CHAR(50) NULL,
  PRIMARY KEY (RouteID, SeqNo),
  FOREIGN KEY (DockID) REFERENCES Dock(DockID)
    ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (RouteID) REFERENCES Route(RouteID)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Trip (
  TripID CHAR(50) PRIMARY KEY,
  Date DATE NOT NULL,
  DepartTime TIME NOT NULL,
  ArriveTime TIME NOT NULL,
  Fare DECIMAL(10,2) NOT NULL,
  EmployeeID CHAR(50) NULL,
  BoatID CHAR(50) NOT NULL,
  FromDock CHAR(50) NOT NULL,
  ToDock CHAR(50) NOT NULL,
  SupervisorID CHAR(50) NULL,
  FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (BoatID) REFERENCES Boat(BoatID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (FromDock) REFERENCES Dock(DockID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (ToDock) REFERENCES Dock(DockID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (SupervisorID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Crewed_By (
  TripID CHAR(50),
  EmployeeID CHAR(50),
  CrewRole CHAR(50),
  PRIMARY KEY (TripID, EmployeeID),
  FOREIGN KEY (TripID) REFERENCES Trip(TripID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Booking (
  BookingID CHAR(50) PRIMARY KEY,
  Status BOOLEAN,
  Price INT,
  PurchaseTS CHAR(50),
  TripID CHAR(50) NULL,
  FOREIGN KEY (TripID) REFERENCES Trip(TripID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Passenger (
  PassengerID CHAR(50) PRIMARY KEY,
  FullName CHAR(50),
  Email CHAR(50) UNIQUE,
  Phone CHAR(10),
  AgeGroup INT,
  BookingID CHAR(50) NULL,
  FOREIGN KEY (BookingID) REFERENCES Booking(BookingID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE ExpressBooking (
  BookingID CHAR(50) PRIMARY KEY,
  FastPass CHAR(50),
  FOREIGN KEY (BookingID) REFERENCES Booking(BookingID)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE RoundTripBooking (
  BookingID CHAR(50) PRIMARY KEY,
  ReturnTime TIME NOT NULL,
  FOREIGN KEY (BookingID) REFERENCES Booking(BookingID)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Approves (
  TripID CHAR(50),
  SupervisorID CHAR(50),
  ApprovedAt CHAR(50),
  PRIMARY KEY (TripID, SupervisorID),
  FOREIGN KEY (TripID) REFERENCES Trip(TripID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (SupervisorID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE CASCADE
);


--tuple population
INSERT INTO Employee (EmployeeID, FullName, Age, SupervisorID) VALUES
('E06','Laurel Paul',42,NULL),
('E07','Veronica Rogers',29,NULL);

INSERT INTO Employee (EmployeeID, FullName, Age, SupervisorID) VALUES
('E01','Michael Smith',23,'E06'),
('E02','Jess Richardson',31,'E06'),
('E03','Zaid Oordoni',26,'E07'),
('E04','George Gironimo',34,'E07'),
('E05','Terry Cook',19,'E07'),
('E08','Bill Potson',62,'E06'),
('E09','Jim Robertson',51,'E06'),
('E10','Michelle Torson',43,'E07'),
('E11','Jerry Tickles',39,'E07');

INSERT INTO Driver (EmployeeID, LicenseNo, LicenseValidUntil) VALUES
('E03','L001','2028-11-11'),
('E04','L002','2029-10-05'),
('E05','L003','2026-08-02'),
('E10','L004','2028-09-09'),
('E11','L005','2027-05-04');

INSERT INTO Boat (BoatID, BoatName, Colour, Size) VALUES
('B01','Coastal Breeze','Red',20),
('B02','Fish Smell','Brown',25),
('B03','Bill Clinton','Blue',15),
('B04','Great Mariner','Brown',27),
('B05','Iron Fist','Silver',15);

INSERT INTO Dock (DockID, DockName) VALUES
('D01','Granville Island'),
('D02','Yaletown'),
('D03','Olympic Village'),
('D04','David Lam'),
('D05','Arenas');

INSERT INTO Route(RouteID, RouteName) VALUES
('R01','Island-Olympic'),
('R02','Island-Arenas'),
('R03','Island-Yaletown'),
('R04','Yaletown-Island'),
('R05','Arenas-Island');

INSERT INTO Route_Stop (RouteID, SeqNo, DockID) VALUES
('R01',1,'D01'), ('R01',2,'D04'), ('R01',3,'D02'), ('R01',4,'D05'), ('R01',5,'D03'),
('R02',1,'D01'), ('R02',2,'D04'), ('R02',3,'D02'), ('R02',4,'D05'),
('R03',1,'D01'), ('R03',2,'D04'), ('R03',3,'D02'),
('R04',1,'D02'), ('R04',2,'D04'), ('R04',3,'D01'),
('R05',1,'D05'), ('R05',2,'D02'), ('R05',3,'D04'), ('R05',4,'D01');

INSERT INTO Trip (TripID, Date, DepartTime, ArriveTime, Fare, EmployeeID, BoatID, FromDock, ToDock, SupervisorID) VALUES
('T01','2025-10-20','10:00:00','10:15:00',10.00,'E03','B01','D01','D04','E07'),
('T02','2025-10-20','10:20:00','10:35:00',10.00,'E03','B01','D04','D02','E07'),
('T03','2025-10-20','10:40:00','10:55:00',10.00,'E03','B01','D02','D05','E07'),
('T04','2025-10-20','11:00:00','11:15:00',10.00,'E03','B01','D05','D03','E07'),
('T05','2025-10-20','11:30:00','11:45:00',6.00,'E04','B01','D05','D02','E07'),
('T06','2025-10-20','11:50:00','12:05:00',10.00,'E04','B01','D02','D04','E07'),
('T07','2025-10-20','12:10:00','12:25:00',10.00,'E04','B01','D04','D01','E07');

INSERT INTO Booking (BookingID, Status, Price, PurchaseTS, TripID) VALUES
('B01',1,10,'2025-10-20 10:00:00','T01'),
('B02',1,10,'2025-10-20 10:00:00','T01'),
('B03',1,10,'2025-10-20 10:40:00','T03'),
('B04',1,6,'2025-10-20 11:30:00','T05'),
('B05',1,8,'2025-10-20 11:30:00','T05'),
('B06',1,10,'2025-10-20 11:30:00','T05'),
('B07',1,10,'2025-10-20 11:30:00','T05'),
('B08',1,10,'2025-10-20 10:00:00','T05'),
('B09',1,10,'2025-10-20 11:30:00','T05'),
('B10',1,10,'2025-10-20 11:30:00','T05');

INSERT INTO Passenger (PassengerID, FullName, Email, Phone, AgeGroup, BookingID) VALUES
('P01','Samantha Peterson','spet@gmail.com','6044567890',2,'B01'),
('P02','Giorgino Stalone','gstalone@gmail.com','6045167820',2,'B02'),
('P03','Marky Barky','markyb@gmail.com','6044141550',2,'B03'),
('P04','Peter Pan','ppman@gmail.com','7784414688',1,'B04'),
('P05','Vir Gerl','vgerl@gmail.com','7785437654',3,'B05'),
('P06','Mike Pork','mpork@gmail.com','7781237652',2,'B06'),
('P07','Ahmed Kharieh','ahmedk@gmail.com','7783215324',2,'B07'),
('P08','Lin Maryam','linmar@gmail.com','6047633241',2,'B08'),
('P09','Mike Pork','mpork2@gmail.com','7789088765',2,'B09'),
('P10','Mike Pork','mpork3@gmail.com','7789088766',2,'B10');

INSERT INTO Crewed_By (TripID, EmployeeID, CrewRole) VALUES
('T01','E01','Deckhand'), ('T01','E03','Driver'),
('T02','E09','Deckhand'), ('T02','E02','Deckhand'), ('T02','E04','Driver');

INSERT INTO ExpressBooking (BookingID, FastPass) VALUES
('B01','FP441'), ('B02','FP124'), ('B05','FP743'), ('B06','FP897'), ('B07','FP627');

INSERT INTO RoundTripBooking (BookingID, ReturnTime) VALUES
('B01','12:25:00'), ('B02','12:25:00'), ('B03','12:25:00'), ('B08','12:25:00'), ('B09','12:25:00');

INSERT INTO Approves (TripID, SupervisorID, ApprovedAt) VALUES
('T01','E07','2025-10-19 09:00:00'),
('T02','E07','2025-10-19 09:10:00'),
('T03','E07','2025-10-19 09:20:00'),
('T05','E07','2025-10-19 09:30:00'),
('T06','E07','2025-10-19 09:40:00');
