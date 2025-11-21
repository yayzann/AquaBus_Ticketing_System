-- AquaBus Ticketing System Database Schema
-- Note: Mostly taken from the assignment.
-- TODO: Add sample data for boats, docks, and routes.
-- TODO: Verify Approves table structure.

CREATE TABLE Employee (
  EmployeeID CHAR(50) PRIMARY KEY,
  FullName CHAR(100) NOT NULL,
  Age INT,
  SupervisorID CHAR(50),
  FOREIGN KEY (SupervisorID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Driver (
  EmployeeID CHAR(50) PRIMARY KEY,
  LicenseNo CHAR(50) UNIQUE,
  LicenseValidUntil DATE NOT NULL,
  FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE SET NULL
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
  DockID CHAR(50),
  PRIMARY KEY (RouteID, SeqNo),
  FOREIGN KEY (DockID) REFERENCES Dock(DockID)
    ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (RouteID) REFERENCES Route(RouteID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Trip (
  TripID CHAR(50) PRIMARY KEY,
  DepartTime TIME,
  ArriveTime TIME,
  Status BOOLEAN,
  EmployeeID CHAR(50),
  BoatID CHAR(50),
  DepartDockID CHAR(50),
  ArriveDockID CHAR(50),
  SupervisorID CHAR(50),
  FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
    ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (BoatID) REFERENCES Boat(BoatID)
    ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (DepartDockID) REFERENCES Dock(DockID)
    ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (ArriveDockID) REFERENCES Dock(DockID)
    ON UPDATE CASCADE ON DELETE SET NULL,
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
  TripID CHAR(50),
  FOREIGN KEY (TripID) REFERENCES Trip(TripID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Passenger (
  PassengerID CHAR(50) PRIMARY KEY,
  FullName CHAR(50),
  Email CHAR(50) UNIQUE,
  Phone CHAR(10),
  AgeGroup INT,
  BookingID CHAR(50),
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
