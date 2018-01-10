CREATE TABLE IF NOT EXISTS Users 
(
UserID CHAR(14), 
UserPassword VARCHAR(255) NOT NULL,/*Use a php hash function to hash the text password*/ 
JoinDate DATETIME NOT NULL,
FirstName VARCHAR(20),
MiddleName VARCHAR(20), /*Middle Name CHAR(20)*/
LastName VARCHAR(20), /*Last Name	CHAR(20)*/
Sex ENUM('M','F','C') NOT NULL, /*Sex CHAR(M, F OR C for Company)*/
DoB DATE, /*Dob			DATE*/
CoName VARCHAR(50), /*Company name VARCHAR (100)*/
Email VARCHAR(50) NOT NULL, /*Email addresses VARCHAR (50)*/
Address VARCHAR(140), /*Address VARCHAR (140)*/
District VARCHAR(20) NOT NULL,/*District (Head offices) CHAR(50)*/
Website VARCHAR(30),/*Website VARCHAR(30)*/
PhoneNo CHAR(11) NOT NULL, /*Phone number INT(11) NOT NULL*/
About TEXT,
ProfilePic VARCHAR(255), /*uri to profile pic*/
ResponseTime TIME,
PrivacyBitmap VARCHAR(12),
UnreadMessages SMALLINT UNSIGNED DEFAULT 0,
NewOrders SMALLINT UNSIGNED,
UNIQUE (PhoneNo),
PRIMARY KEY (UserID)
);
CREATE TABLE IF NOT EXISTS Items
(
ItemID CHAR(14), /*ItemID VARCHAR(PHP uniqid(I)) PRIMARY KEY*/
ItemName VARCHAR(50) UNIQUE NOT NULL,/*Name (select from category) VARCHAR (50)*/
Aliases VARCHAR(255),
Category VARCHAR(255) NOT NULL,
Description VARCHAR(140),/*Description VARCHAR(140)*/
PRIMARY KEY (ItemID)
);

