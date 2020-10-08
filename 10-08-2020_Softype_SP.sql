DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartment`(IN _name VARCHAR(255))
BEGIN
	INSERT INTO softype.department(name)
    VALUES(_name);
    
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentEmployee`(
	IN _employeeId INT(11), 
    IN _department_managerId INT(11),
    IN _department_headId INT(11))
BEGIN
    INSERT INTO department_employees(employeeId, department_managerId, department_headId, created_at)
    VALUES(_employeeId, _department_managerId, _department_headId, date(now()));
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentHead`(IN _departmentId INT(11), IN _department_head INT(11))
BEGIN
	INSERT INTO department_head(departmentId, department_head, created_at)
    VALUES(_departmentId, _department_head, date(now()));
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentManager`(IN _departmentId INT(11), IN _department_manager INT(11) )
BEGIN
    INSERT INTO department_manager(departmentId, department_manager, created_at)
    VALUES (_departmentId, _department_manager, date(now()));
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddMember`(IN _meetingId int(11), IN _memberId int(11))
BEGIN
	INSERT INTO calendar_invites(meetingId, memberId)
    VALUES (_meetingId, _memberId);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ApproveTicket`(IN _ticketId INT(11) ,IN _approverId INT(11), IN _remarks VARCHAR(255))
BEGIN
	SET sql_safe_updates = 0;
    UPDATE ticket
    SET approverId = _approverId, status = 1, resolve_date = date(now()), remarks = _remarks
    WHERE id = _ticketId;

	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date ,t.status,t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE t.id = _ticketId AND t.approverId = _approverId AND t.status = 1;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateAnnouncement`(IN _employeeId INT(11), IN _title VARCHAR(255), IN _description VARCHAR(255))
BEGIN
	INSERT INTO announcement(employeeId, title, description)
    VALUES(_employeeId, _title, _description);
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateEmployee`(
		IN `_firstname` VARCHAR(255),
        IN `_middlename` VARCHAR(255),
        IN `_lastname` VARCHAR(255),
        IN `_mobileno` VARCHAR(255),
        IN `_gender` VARCHAR(255),
        IN `_email` VARCHAR(255),
        IN `_birthdate` VARCHAR(255),
        IN `_street` VARCHAR(255),
        IN `_city` VARCHAR(255),
        IN `_country` VARCHAR(255),
        IN `_roleId` INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateEmployeeAccount`(
	IN `_username` VARCHAR(255), 
    IN `_password` VARCHAR(255), 
    IN `_qrcode` VARCHAR(255), 
    IN `_employeeId` INT(11), 
    IN `_roleId` INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateMeeting`(
		IN _title VARCHAR(255), IN _organizer int(11),IN _category VARCHAR(255), IN _description VARCHAR(255),
		IN _set_date date, IN _time_start VARCHAR(255), IN _time_end VARCHAR(255), IN _status VARCHAR(255)
	)
BEGIN
	INSERT INTO meeting(title, organizer, category, description, set_date, time_start, time_end, status, created_at)
	VALUES (_title, _organizer, _category, _description, _set_date, _time_start, _time_end, _status, date(now()));
	
	SELECT LAST_INSERT_ID() as id;
	
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateRoleType`(IN _position VARCHAR(255))
BEGIN
	INSERT INTO softype.role(position) VALUES(_position);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateTicket`(IN _employeeId INT(11), IN _title VARCHAR(255), IN _item VARCHAR(255), 
		IN _quantity INT(11))
BEGIN

	INSERT INTO ticket(employeeId, title, item, quantity,created_at)
	VALUES (_employeeId, _title, _item, _quantity, date(now()));
	
	SELECT LAST_INSERT_ID() as id;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateUserType`(IN _user_type VARCHAR(255))
BEGIN
	INSERT INTO accont_type(type) VALUES(_user_type);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteAnnouncement`(IN _announcementId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM announcement
    WHERE id = _announcementId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartment`(IN _departmentId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM department
    WHERE id = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentEmployee`(IN _department_employeeId INT(11))
BEGIN
	DELETE FROM department_employees
    WHERE id = _department_employeeId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentHead`(IN _id INT(11))
BEGIN
	DELETE FROM softype.department_head
    WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentManager`(IN _id INT(11))
BEGIN
	DELETE FROM softype.department_manager
    WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteEmployee`(IN `_id` INT(11))
    NO SQL
BEGIN
	DELETE FROM `employee` WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteMeeting`(IN _id int(11))
BEGIN
	SET sql_safe_updates = 0;
	DELETE meeting, calendar_invites 
    FROM meeting
    INNER JOIN calendar_invites ON meeting.id = calendar_invites.meetingId
    WHERE meeting.id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteMember`(IN _id int(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM calendar_invites
	WHERE memberId = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteTicket`(IN _ticketId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM ticket
    WHERE id = _ticketId;
    
    call retrieveTickets();
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveAnnouncementByDate`()
BEGIN
	SELECT 
		a.id as announcementId,
        a.title as title,
        a.description as description,
        concat(emp.firstname, ' ', emp.lastname) as creator
    from softype.announcement as a
    JOIN softype.employee as emp ON a.employeeId = emp.id
    WHERE date(t.created_at) = date(now());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveAnnouncements`()
BEGIN
	SELECT 
		a.id as announcementId,
        a.title as title,
        a.description as description,
        concat(emp.firstname, ' ', emp.lastname) as creator
    from softype.announcement as a
    JOIN softype.employee as emp ON a.employeeId = emp.id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentEmployees`()
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentHeads`()
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentManagers`()
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartments`()
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeeByDepartment`(IN _departmentId INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeeByManager`(IN _department_managerId INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployees`()
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLeaveRequests`(
	IN _roleID  int(11),
    IN _empId int(11),
    IN _status varchar(50)
)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartment`(IN _departmentId INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentEmployee`(IN _department_employeeId INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentHead`(IN _id INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentManager`(IN _managerId INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedEmployee`(IN _id INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedMeeting`(IN _meetingId int(11))
BEGIN
	SELECT ci.meetingId, m.organizer, ci.memberId, emp.firstname, emp.middlename, emp.lastname, emp.email,
    m.title, m.category, m.description, m.set_date, m.time_start, m.time_end, m.status
    FROM meeting as m 
    INNER JOIN calendar_invites as ci ON m.id = ci.meetingId
    INNER JOIN employee  as emp ON ci.memberId = emp.id
    WHERE m.id = _meetingId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedTicket`(IN _ticketId INT(11))
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date ,t.status,t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE t.id = _ticketId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveMeetingByCurrentDate`()
BEGIN
	SELECT ci.meetingId, m.organizer, ci.memberId, emp.firstname, emp.middlename, emp.lastname, emp.email,
    m.title, m.category, m.description, m.set_date, m.time_start, m.time_end, m.status
    FROM meeting as m 
	LEFT JOIN calendar_invites as ci ON m.id = ci.meetingId
    LEFT JOIN employee  as emp ON ci.memberId = emp.id
    WHERE m.set_date = date(now());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveMeetings`()
BEGIN
	SELECT ci.meetingId, m.organizer, ci.memberId, emp.firstname, emp.middlename, emp.lastname, emp.email,
    m.title, m.category ,m.description, m.set_date, m.time_start, m.time_end, m.status
    FROM meeting as m
    INNER JOIN calendar_invites as ci ON m.id = ci.meetingId
    INNER JOIN employee  as emp ON ci.memberId = emp.id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTickets`()
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, t.item, 
    t.quantity, t.approverId, t.resolve_date, t.status, t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByDate`()
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date ,t.status, t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE date(t.created_at) = date(now());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByMonth`(IN _month VARCHAR(255))
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date ,t.status,t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE MONTH(t.created_at) = _month;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveTicketsByYear`(IN _year VARCHAR(255))
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date , t.status,t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE YEAR(t.created_at) = _year;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveUsers`()
BEGIN
	SELECT * from `user`;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAnnouncement`(IN _announcementId INT(11), IN _title VARCHAR(255), IN _description VARCHAR(255))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartment`(IN _departmentId INT(11), IN _name VARCHAR(255))
BEGIN
	SET sql_safe_updates = 0;
    IF _name IS NOT NULL THEN
    UPDATE department
    SET name = _name WHERE id = _departmentId;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentEmployee`(
	IN _id INT(11), 
    IN _employeeId INT(11), 
    IN _department_managerId INT(11), 
    IN _department_headId INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentHead`(IN _id INT(11), IN _departmentId INT(11), IN _department_head INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentManager`(IN _id INT(11), IN _departmentId INT(11), IN _employeeId INT(11))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateEmployee`(
	IN _employeeId INT(11), 
    IN _firstname VARCHAR(255),
    IN _middlename VARCHAR(255),
    IN _lastname VARCHAR(255),
    IN _mobileno VARCHAR(255),
    IN _gender VARCHAR(255),
    IN _email VARCHAR(255),
    IN _birthdate DATE,
    IN _street VARCHAR(255),
    IN _city VARCHAR(255),
    IN _country VARCHAR(255),
    IN _roleId INT(11)
    )
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateMeeting`(IN _meetingId int(11),
		IN _title VARCHAR(255), IN _organizer int(11),IN _category VARCHAR(255), IN _description VARCHAR(255),
		IN _set_date date, IN _time_start VARCHAR(255), IN _time_end VARCHAR(255), IN _status VARCHAR(255))
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateTicket`(IN _ticketId INT(11), IN _employeeId INT(11), IN _title VARCHAR(255), IN _item VARCHAR(255), 
		IN _quantity INT(11), IN _status INT(11))
BEGIN
	SET sql_safe_updates = 0;
    IF _title IS NOT NULL then
    UPDATE ticket
    SET title = _title WHERE id = _ticketId AND employeeId = _employeeId;
    END IF;
    IF _item IS NOT NULL then
    UPDATE ticket
    SET item = _item WHERE id = _ticketId AND employeeId = _employeeId;
    END IF;
    IF _quantity IS NOT NULL then
    UPDATE ticket
    SET quantity = _quantity WHERE id = _ticketId AND employeeId = _employeeId;
    END IF;
    UPDATE ticket
    SET updated_at = date(now()) WHERE id = _ticketId AND employeeId = _employeeId;
    
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserCreateLeaveRequest`(
	IN _employeeId int(11),
	IN _leave_categoryId int(11),
	IN _date_from date,
	IN _date_to date,
	IN _reason varchar(255),
	IN _approver int(11)
)
BEGIN
	insert into 
		softype.leave_request
		(employeeId, leave_categoryId, date_from, date_to, reason, `status`, approver,created_at) 
    values 
		(_employeeId, _leave_categoryId, _date_from, _date_to, _reason, "pending", _approver,now());
	 SELECT * FROM  softype.leave_request WHERE id = (SELECT LAST_INSERT_ID());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserDoLogin`(
IN `_username` VARCHAR(50), 
IN `_password` VARCHAR(50)
)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserGetProfile`(
	IN _userID int(11)
)
BEGIN
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
        usr.email,
        qr_code ,
        position 
    from 
		softype.employee emp 
	join softype.user usr 
		on emp.id = usr.employeeId 
    join softype.role rl 
		on emp.roleId = rl.id
    where usr.id = _userID;
END$$
DELIMITER ;
