-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2020 at 01:53 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softype`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartment` (IN `_name` VARCHAR(255))  BEGIN
	INSERT INTO softype.department(name)
    VALUES(_name);
    
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentEmployee` (IN `_employeeId` INT(11), IN `_department_managerId` INT(11), IN `_department_headId` INT(11))  BEGIN
    INSERT INTO department_employees(employeeId, department_managerId, department_headId, created_at)
    VALUES(_employeeId, _department_managerId, _department_headId, date(now()));
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentHead` (IN `_departmentId` INT(11), IN `_department_head` INT(11))  BEGIN
	INSERT INTO department_head(departmentId, department_head, created_at)
    VALUES(_departmentId, _department_head, date(now()));
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentManager` (IN `_departmentId` INT(11), IN `_department_manager` INT(11))  BEGIN
    INSERT INTO department_manager(departmentId, department_manager, created_at)
    VALUES (_departmentId, _department_manager, date(now()));
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddFile` (IN `_employeeId` INT(11), IN `_name` VARCHAR(255), IN `_path` VARCHAR(255), IN `_type` VARCHAR(255), IN `_description` VARCHAR(255))  BEGIN
	INSERT INTO company_repository(uploadedBy, filename, path, type, description, created_at)
    VALUES(_employeeId, _name , _path, _type, _description, date(now()));
    
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddMember` (IN `_meetingId` INT(11), IN `_memberId` INT(11))  BEGIN
	INSERT INTO calendar_invites(meetingId, memberId)
    VALUES (_meetingId, _memberId);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CloseTicketRequest` (IN `_ticketId` INT(11), IN `_approverId` INT(11), IN `_indicator` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    IF _indicator = 1 THEN
    UPDATE ticket
    SET approverId = _approverId, status = 0, resolve_date = date(now()), remarks = "Request Approved"
    WHERE id = _ticketId;
    END IF;	
    IF _indicator = 0 THEN
	UPDATE ticket
    SET approverId = _approverId, status = 0, resolve_date = date(now()), remarks = "Request Rejected"
    WHERE id = _ticketId;
    END IF;
    
    SELECT _ticketId as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateAnnouncement` (IN `_employeeId` INT(11), IN `_title` VARCHAR(255), IN `_description` VARCHAR(255))  BEGIN
	INSERT INTO announcement(employeeId, title, description)
    VALUES(_employeeId, _title, _description);
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateEmployee` (IN `_firstname` VARCHAR(255), IN `_middlename` VARCHAR(255), IN `_lastname` VARCHAR(255), IN `_mobileno` VARCHAR(255), IN `_gender` VARCHAR(255), IN `_email` VARCHAR(255), IN `_birthdate` VARCHAR(255), IN `_street` VARCHAR(255), IN `_city` VARCHAR(255), IN `_country` VARCHAR(255), IN `_roleId` INT(11))  BEGIN
	INSERT INTO 
		employee(
			firstname,
			middlename,
			lastname,
			mobileno,
			gender,
			email,
			birthdate,
			street,
			city,
			country,
			roleId
		)
	VALUES (_firstname, _middlename, _lastname, _mobileno, _gender, _email, _birthdate, _street, _city, _country, _roleId);
    SELECT last_insert_id() as id;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateEmployeeAccount` (IN `_username` VARCHAR(255), IN `_password` VARCHAR(255), IN `_qrcode` VARCHAR(255), IN `_employeeId` INT(11), IN `_roleId` INT(11))  BEGIN
    DECLARE _accountType INT;
	
	CASE
    WHEN _roleId IN(1, 2 , 3 , 4) THEN
	SET _accountType = 3; # SUPERVISOR

	WHEN _roleId IN(5, 6 , 7 , 8) THEN
	SET _accountType = 2;  # EMPLOYEE

	ELSE SET _accountType = 1; # ADMIN

	END CASE;  
    
	INSERT INTO `user` 
    (
		`username`, 
		`password`, 
        `qr_code`,
        `account_type`,
        `employeeId`
	) 
    VALUES
    (
		_username, 
        _password, 
        _qrcode,
        _accountType,
        _employeeId
	);
    SELECT * FROM `user` WHERE id = (SELECT LAST_INSERT_ID());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateMeeting` (IN `_title` VARCHAR(255), IN `_organizer` INT(11), IN `_category` VARCHAR(255), IN `_description` VARCHAR(255), IN `_set_date` DATE, IN `_time_start` VARCHAR(255), IN `_time_end` VARCHAR(255), IN `_status` VARCHAR(255))  BEGIN
	INSERT INTO meeting(title, organizer, category, description, set_date, time_start, time_end, status, created_at)
	VALUES (_title, _organizer, _category, _description, _set_date, _time_start, _time_end, _status, date(now()));
	
	SELECT LAST_INSERT_ID() as id;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreatePerformanceReview` (IN `_criteria` VARCHAR(255), IN `_date_reviewed` DATE, IN `_employee_reviewed` INTEGER, IN `_ratings` DOUBLE, IN `_reviewer` INTEGER)  BEGIN
	INSERT INTO softype.performance_review(criteria, date_reviewed, employee_reviewed, ratings, reviewer)
    VALUES(_criteria, _date_reviewed, _employee_reviewed, _ratings, _reviewer);
    
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateRoleType` (IN `_position` VARCHAR(255))  BEGIN
	INSERT INTO softype.role(position) VALUES(_position);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateTicket` (IN `_transac` VARCHAR(255), IN `_employeeId` INT(11), IN `_item` VARCHAR(255), IN `_quantity` INT(11), IN `_description` VARCHAR(255))  BEGIN
    DECLARE _approver INT(11);
    
	INSERT INTO ticket(transaction_no, employeeId, item, quantity, approverId, description)
	VALUES (_transac, _employeeId, _item, _quantity, GetAdmin(),_description);

	SELECT LAST_INSERT_ID() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateUserType` (IN `_user_type` VARCHAR(255))  BEGIN
	INSERT INTO accont_type(type) VALUES(_user_type);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteAnnouncement` (IN `_announcementId` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM announcement
    WHERE id = _announcementId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartment` (IN `_departmentId` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM department
    WHERE id = _departmentId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentEmployee` (IN `_department_employeeId` INT(11))  BEGIN
	DELETE FROM department_employees
    WHERE id = _department_employeeId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentHead` (IN `_id` INT(11))  BEGIN
	DELETE FROM softype.department_head
    WHERE id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentManager` (IN `_id` INT(11))  BEGIN
	DELETE FROM softype.department_manager
    WHERE id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteEmployee` (IN `_id` INT(11))  NO SQL
BEGIN
	DELETE FROM `employee` WHERE id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteFile` (IN `_id` INT(11))  BEGIN
	DELETE FROM company_repository
    WHERE id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteMeeting` (IN `_id` INT(11))  BEGIN
	SET sql_safe_updates = 0;
	DELETE meeting, calendar_invites 
    FROM meeting
    INNER JOIN calendar_invites ON meeting.id = calendar_invites.meetingId
    WHERE meeting.id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteMember` (IN `_id` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM calendar_invites
	WHERE memberId = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeletePerformanceReview` (IN `_id` INT(11))  NO SQL
BEGIN
	DELETE FROM `performance_review` WHERE id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteTicket` (IN `_ticketId` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM ticket
    WHERE id = _ticketId;
    
    call retrieveTickets();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveAnnouncementByDate` ()  BEGIN
	SELECT 
		a.id as announcementId,
        a.title as title,
        a.description as description,
        concat(emp.firstname, ' ', emp.lastname) as creator
    from softype.announcement as a
    JOIN softype.employee as emp ON a.employeeId = emp.id
    WHERE date(t.created_at) = date(now());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveAnnouncements` ()  BEGIN
	SELECT 
		a.id as announcementId,
        a.title as title,
        a.description as description,
        concat(emp.firstname, ' ', emp.lastname) as creator
    from softype.announcement as a
    JOIN softype.employee as emp ON a.employeeId = emp.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveByFileType` (IN `_type` VARCHAR(255))  BEGIN
	SELECT 
		cr.id as file_id,
		cr.created_at as uploadedAt,
        cr.filename as filename,
        cr.path as path,
        cr.type as type,
        cr.description as description,
        concat(emp.firstname, ' ', emp.lastname) as uploadedBy
    FROM softype.company_repository as cr
    JOIN softype.employee as emp ON emp.id = cr.employeeId
    WHERE cr.type = _type;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentEmployees` ()  BEGIN
	SELECT
		emp.id as employeeId,
        dept_emp.id as department_employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
		dept.name as department_name,
		deh.department_head as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.department_manager as department_managerId,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
		emp.firstname as firstname, 
		emp.middlename as middlename,
		emp.lastname as lastname,
		emp.gender as gender,
		emp.mobileno as mobileno,
		emp.birthdate as birthdate,
		emp.email as email,
		emp.street as street,
		emp.city as city,
		emp.country as country,
		u.qr_code as qrcode
	FROM softype.employee as emp
    JOIN softype.role as r ON r.id = emp.roleId
    JOIN softype.user as u ON u.employeeId = emp.id
	LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
    LEFT JOIN softype.department_manager as dm ON dm.department_manager = emp.id
	LEFT JOIN softype.department_head as dh ON dh.department_head = emp.id
	LEFT JOIN softype.department_manager as dem ON dem.id = dept_emp.department_managerId
	LEFT JOIN softype.department_head as deh ON deh.id = dept_emp.department_headId
    LEFT JOIN softype.employee as emp_m ON dem.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON deh.department_head = emp_h.id
    LEFT JOIN softype.department as dept ON dem.departmentId = dept.id
    WHERE dept_emp.employeeId IS NOT NULL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentHeads` ()  BEGIN
	SELECT
		emp_h.id as employeeId,
        dept_h.id as managerId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
        dept.name as department_name,
		emp_h.firstname as department_head_firstname, 
		emp_h.middlename as department_head_middlename,
		emp_h.lastname as department_head_lastname,
		emp_h.gender as department_head_gender,
		emp_h.mobileno as department_head_mobileno,
		emp_h.birthdate as department_head_birthdate,
		emp_h.email as department_head_email,
		emp_h.street as department_head_street,
		emp_h.city as department_head_city,
		emp_h.country as department_head_country,
		u.qr_code as department_head_qrcode
	from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    JOIN softype.role as r ON emp_h.roleId = r.id
    JOIN softype.user as u ON emp_h.id = u.employeeId
    LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentManagers` ()  BEGIN
	SELECT
        emp_m.id as employeeId,
        dept_m.id as managerId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
        dept.name as department_name,
        concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
		emp_m.firstname as manager_firstname, 
		emp_m.middlename as manager_middlename,
		emp_m.lastname as manager_lastname,
		emp_m.gender as manager_gender,
		emp_m.mobileno as manager_mobileno,
		emp_m.birthdate as manager_birthdate,
		emp_m.email as manager_email,
		emp_m.street as manager_street,
		emp_m.city as manager_city,
		emp_m.country as manager_country,
		u.qr_code as manager_qrcode
	from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    LEFT JOIN softype.department_manager as dept_m ON dept_m.departmentId = dept.id
    JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
    JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    JOIN softype.role as r ON emp_m.roleId = r.id
    JOIN softype.user as u ON emp_m.id = u.employeeId
    LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartments` ()  BEGIN
	SELECT 
		dept.id as department_id,
        dept.name as department_name,
        concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
        concat(emps.firstname, ' ', emps.lastname) as department_employee
    from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    LEFT JOIN softype.department_manager as dept_m ON dept_m.departmentId = dept.id
    JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
    JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId
    JOIN softype.employee as emps ON dept_emp_h.employeeId = emps.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeeByDepartment` (IN `_departmentId` INT(11))  BEGIN
	SELECT
		emp.id as employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
		dept.name as department_name,
		deh.department_head as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.department_manager as department_managerId,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
		emp.firstname as firstname, 
		emp.middlename as middlename,
		emp.lastname as lastname,
		emp.gender as gender,
		emp.mobileno as mobileno,
		emp.birthdate as birthdate,
		emp.email as email,
		emp.street as street,
		emp.city as city,
		emp.country as country,
		u.qr_code as qrcode
	FROM softype.employee as emp
    JOIN softype.role as r ON r.id = emp.roleId
    JOIN softype.user as u ON u.employeeId = emp.id
    LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
    LEFT JOIN softype.department_manager as dm ON dm.department_manager = emp.id
	LEFT JOIN softype.department_head as dh ON dh.department_head = emp.id
	LEFT JOIN softype.department_manager as dem ON dem.id = dept_emp.department_managerId
	LEFT JOIN softype.department_head as deh ON deh.id = dept_emp.department_headId
    LEFT JOIN softype.employee as emp_m ON dem.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON deh.department_head = emp_h.id
    LEFT JOIN softype.department as dept ON dem.departmentId = dept.id
    WHERE dept.id = _departmentId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeeByManager` (IN `_department_managerId` INT(11))  BEGIN
SELECT
		emp.id as employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
		dept.name as department_name,
		deh.department_head as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.department_manager as department_managerId,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
		emp.firstname as firstname, 
		emp.middlename as middlename,
		emp.lastname as lastname,
		emp.gender as gender,
		emp.mobileno as mobileno,
		emp.birthdate as birthdate,
		emp.email as email,
		emp.street as street,
		emp.city as city,
		emp.country as country,
		u.qr_code as qrcode
	FROM softype.employee as emp
    JOIN softype.role as r ON r.id = emp.roleId
    JOIN softype.user as u ON u.employeeId = emp.id
    LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
    LEFT JOIN softype.department_manager as dm ON dm.department_manager = emp.id
	LEFT JOIN softype.department_head as dh ON dh.department_head = emp.id
	LEFT JOIN softype.department_manager as dem ON dem.id = dept_emp.department_managerId
	LEFT JOIN softype.department_head as deh ON deh.id = dept_emp.department_headId
    LEFT JOIN softype.employee as emp_m ON dem.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON deh.department_head = emp_h.id
    LEFT JOIN softype.department as dept ON dem.departmentId = dept.id
    
    WHERE dept_emp.department_managerId = _department_managerId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployees` ()  BEGIN
SELECT
		emp.id as employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
		dept.name as department_name,
		deh.department_head as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.department_manager as department_managerId,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
		emp.firstname as firstname, 
		emp.middlename as middlename,
		emp.lastname as lastname,
		emp.gender as gender,
		emp.mobileno as mobileno,
		emp.birthdate as birthdate,
		emp.email as email,
		emp.street as street,
		emp.city as city,
		emp.country as country,
		u.qr_code as qrcode
	FROM softype.employee as emp
    JOIN softype.role as r ON r.id = emp.roleId
    JOIN softype.user as u ON u.employeeId = emp.id
    LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
    LEFT JOIN softype.department_manager as dm ON dm.department_manager = emp.id
	LEFT JOIN softype.department_head as dh ON dh.department_head = emp.id
	LEFT JOIN softype.department_manager as dem ON dem.id = dept_emp.department_managerId
	LEFT JOIN softype.department_head as deh ON deh.id = dept_emp.department_headId
    LEFT JOIN softype.employee as emp_m ON dem.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON deh.department_head = emp_h.id
    LEFT JOIN softype.department as dept ON dem.departmentId = dept.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveFiles` ()  BEGIN
	SELECT 
		cr.id as file_id,
		cr.created_at as uploadedAt,
        cr.filename as filename,
        cr.path as path,
		cr.type as type,
        cr.description as description,
        concat(emp.firstname, ' ', emp.lastname) as uploadedBy
    FROM softype.company_repository as cr
    JOIN softype.employee as emp ON emp.id = cr.employeeId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLeaveRequests` (IN `_roleID` INT(11), IN `_empId` INT(11), IN `_status` VARCHAR(50))  BEGIN
	/*
		*1  = admin
        *2  = manager
        *3  = regular
    */
    
    if _status is null OR _status = "" then 
       set _status = "pending";
	end if;
    
    if _roleID = 1 then 
		select "admin";
    end if;
    
     if _roleID = 2 then 
		select "manager";
    end if;
    
     if _roleID = 3 then 
		SELECT emp.id AS employee_id, 
       Concat (emp.firstname, "", emp.lastname) AS `name`, 
       `status`, 
       date_from, 
       date_to, 
       reason, 
       type AS category, 
       Concat (emp1.firstname, " ", emp1.lastname) AS approver, 
       lr.approver AS approver_id 
       FROM   softype.leave_request lr 
       JOIN softype.leave_category lc 
         ON lr.leave_categoryid = lc.id 
       JOIN softype.employee emp 
         ON lr.employeeid = emp.id 
       JOIN softype.employee emp1 
         ON lr.approver = emp1.id 
	  WHERE emp.id = _empId 
        AND lr.`status` =  _status;
     end if;
    
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartment` (IN `_departmentId` INT(11))  BEGIN
	SELECT 
		dept.id as department_id,
        dept.name as department_name,
        concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
        concat(emps.firstname, ' ', emps.lastname) as department_employee
    from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    LEFT JOIN softype.department_manager as dept_m ON dept_m.departmentId = dept.id
    JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
    JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId
    JOIN softype.employee as emps ON dept_emp_h.employeeId = emps.id
    WHERE dept.id = _departmentId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentEmployee` (IN `_department_employeeId` INT(11))  BEGIN
	SELECT
		emp.id as employeeId,
        dept_emp.id as department_employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
		dept.name as department_name,
		deh.department_head as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.department_manager as department_managerId,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
		emp.firstname as firstname, 
		emp.middlename as middlename,
		emp.lastname as lastname,
		emp.gender as gender,
		emp.mobileno as mobileno,
		emp.birthdate as birthdate,
		emp.email as email,
		emp.street as street,
		emp.city as city,
		emp.country as country,
		u.qr_code as qrcode
	FROM softype.employee as emp
    JOIN softype.role as r ON r.id = emp.roleId
    JOIN softype.user as u ON u.employeeId = emp.id
	LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
    LEFT JOIN softype.department_manager as dm ON dm.department_manager = emp.id
	LEFT JOIN softype.department_head as dh ON dh.department_head = emp.id
	LEFT JOIN softype.department_manager as dem ON dem.id = dept_emp.department_managerId
	LEFT JOIN softype.department_head as deh ON deh.id = dept_emp.department_headId
    LEFT JOIN softype.employee as emp_m ON dem.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON deh.department_head = emp_h.id
    LEFT JOIN softype.department as dept ON dem.departmentId = dept.id
    WHERE dept_emp.id = _department_employeeId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentHead` (IN `_id` INT(11))  BEGIN
	SELECT
		emp_h.id as employeeId,
        dept_h.id as managerId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
        dept.name as department_name,
		emp_h.firstname as department_head_firstname, 
		emp_h.middlename as department_head_middlename,
		emp_h.lastname as department_head_lastname,
		emp_h.gender as department_head_gender,
		emp_h.mobileno as department_head_mobileno,
		emp_h.birthdate as department_head_birthdate,
		emp_h.email as department_head_email,
		emp_h.street as department_head_street,
		emp_h.city as department_head_city,
		emp_h.country as department_head_country,
		u.qr_code as department_head_qrcode
	from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    JOIN softype.role as r ON emp_h.roleId = r.id
    JOIN softype.user as u ON emp_h.id = u.employeeId
    LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    WHERE dept_h.id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentManager` (IN `_managerId` INT(11))  BEGIN
	SELECT
		emp_m.id as employeeId,
        dept_m.id as managerId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
        dept.name as department_name,
        concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
		emp_m.firstname as manager_firstname, 
		emp_m.middlename as manager_middlename,
		emp_m.lastname as manager_lastname,
		emp_m.gender as manager_gender,
		emp_m.mobileno as manager_mobileno,
		emp_m.birthdate as manager_birthdate,
		emp_m.email as manager_email,
		emp_m.street as manager_street,
		emp_m.city as manager_city,
		emp_m.country as manager_country,
		u.qr_code as manager_qrcode
	from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    LEFT JOIN softype.department_manager as dept_m ON dept_m.departmentId = dept.id
    JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
    JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    JOIN softype.role as r ON emp_m.roleId = r.id
    JOIN softype.user as u ON emp_m.id = u.employeeId
    LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId
    WHERE dept_m.id = _managerId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedEmployee` (IN `_id` INT(11))  BEGIN
SELECT
		emp.id as employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
		dept.name as department_name,
		deh.department_head as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.department_manager as department_managerId,
        concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
		emp.firstname as firstname, 
		emp.middlename as middlename,
		emp.lastname as lastname,
		emp.gender as gender,
		emp.mobileno as mobileno,
		emp.birthdate as birthdate,
		emp.email as email,
		emp.street as street,
		emp.city as city,
		emp.country as country,
		u.qr_code as qrcode
	FROM softype.employee as emp
    JOIN softype.role as r ON r.id = emp.roleId
    JOIN softype.user as u ON u.employeeId = emp.id
    LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
    LEFT JOIN softype.department_manager as dm ON dm.department_manager = emp.id
	LEFT JOIN softype.department_head as dh ON dh.department_head = emp.id
	LEFT JOIN softype.department_manager as dem ON dem.id = dept_emp.department_managerId
	LEFT JOIN softype.department_head as deh ON deh.id = dept_emp.department_headId
    LEFT JOIN softype.employee as emp_m ON dem.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON deh.department_head = emp_h.id
    LEFT JOIN softype.department as dept ON dem.departmentId = dept.id
    WHERE emp.id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedMeeting` (IN `_meetingId` INT(11))  BEGIN
	SELECT ci.meetingId, m.organizer, ci.memberId, emp.firstname, emp.middlename, emp.lastname, emp.email,
    m.title, m.category, m.description, m.set_date, m.time_start, m.time_end, m.status
    FROM meeting as m 
    INNER JOIN calendar_invites as ci ON m.id = ci.meetingId
    INNER JOIN employee  as emp ON ci.memberId = emp.id
    WHERE m.id = _meetingId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedPerformanceReview` (IN `_id` INT(11))  BEGIN
	SELECT 
    	pr.id as performance_review_id,
		concat(emp_reviewed.firstname,' ', emp_reviewed.lastname) as employee_reviewed,
        concat(emp_reviewer.firstname,' ', emp_reviewer.lastname) as employee_reviewer,
        pr.date_reviewed as date_reviewed,
        pr.criteria as criteria
        
        FROM softype.performance_review as pr
        JOIN softype.employee as emp_reviewed on emp_reviewed.id = pr.employee_reviewed
        JOIN softype.employee as emp_reviewer ON emp_reviewer.id = pr.reviewer
        WHERE pr.id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedTicket` (IN `_ticketId` INT(11))  BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.description as description,
        t.item as item,
		t.quantity as quantity,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.id = _ticketId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveMeetingByCurrentDate` ()  BEGIN
	SELECT ci.meetingId, m.organizer, ci.memberId, emp.firstname, emp.middlename, emp.lastname, emp.email,
    m.title, m.category, m.description, m.set_date, m.time_start, m.time_end, m.status
    FROM meeting as m 
	LEFT JOIN calendar_invites as ci ON m.id = ci.meetingId
    LEFT JOIN employee  as emp ON ci.memberId = emp.id
    WHERE m.set_date = date(now());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveMeetings` ()  BEGIN
	SELECT ci.meetingId, m.organizer, ci.memberId, emp.firstname, emp.middlename, emp.lastname, emp.email,
    m.title, m.category ,m.description, m.set_date, m.time_start, m.time_end, m.status
    FROM meeting as m
    INNER JOIN calendar_invites as ci ON m.id = ci.meetingId
    INNER JOIN employee  as emp ON ci.memberId = emp.id;
END$$

CREATE DEFINER=`` PROCEDURE `RetrievePerformanceReviewByEmployee` (IN `_id` INT)  NO SQL
BEGIN
	SELECT 
    	pr.id as performance_review_id,
		concat(emp_reviewed.firstname,' ', emp_reviewed.lastname) as employee_reviewed,
        concat(emp_reviewer.firstname,' ', emp_reviewer.lastname) as employee_reviewer,
        pr.date_reviewed as date_reviewed,
        pr.criteria as criteria
        
        FROM softype.performance_review as pr
        JOIN softype.employee as emp_reviewed on emp_reviewed.id = pr.employee_reviewed
        JOIN softype.employee as emp_reviewer ON emp_reviewer.id = pr.reviewer
        WHERE emp_reviewed.id = _id ;
END$$

CREATE DEFINER=`` PROCEDURE `RetrievePerformanceReviews` ()  NO SQL
BEGIN
	SELECT 
    	pr.id as performance_review_id,
		concat(emp_reviewed.firstname,' ', emp_reviewed.lastname) as employee_reviewed,
        concat(emp_reviewer.firstname,' ', emp_reviewer.lastname) as employee_reviewer,
        pr.date_reviewed as date_reviewed,
        pr.criteria as criteria
        
        FROM softype.performance_review as pr
        JOIN softype.employee as emp_reviewed on emp_reviewed.id = pr.employee_reviewed
        JOIN softype.employee as emp_reviewer ON emp_reviewer.id = pr.reviewer;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTickets` ()  BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.description as description,
        t.item as item,
		t.quantity as quantity,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByDate` ()  BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.description as description,
        t.item as item,
		t.quantity as quantity,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.created_at = date(now());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByEmployee` (IN `_employeeId` INT(11))  BEGIN
	SELECT 
		emp.id as employeeId,
		t.id as ticketId,
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.description as description,
        t.item as item,
		t.quantity as quantity,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.employeeId = _employeeId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByMonth` (IN `_month` VARCHAR(255))  BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.description as description,
        t.item as item,
		t.quantity as quantity,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE MONTH(t.created_at) = _month;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByStatus` (IN `_status` INT(11))  BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.description as description,
        t.item as item,
		t.quantity as quantity,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.status = _status;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByYear` (IN `_year` VARCHAR(255))  BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.description as description,
        t.item as item,
		t.quantity as quantity,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE YEAR(t.created_at) = _year;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveUsers` ()  BEGIN
	SELECT * from `user`;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `test` (IN `_name` VARCHAR(255))  BEGIN
	INSERT INTO softype.department(name)
    VALUES(_name);
    
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TimeIn` (IN `_employeeId` INT(11), IN `_timeIn` TIME)  BEGIN
	INSERT INTO employee_attendance(employeeId, time_in, time_out, date, no_of_hours)
    VALUES(_employeeId, _timeIn, '00:00:00', date(now()), 0);
    
    SELECT last_insert_id() as id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAnnouncement` (IN `_announcementId` INT(11), IN `_title` VARCHAR(255), IN `_description` VARCHAR(255))  BEGIN
	SET sql_safe_updates = 0;
	IF _title IS NOT NULL THEN
    UPDATE announcement
    SET title = _title
    WHERE id = _announcementId;
    END IF;
    IF _description IS NOT NULL THEN
    UPDATE announcement
    SET description = _description
    WHERE id = _announcementId;
    END IF;
    UPDATE announcement
    SET updated_at = date(now()) WHERE id = _announcementId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartment` (IN `_departmentId` INT(11), IN `_name` VARCHAR(255))  BEGIN
	SET sql_safe_updates = 0;
    IF _name IS NOT NULL THEN
    UPDATE department
    SET name = _name WHERE id = _departmentId;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentEmployee` (IN `_id` INT(11), IN `_employeeId` INT(11), IN `_department_managerId` INT(11), IN `_department_headId` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    IF _employeeId IS NOT NULL THEN
    UPDATE department_employees
    SET employeeId = _employeeId
    WHERE id = _id;
    END IF;
	IF _department_managerId IS NOT NULL THEN
    UPDATE department_employees
    SET department_managerId = _department_managerId
    WHERE id = _id;
    END IF;
    IF _department_headId IS NOT NULL THEN
    UPDATE department_employees
    SET department_headId = _department_headId
    WHERE id = _id;
    END IF;
    UPDATE department_employees
    SET updated_at = date(now())
    WHERE id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentHead` (IN `_id` INT(11), IN `_departmentId` INT(11), IN `_department_head` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    IF _departmentId IS NOT NULL THEN
    UPDATE department_head
    SET departmentId = _departmentId
    WHERE id = _id;
    END IF;
    IF _department_head IS NOT NULL THEN
    UPDATE department_head
    SET department_head = _department_head
    WHERE id = _id;
    END IF;
	UPDATE department_head
    SET updated_at = date(now())
    WHERE id = _id;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentManager` (IN `_id` INT(11), IN `_departmentId` INT(11), IN `_employeeId` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    IF _departmentId IS NOT NULL THEN
    UPDATE department_employees
    SET departmentId = _departmentId
    WHERE id = _id;
    END IF;
    IF _employeeId IS NOT NULL THEN
    UPDATE department_employees
    SET department_manager = _employeeId
    WHERE id = _id;
    END IF;
    UPDATE department_employees
    SET updated_at = date(now())
    WHERE id = _id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateEmployee` (IN `_employeeId` INT(11), IN `_firstname` VARCHAR(255), IN `_middlename` VARCHAR(255), IN `_lastname` VARCHAR(255), IN `_mobileno` VARCHAR(255), IN `_gender` VARCHAR(255), IN `_email` VARCHAR(255), IN `_birthdate` DATE, IN `_street` VARCHAR(255), IN `_city` VARCHAR(255), IN `_country` VARCHAR(255), IN `_roleId` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    SET @update_id := 0;
    IF _firstname IS NOT NULL THEN
    UPDATE employee
    SET firstname = _firstname , id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _middlename IS NOT NULL THEN
    UPDATE employee
    SET middlename = _middlename, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
	IF _lastname IS NOT NULL THEN
    UPDATE employee
    SET lastname = _lastname, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _mobileno IS NOT NULL THEN
    UPDATE employee
    SET mobileno = _mobileno, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
	IF _gender IS NOT NULL THEN
    UPDATE employee
    SET gender = _gender, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _email IS NOT NULL THEN
    UPDATE employee
    SET email = _email, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _birthdate IS NOT NULL THEN
    UPDATE employee
    SET birthdate = _birthdate, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
	IF _street IS NOT NULL THEN
    UPDATE employee
    SET street = _street, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _city IS NOT NULL THEN
    UPDATE employee
    SET city = _city, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _country IS NOT NULL THEN
    UPDATE employee
    SET country = _country, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _roleId IS NOT NULL THEN
    UPDATE employee
    SET roleId = _roleId, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    SELECT @update_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateMeeting` (IN `_meetingId` INT(11), IN `_title` VARCHAR(255), IN `_organizer` INT(11), IN `_category` VARCHAR(255), IN `_description` VARCHAR(255), IN `_set_date` DATE, IN `_time_start` VARCHAR(255), IN `_time_end` VARCHAR(255), IN `_status` VARCHAR(255))  BEGIN
	SET sql_safe_updates = 0;
    IF _title IS NOT NULL then
    UPDATE meeting
    SET title = _title WHERE id = _meetingId;
    END IF;
    IF _organizer IS NOT NULL then
    UPDATE meeting
    SET organizer = _organizer WHERE id = _meetingId;
    END IF;
    IF _category IS NOT NULL then
    UPDATE meeting
    SET category = _category WHERE id = _meetingId;
    END IF;
    IF _description IS NOT NULL then
    UPDATE meeting
    SET description = _description WHERE id = _meetingId;
    END IF;
    IF _set_date IS NOT NULL then
    UPDATE meeting
    SET set_date = _set_date WHERE id = _meetingId;
    END IF;
    IF _time_start IS NOT NULL then
    UPDATE meeting
    SET time_start = _time_start WHERE id = _meetingId;
    END IF;
    IF _time_end IS NOT NULL then
    UPDATE meeting
    SET time_end = _time_end WHERE id = _meetingId;
    END IF;
    IF _status IS NOT NULL then
    UPDATE meeting
    SET status = _status WHERE id = _meetingId;
    END IF;
    UPDATE meeting
    SET updated_at = date(now()) WHERE id = _meetingId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateTicket` (IN `_ticketId` INT(11), IN `_employeeId` INT(11), IN `_description` VARCHAR(255), IN `_item` VARCHAR(255), IN `_quantity` INT(11), IN `_status` INT(11))  BEGIN
	SET sql_safe_updates = 0;
    IF _description IS NOT NULL then
    UPDATE ticket
    SET description = _description WHERE id = _ticketId AND employeeId = _employeeId;
    END IF;
    IF _item IS NOT NULL then
    UPDATE ticket
    SET item = _item WHERE id = _ticketId AND employeeId = _employeeId;
    END IF;
    IF _quantity IS NOT NULL then
    UPDATE ticket
    SET quantity = _quantity WHERE id = _ticketId AND employeeId = _employeeId;
    END IF;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UserCreateLeaveRequest` (IN `_employeeId` INT(11), IN `_leave_categoryId` INT(11), IN `_date_from` DATE, IN `_date_to` DATE, IN `_reason` VARCHAR(255), IN `_approver` INT(11))  BEGIN
	insert into 
		softype.leave_request
		(employeeId, leave_categoryId, date_from, date_to, reason, `status`, approver,created_at) 
    values 
		(_employeeId, _leave_categoryId, _date_from, _date_to, _reason, "pending", _approver,now());
	 SELECT * FROM  softype.leave_request WHERE id = (SELECT LAST_INSERT_ID());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UserDoLogin` (IN `_username` VARCHAR(50), IN `_password` VARCHAR(50))  BEGIN
	DECLARE _employeeID INT;
    DECLARE _results INT;
    
    ###query begin
    select 
		users.employeeId  as employeeId , count(users.employeeId) as result
    from 
		softype.`user` users 
    join 
		softype.`employee` emp 
    on 
		users.employeeId = emp.id
    where 
		users.username = _username  and users.`password` = _password;
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UserGetProfile` (IN `_userID` INT(11))  BEGIN
declare _employeeID int(11);
   declare _qrCode varchar(50);
 
 --  select employeeId, qr_code  into _employeeID , _qrCode from  softype.user where id = _userID;    
  -- select * , _qrCode as qr_code from  softype.employee  emp where id = _employeeID ;
   
   select
emp.id as employeeId,
       usr.id as userId,
       firstname,
       middlename,
       lastname,
       mobileno,
       birthdate,
       gender,
       street,
       city,
       country,
       roleId,
       email,
       qr_code ,
       position ,
       account_type
   from
softype.employee emp
join softype.user usr
on emp.id = usr.employeeId
   left join softype.role rl
on emp.roleId = rl.id
   where usr.id = _userID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employeeId` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_invites`
--

CREATE TABLE `calendar_invites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meetingId` int(11) NOT NULL,
  `memberId` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_repository`
--

CREATE TABLE `company_repository` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uploadedBy` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_employees`
--

CREATE TABLE `department_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employeeId` int(11) NOT NULL,
  `department_managerId` int(11) NOT NULL,
  `department_headId` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_head`
--

CREATE TABLE `department_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `departmentId` int(11) NOT NULL,
  `department_head` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_manager`
--

CREATE TABLE `department_manager` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `departmentId` int(11) NOT NULL,
  `department_manager` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middlename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobileno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `firstname`, `middlename`, `lastname`, `mobileno`, `birthdate`, `email`, `gender`, `street`, `city`, `country`, `roleId`) VALUES
(2, 'Harvey', 'test', 'Aparece', '123', '2020-09-01', 'harvet@gmai.com', 'Male', 'test', 'test', 'test', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employeeId` int(11) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `date` date NOT NULL,
  `no_of_hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_project`
--

CREATE TABLE `employee_project` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employeeId` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_category`
--

CREATE TABLE `leave_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_request`
--

CREATE TABLE `leave_request` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employeeId` int(11) NOT NULL,
  `leave_categoryId` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver` int(11) NOT NULL,
  `date_approved` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizer` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `set_date` date NOT NULL,
  `time_start` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_end` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(21, '2014_10_12_100000_create_password_resets_table', 1),
(22, '2019_08_19_000000_create_failed_jobs_table', 1),
(23, '2020_08_31_064651_create_employee', 1),
(24, '2020_09_07_135928_create_role', 1),
(25, '2020_09_07_140701_create_project', 1),
(26, '2020_09_07_141744_create_ticket_request', 1),
(27, '2020_09_07_141756_create_leave_request', 1),
(28, '2020_09_07_144251_create_employee_attendance', 1),
(29, '2020_09_07_144258_create_employee_project', 1),
(30, '2020_09_07_144318_create_company_repository', 1),
(31, '2020_09_07_144332_create_remaining_leave', 1),
(32, '2020_09_15_005948_create_user', 1),
(33, '2020_09_21_020029_create_meeting', 1),
(34, '2020_09_21_020218_create_announcement', 1),
(35, '2020_09_21_020240_create_calendar_invites', 1),
(36, '2020_09_21_020445_create_leave_category', 1),
(37, '2020_10_01_142306_create_department', 1),
(38, '2020_10_01_142336_create_department_employees', 1),
(39, '2020_10_06_021128_create_department_head', 1),
(40, '2020_10_06_021137_create_department_manager', 1),
(41, '2020_10_17_012724_create_performance_review_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance_review`
--

CREATE TABLE `performance_review` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_reviewed` date NOT NULL,
  `criteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_reviewed` int(11) NOT NULL,
  `reviewer` int(11) NOT NULL,
  `ratings` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `performance_review`
--

INSERT INTO `performance_review` (`id`, `date_reviewed`, `criteria`, `employee_reviewed`, `reviewer`, `ratings`) VALUES
(1, '0000-00-00', 'Sample criteria', 2, 2, 9.5);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remaining_leave`
--

CREATE TABLE `remaining_leave` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employeeId` int(11) NOT NULL,
  `no_of_days` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `position`) VALUES
(1, 'IT'),
(2, 'Intern');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employeeId` int(11) NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resolve_date` date DEFAULT NULL,
  `approverId` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT '2020-10-17'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` int(11) NOT NULL,
  `employeeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `qr_code`, `account_type`, `employeeId`) VALUES
(1, 'haparece', '$2y$10$M939cqy6oZebD.jY1NB5nubxpSJ23LkkB7H7Mz0.J3WBfljsX4Khq', 'qrcode/haparece_2.svg', 3, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_invites`
--
ALTER TABLE `calendar_invites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_repository`
--
ALTER TABLE `company_repository`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_employees`
--
ALTER TABLE `department_employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_head`
--
ALTER TABLE `department_head`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_manager`
--
ALTER TABLE `department_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_email_unique` (`email`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_project`
--
ALTER TABLE `employee_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_category`
--
ALTER TABLE `leave_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_request`
--
ALTER TABLE `leave_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `performance_review`
--
ALTER TABLE `performance_review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remaining_leave`
--
ALTER TABLE `remaining_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_qr_code_unique` (`qr_code`),
  ADD UNIQUE KEY `user_employeeid_unique` (`employeeId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar_invites`
--
ALTER TABLE `calendar_invites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_repository`
--
ALTER TABLE `company_repository`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department_employees`
--
ALTER TABLE `department_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department_head`
--
ALTER TABLE `department_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department_manager`
--
ALTER TABLE `department_manager`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_project`
--
ALTER TABLE `employee_project`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_category`
--
ALTER TABLE `leave_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_request`
--
ALTER TABLE `leave_request`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `performance_review`
--
ALTER TABLE `performance_review`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remaining_leave`
--
ALTER TABLE `remaining_leave`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
