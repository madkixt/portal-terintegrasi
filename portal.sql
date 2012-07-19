USE Portal;

CREATE TABLE tbl_user (
userID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
role INTEGER NOT NULL DEFAULT 2,
username VARCHAR(20) NOT NULL UNIQUE,
password CHAR(32) NOT NULL,
description TEXT,
creationDate DATE NOT NULL,
modifiedDate DATE,
createdBy INTEGER,
lastModifiedBy INTEGER,
FOREIGN KEY(createdBy) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE SET NULL,
FOREIGN KEY(lastModifiedBy) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE SET NULL
) Engine=InnoDB;

CREATE TABLE tbl_query (
queryID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(50) NOT NULL UNIQUE,
databaseName VARCHAR(30) NOT NULL,
notes TEXT,
creationDate DATE NOT NULL,
modifiedDate DATE,
notesModifiedDate DATE,
createdBy INTEGER,
lastModifiedBy INTEGER,
lastNotesEditor INTEGER,
FOREIGN KEY(createdBy) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE SET NULL,
FOREIGN KEY(lastModifiedBy) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE SET NULL,
FOREIGN KEY(lastNotesEditor) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE SET NULL
) Engine=InnoDB;

CREATE TABLE tbl_statement (
queryID INTEGER NOT NULL,
queryNum INTEGER NOT NULL,
queryStatement TEXT NOT NULL,
notes TEXT,
PRIMARY KEY(queryID, queryNum),
FOREIGN KEY(queryID) REFERENCES tbl_query(queryID) ON UPDATE CASCADE ON DELETE CASCADE
) Engine=InnoDB;

CREATE TABLE tbl_connection (
connectionID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
IPAddress CHAR(15) NOT NULL,
dbms INTEGER NOT NULL,
username VARCHAR(20),
password CHAR(32),
serverName VARCHAR(20),
description TEXT,
creationDate DATE NOT NULL,
modifiedDate DATE,
createdBy INTEGER,
lastModifiedBy INTEGER,
FOREIGN KEY(createdBy) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE SET NULL,
FOREIGN KEY(lastModifiedBy) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE SET NULL
) Engine=InnoDB;

CREATE TABLE tbl_user_query (
userID INTEGER NOT NULL,
queryID INTEGER NOT NULL,
PRIMARY KEY(userID, queryID), 
FOREIGN KEY(userID) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE CASCADE,
FOREIGN KEY(queryID) REFERENCES tbl_query(queryID) ON UPDATE CASCADE ON DELETE CASCADE
) Engine = InnoDB;

CREATE TABLE tbl_user_connection (
userID INTEGER NOT NULL,
connectionID INTEGER NOT NULL,
PRIMARY KEY(userID, connectionID), 
FOREIGN KEY(userID) REFERENCES tbl_user(userID) ON UPDATE CASCADE ON DELETE CASCADE,
FOREIGN KEY(connectionID) REFERENCES tbl_connection(connectionID) ON UPDATE CASCADE ON DELETE CASCADE
) Engine = InnoDB;