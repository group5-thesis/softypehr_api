DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addDepartmentEmployee`(IN _employeeId INT(11), IN _department_managerId INT(11), _departmentId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    UPDATE department_employees
    SET employeeId = _employeeId
    WHERE department_managerId = _department_managerId AND departmentId = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addDepartmentHead`(IN _departmentId INT(11), IN _employeeId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    UPDATE department as d
    SET department_head = _employeeId
    WHERE d.id = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addDepartmentManager`(IN _departmentId INT(11), IN _department_managerId INT(11) )
BEGIN
    INSERT INTO department_employees(departmentId, department_managerId)
    VALUES (_departmentId, _department_managerId);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addMember`(IN _meetingId int(11), IN _memberId int(11))
BEGIN
	INSERT INTO calendar_invites(meetingId, memberId)
    VALUES (_meetingId, _memberId);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `approveTicket`(IN _ticketId INT(11) ,IN _approverId INT(11), IN _remarks VARCHAR(255))
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `createAnnouncement`(IN _employeeId INT(11), IN _title VARCHAR(255), IN _description VARCHAR(255))
BEGIN
	INSERT INTO announcement(employeeId, title, description)
    VALUES(_employeeId, _title, _description);
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `createDepartment`(IN _name VARCHAR(255), IN _department_headId INT(11))
BEGIN
	INSERT INTO softype.department(name, department_head)
    VALUES(_name, _department_headId);
    
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `createEmployee`(
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `createEmployeeAccount`(
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `createMeeting`(
		IN _title VARCHAR(255), IN _organizer int(11),IN _category VARCHAR(255), IN _description VARCHAR(255),
		IN _set_date date, IN _time_start VARCHAR(255), IN _time_end VARCHAR(255), IN _status VARCHAR(255)
	)
BEGIN
	INSERT INTO meeting(title, organizer, category, description, set_date, time_start, time_end, status, created_at)
	VALUES (_title, _organizer, _category, _description, _set_date, _time_start, _time_end, _status, date(now()));
	
	SELECT * FROM meeting WHERE `id` = (SELECT LAST_INSERT_ID());
	
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `createRoleType`(IN _position VARCHAR(255))
BEGIN
	INSERT INTO softype.role(position) VALUES(_position);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `createTicket`(IN _employeeId INT(11), IN _title VARCHAR(255), IN _item VARCHAR(255), 
		IN _quantity INT(11))
BEGIN
	DECLARE last_insertedId INT;
	INSERT INTO ticket(employeeId, title, item, quantity,created_at)
	VALUES (_employeeId, _title, _item, _quantity, date(now()));
	
	SELECT `id` FROM ticket WHERE `id` = (SELECT LAST_INSERT_ID());

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `createUserType`(IN _user_type VARCHAR(255))
BEGIN
	INSERT INTO accont_type(type) VALUES(_user_type);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteAnnouncement`(IN _announcementId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM announcement
    WHERE id = _announcementId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteDepartment`(IN _departmentId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM department
    WHERE id = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMeeting`(IN _id int(11))
BEGIN
	SET sql_safe_updates = 0;
	DELETE meeting, calendar_invites 
    FROM meeting
    INNER JOIN calendar_invites ON meeting.id = calendar_invites.meetingId
    WHERE meeting.id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMember`(IN _id int(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM calendar_invites
	WHERE memberId = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteTicket`(IN _ticketId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM ticket
    WHERE id = _ticketId;
    
    call retrieveTickets();
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveAnnouncementByDate`()
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveAnnouncements`()
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveDepartments`()
BEGIN
	SELECT 
		dept.id as departmentId,
		dept.name as department_name,
        dept.department_head as department_headId,
        concat(emp.firstname, ' ', emp.lastname) as department_head
    from department as dept
    JOIN softype.employee as emp ON emp.id = dept.department_head;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveEmployeeByDepartment`(IN _departmentId INT(11))
BEGIN
	SELECT
		emp.id as employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		des.id as department_id,
		des.name as department_name,
		des.department_head as department_headId,
		concat(ed.firstname,' ', ed.lastname) as department_head,
        dept_emp.department_managerId as department_managerId,
        concat(e.firstname,' ', e.lastname) as department_manager,
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
    LEFT JOIN softype.employee as e ON dept_emp.department_managerId = e.id
    LEFT JOIN softype.department as des ON dept_emp.departmentId = des.id
    LEFT JOIN softype.employee as ed ON des.department_head = ed.id
    WHERE dept_emp.departmentId = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveEmployeeByManager`(IN _department_managerId INT(11))
BEGIN
SELECT
	emp.id as employeeId,
	u.id as userId,
	u.account_type as accountType,
	r.position as role,
	des.id as department_id,
	des.name as department_name,
	des.department_head as department_headId,
	concat(ed.firstname,' ', ed.lastname) as department_head,
	dept_emp.department_managerId as department_managerId,
	concat(e.firstname,' ', e.lastname) as department_manager,
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
    LEFT JOIN softype.employee as e ON dept_emp.department_managerId = e.id
    LEFT JOIN softype.department as des ON dept_emp.departmentId = des.id
    LEFT JOIN softype.employee as ed ON des.department_head = ed.id
    WHERE dept_emp.department_managerId = _department_managerId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveEmployees`()
BEGIN
SELECT
		emp.id as employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		des.id as department_id,
		des.name as department_name,
		des.department_head as department_headId,
		concat(ed.firstname,' ', ed.lastname) as department_head,
        dept_emp.department_managerId as department_managerId,
        concat(e.firstname,' ', e.lastname) as department_manager,
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
    LEFT JOIN softype.employee as e ON dept_emp.department_managerId = e.id
    LEFT JOIN softype.department as des ON dept_emp.departmentId = des.id
    LEFT JOIN softype.employee as ed ON des.department_head = ed.id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveLimitedDepartment`(IN _departmentId INT(11))
BEGIN
	SELECT 
		dept.id as departmentId,
		dept.name as department_name,
        dept.department_head as department_headId,
        concat(emp.firstname, ' ', emp.lastname) as department_head
    from department as dept
    JOIN softype.employee as emp ON emp.id = dept.department_head
    WHERE dept.id = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveLimitedEmployee`(IN _id INT(11))
BEGIN
SELECT
		emp.id as employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		des.id as department_id,
		des.name as department_name,
		des.department_head as department_headId,
		concat(ed.firstname,' ', ed.lastname) as department_head,
        dept_emp.department_managerId as department_managerId,
        concat(e.firstname,' ', e.lastname) as department_manager,
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
    LEFT JOIN softype.employee as e ON dept_emp.department_managerId = e.id
    LEFT JOIN softype.department as des ON dept_emp.departmentId = des.id
    LEFT JOIN softype.employee as ed ON des.department_head = ed.id
    WHERE emp.id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveLimitedMeeting`(IN _meetingId int(11))
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveLimitedTicket`(IN _ticketId INT(11))
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date ,t.status,t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE t.id = _ticketId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveMeetingByCurrentDate`()
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveMeetings`()
BEGIN
	SELECT ci.meetingId, m.organizer, ci.memberId, emp.firstname, emp.middlename, emp.lastname, emp.email,
    m.title, m.category ,m.description, m.set_date, m.time_start, m.time_end, m.status
    FROM meeting as m
    INNER JOIN calendar_invites as ci ON m.id = ci.meetingId
    INNER JOIN employee  as emp ON ci.memberId = emp.id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveTickets`()
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, t.item, 
    t.quantity, t.approverId, t.resolve_date, t.status, t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveTicketsByDate`()
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date ,t.status, t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE date(t.created_at) = date(now());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveTicketsByMonth`(IN _month VARCHAR(255))
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date ,t.status,t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE MONTH(t.created_at) = _month;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveTicketsByYear`(IN _year VARCHAR(255))
BEGIN
	SELECT t.id, emp.firstname, emp.middlename, emp.lastname, emp.email, t.title, 
    t.item, t.quantity, t.approverId, t.resolve_date , t.status,t.remarks, t.created_at, t.updated_at
    FROM ticket as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    WHERE YEAR(t.created_at) = _year;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `retrieveUsers`()
BEGIN
	SELECT * from `user`;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateAnnouncement`(IN _announcementId INT(11), IN _title VARCHAR(255), IN _description VARCHAR(255))
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateDepartment`(IN _departmentId INT(11), IN _name VARCHAR(255), IN _department_head INT(11))
BEGIN
	SET sql_safe_updates = 0;
    IF _name IS NOT NULL THEN
    UPDATE department
    SET name = _name WHERE id = _departmentId;
    END IF;
	IF _department_head IS NOT NULL THEN
    UPDATE department
    SET department_head = _department_head WHERE id = _departmentId;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateMeeting`(IN _meetingId int(11),
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateTicket`(IN _ticketId INT(11), IN _employeeId INT(11), IN _title VARCHAR(255), IN _item VARCHAR(255), 
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserDoLogin`(IN `_username` VARCHAR(50), IN `_password` VARCHAR(50))
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
		(users.username = _username OR emp.email = _username) and users.`password` = _password;
        
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
