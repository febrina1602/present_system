CREATE TABLE USER(
	id BIGINT PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(100) NOT NULL,
	password VARCHAR(128) NOT NULL,
	created_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
	access VARCHAR(10) DEFAULT 'user'
);

CREATE TABLE employee(
	id BIGINT PRIMARY KEY,
	name CHARACTER VARYING(255) NOT NULL,
	number VARCHAR(18) DEFAULT NULL,
	birth_date DATE DEFAULT NULL,
	education VARCHAR(150) DEFAULT NULL,
	address TEXT DEFAULT NULL,
	phone_number CHARACTER VARYING(15) DEFAULT NULL,
	level CHARACTER VARYING(15) DEFAULT NULL,
	created_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
	created_by VARCHAR(30) DEFAULT 'SYSTEM'
);



CREATE TABLE attendance(
	id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	employee__id BIGINT NOT NULL,
	date DATE NOT NULL,
	time_in DATETIME DEFAULT CURRENT_TIMESTAMP(),
	time_out DATETIME DEFAULT CURRENT_TIMESTAMP(),
	shift_start TIME DEFAULT NULL,
	shift_end TIME DEFAULT NULL,
	latitude VARCHAR(100) DEFAULT NULL,
	longitude VARCHAR(100) DEFAULT NULL,
	attachment VARCHAR(255) DEFAULT NULL,
	CONSTRAINT fk_employee_to_attendance FOREIGN KEY(employee__id)
	REFERENCES employee(id)
);

CREATE TABLE shift(
	id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL,
	time_start TIME DEFAULT NULL,
	time_end TIME DEFAULT NULL
);
/* running dari sini */

delete from user;
delete from employee;

ALTER TABLE USER
ADD COLUMN employee__id BIGINT NOT NULL,
ADD CONSTRAINT fk_employee_to_user FOREIGN KEY(employee__id)
REFERENCES employee(id);

DELIMITER $$

CREATE
    /*[DEFINER = { user | CURRENT_USER }]*/
    TRIGGER `db_pegawai`.`employee_after_insert` AFTER INSERT
    ON `db_pegawai`.`employee`
    FOR EACH ROW BEGIN
	INSERT INTO USER(username, PASSWORD, access, employee__id)
	VALUES(new.name, 'f5bb0c8de146c67b44babbf4e6584cc0', 'user', new.id);
    END$$

DELIMITER ;

ALTER TABLE employee
ADD COLUMN gender VARCHAR(20) NOT NULL AFTER birth_date;

INSERT INTO employee VALUES(2022001, 'admin', NULL, '2005-02-16', 'Wanita', 'SMK', NULL, NULL, 'IVA', CURRENT_TIMESTAMP(), 'SYSTEM');
update user set access = 'admin' where employee__id = 2022001;

ALTER TABLE attendance
add column status varchar(10) default 'Alpha' after attachment;

INSERT INTO shift(name, time_start, time_end)
values('Shift Normal', '06:45', '17:30');