CREATE TABLE IF NOT EXISTS Repository
(
RepID CHAR(14),
UserID CHAR(14),
ItemID CHAR(14),
Quantity DECIMAL(10,2) NOT NULL,
Units VARCHAR(10) NOT NULL,
UnitPrice DECIMAL(12,2) NOT NULL, /*(Shillings will be assumed, unless otherwise specified)*/
State VARCHAR(50), /*(Fresh/Dried/Other[Describe])*/
DateAdded DATETIME NOT NULL, /*Date added DATE(). It shouldn't be able to change everytime it's updated*/
Description VARCHAR(255),
Deliverable ENUM('Y','N'), /*Deliverable areas (if deliverable) VARCHAR (256)*/
DeliverableAreas VARCHAR(255),
PRIMARY KEY (RepID),
FOREIGN KEY (UserID) REFERENCES Users(UserID),
FOREIGN KEY (ItemID) REFERENCES Items(ItemID)
);
CREATE TABLE IF NOT EXISTS ClosedOrders
(
OrderID CHAR(14), /*Generated at the time "place order" is placed*/
RepID CHAR(14),
Quantity INT(9) NOT NULL,
Units VARCHAR(10) NOT NULL,
ClientID CHAR(14) NOT NULL, /*The client's phone number*/
Delivery CHAR(30) NOT NULL, /*The client's location*/
ClientRemarks VARCHAR(255), /*the client's comments*/
OrderTime TIMESTAMP NOT NULL,
OrderExpiration DATETIME NOT NULL,
PRIMARY KEY (OrderID),
FOREIGN KEY (RepID) REFERENCES Repository(RepID)
);
CREATE TABLE IF NOT EXISTS OpenOrders
(
  OrderID CHAR(14), /*Generated at the time "place order" is placed*/
  ItemID CHAR(14),
  Quantity INT(9) NOT NULL,
  PriceMax DECIMAL(12,2) NOT NULL,
  Units VARCHAR(10) NOT NULL,
  ClientID CHAR(11) NOT NULL, /*The client's phone number*/
  OrderExpiration DATETIME NOT NULL,
  Delivery CHAR(30) NOT NULL, /*The client's location*/
  ClientRemarks VARCHAR(255), /*the client's comments*/
  OrderTime TIMESTAMP NOT NULL,
  DeliveryRequest CHAR(255),
  PickUpCapability CHAR(255), /*Where the client is capable of picking up*/
  PRIMARY KEY (OrderID),
  FOREIGN KEY (ItemID) REFERENCES Items(ItemID),
  FOREIGN KEY (ClientID) REFERENCES Clients(ClientID)
);
/*Any other constraints will be applied on the forms*/
CREATE TABLE IF NOT EXISTS Clients
(
  ClientID CHAR(14) UNIQUE,
  Honorific ENUM('Mr.', 'Mrs.', 'Miss'), /*Eliminates need to ask for gender*/
  Name VARCHAR(20),
  PhoneNo CHAR(11) NOT NULL,
  Email VARCHAR(50) NOT NULL,
  PwordHash VARCHAR(255), /*Password. Permit only up to 8 characters for simplicity. We're not protecting nuclear launch codes here*/
  JoinDate DATETIME NOT NULL,
  UnreadMessages SMALLINT UNSIGNED DEFAULT 0,
  PRIMARY KEY (ClientID)
);
CREATE TABLE IF NOT EXISTS Units
(
  UnitID CHAR(14),
  Name VARCHAR(20), /*Specifies the name of the Unit e.g Kilograms*/
  NamePlural VARCHAR(20),
  Symbol VARCHAR(5), /*E.g kgs, g, m, */
  Minimum INT(9), /*Specifies the minimum size of an order*/
  Maximum INT(9), /*Specifies the maximum size of an order*/
  Fractions BOOL, /*Can the units be fractions and floating points?*/
  SI BOOL, /*Specifies whether Units are Standard International*/
  PRIMARY KEY (UnitID)
);
CREATE TABLE IF NOT EXISTS UnitsJunct
(
  ItemID CHAR(14) NOT NULL,
  UnitID CHAR(14) NOT NULL,
  PRIMARY KEY (ItemID, UnitID),
  FOREIGN KEY (ItemID) REFERENCES Items(ItemID),
  FOREIGN KEY (UnitID) REFERENCES Units(UnitID)
);
CREATE TABLE IF NOT EXISTS Messages
(
  ChannelID CHAR(28),
  SenderID CHAR(14),
  ReceiverID CHAR(14) NOT NULL,
  TimeStamp TIMESTAMP,
  MsgText CHAR(255),
  PictureID CHAR(14),
  ImageURI VARCHAR(255),
  MsgSerial SERIAL,
  SeenStatus BOOL NOT NULL DEFAULT FALSE,
  PRIMARY KEY (ChannelID, TimeStamp)
);
CREATE TABLE IF NOT EXISTS ItemImages
(
  ItemID CHAR(14),
  ImgID CHAR(14),
  ImageURI VARCHAR(255),
  TimeStamp TIMESTAMP,
  PRIMARY KEY (ImgID),
  FOREIGN KEY (ItemID) REFERENCES Items(ItemID)
);
CREATE TABLE IF NOT EXISTS OrderImages
(
  OrderID CHAR(14),
  ImgID CHAR(14),
  ClientID CHAR(14),
  ImageURI VARCHAR(255),
  TimeStamp TIMESTAMP,
  PRIMARY KEY (ImgID),
  FOREIGN KEY (OrderID) REFERENCES OpenOrders(OrderID),
  FOREIGN KEY (ClientID) REFERENCES Clients(ClientID)
);
CREATE TABLE IF NOT EXISTS RepImages
(
  RepID CHAR(14),
  ImgID CHAR(14),
  UserID CHAR(14),
  ImageURI VARCHAR(255),
  TimeStamp TIMESTAMP,
  PRIMARY KEY (ImgID),
  FOREIGN KEY (UserID) REFERENCES Users(UserID),
  FOREIGN KEY (RepID) REFERENCES Repository(RepID)
);
CREATE TABLE IF NOT EXISTS ItemAssoc
(
  UserID CHAR(14) NOT NULL,
  ItemID CHAR(14) NOT NULL,
  PRIMARY KEY (UserID, ItemID),
  FOREIGN KEY (UserID) REFERENCES Users(UserID),
  FOREIGN KEY (ItemID) REFERENCES Items(ItemID)
);
CREATE TABLE IF NOT EXISTS InterestedOrders /*This keeps track of which seller is interested in which open orders*/
(
  UserID CHAR(14) NOT NULL,
  OrderID CHAR(14) NOT NULL, /*The one in OpenOrders*/
  PRIMARY KEY (UserID, OrderID),
  FOREIGN KEY (UserID) REFERENCES Users(UserID),
  FOREIGN KEY (OrderID) REFERENCES OpenOrders(OrderID)
);

