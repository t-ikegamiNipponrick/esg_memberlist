USE test;

CREATE TABLE
ESG_memberList
(
        employee_id INT PRIMARY KEY AUTO_INCREMENT,
        member_name VARCHAR(40) NOT NULL,
        member_from VARCHAR(40),
        DateEntry DATE,
        dispatched VARCHAR(40),
        tasks VARCHAR(40)
);

CREATE TABLE
ESG_memberInfo
(
        employee_id INT PRIMARY KEY AUTO_INCREMENT,
        key_id INT(11)
);

CREATE TABLE
ESG_memberInfoB
(
        dummy_id INT(11) PRIMARY KEY,
        key_id INT(11),
        dispatched_sofar VARCHAR(40),
        tasks_sofar VARCHAR(40),
        tasks_sofarStart DATE,
        tasks_sofarFin DATE,
        skill_name VARCHAR(20),
        skill_date VARCHAR(10),
)

CREATE TABLE 
ESG_memberPics
(
        employee_id INT(5) PRIMARY KEY,
        key_id INT(5)
);

CREATE TABLE
ESG_memberPicsB
(
        dummyPics_id INT(11) PRIMARY KEY AUTO_INCREMENT,
        key_id INT(11) ,
        file_name VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
        file_type varchar(64)	utf8_unicode_ci	NOT NULL,	
        file_content longblob NOT NULL,
        file_size int(11) NOT NULL
);

SET AUTOCOMMIT=0;
INSERT INTO
ESG_memberList
VALUES
(11400, '池上智啓', '岡山県', '2023-04-01', 'aaa', 'bbb' );
 
SET AUTOCOMMIT=1;

SET AUTOCOMMIT=0;
INSERT INTO
ESG_memberInfo
VALUES
(11400, 'ccc', '2023-08-01', '2023-11-01', 'Java', '2年' );
 
SET AUTOCOMMIT=1;
