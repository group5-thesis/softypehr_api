DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartment`(IN `_name` VARCHAR(255))
BEGIN
	INSERT INTO softype.department(name)
    VALUES(_name);
    
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentEmployee`(IN `_employeeId` INT(11), IN `_department_managerId` INT(11), IN `_department_headId` INT(11))
BEGIN
    INSERT INTO department_employees(employeeId, department_managerId, department_headId, created_at)
    VALUES(_employeeId, _department_managerId, _department_headId, date(now()));
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentHead`(IN `_departmentId` INT(11), IN `_department_head` INT(11))
BEGIN
	INSERT INTO department_head(departmentId, department_head, created_at)
    VALUES(_departmentId, _department_head, date(now()));
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddDepartmentManager`(IN `_departmentId` INT(11), IN `_department_manager` INT(11))
BEGIN
    INSERT INTO department_manager(departmentId, department_manager, created_at)
    VALUES (_departmentId, _department_manager, date(now()));
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddFile`(IN `_employeeId` INT(11), IN `_name` VARCHAR(255), IN `_path` VARCHAR(255), IN `_type` VARCHAR(255), IN `_description` VARCHAR(255))
BEGIN
	INSERT INTO company_repository(uploadedBy, filename, path, type, description, created_at)
    VALUES(_employeeId, _name , _path, _type, _description, date(now()));
    
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddMember`(IN `_meetingId` INT(11), IN `_memberId` INT(11))
BEGIN
	INSERT INTO calendar_invites(meetingId, memberId)
    VALUES (_meetingId, _memberId);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddRecoveryCode`(
	IN _email varchar(255),
    IN _code varchar  (50),
    IN _created varchar(50)
)
BEGIN
     declare _count int;
	 select count(email)   into _count from softype.account_recovery where email = _email ; 
	 if _count < 1 then
	    INSERT INTO softype.account_recovery(`email` , `code` ,`created_at`) values (_email , _code ,_created);
	 else 
        update softype.account_recovery set `code` = _code , `created_at` = _created where email  = _email;
	end if;
    select "ok" as result;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckUserEmail`(
	IN _email varchar(50)
)
BEGIN
	declare isExist int ;
    set isExist =0;
    select count(id) into isExist from  softype.employee where email = _email;
    select isExist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CloseOfficeRequest`(IN `_office_requestId` INT(11), IN `_approverId` INT(11), IN `_indicator` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    IF _indicator = 1 THEN
    UPDATE office_request
    SET approverId = _approverId, status = 0, resolve_date = date(now()), remarks = "Request Approved"
    WHERE id = _office_requestId;
    END IF;	
    IF _indicator = 0 THEN
	UPDATE office_request
    SET approverId = _approverId, status = 0, resolve_date = date(now()), remarks = "Request Rejected"
    WHERE id = _office_requestId;
    END IF;
    
    SELECT _office_requestId as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateAnnouncement`(IN `_employeeId` INT(11), IN `_title` VARCHAR(255), IN `_description` VARCHAR(255))
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
        IN `_phil_health_no` VARCHAR(255),
        IN `_sss_no` VARCHAR(255),
        IN `_pag_ibig_no` VARCHAR(255),
        IN `_role` VARCHAR(255))
BEGIN
	DECLARE _roleId int(11);
    IF NOT EXISTS(SELECT id FROM softype.`role` WHERE position = _role)
	THEN
		INSERT INTO softype.`role` (position) VALUES (_role);
		SELECT last_insert_id() into _roleId;
	ELSE
		SELECT id FROM softype.`role` WHERE position = _role into _roleId;
	END If;
    
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
			roleId,
            phil_health_no,
            sss_no,
            pag_ibig_no
		)
	VALUES (_firstname, _middlename, _lastname, _mobileno, _gender, _email, _birthdate, _street, _city, _country, _roleId, _phil_health_no,_sss_no,_pag_ibig_no);
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
        _roleId,
        _employeeId
	);
    SELECT * FROM `user` WHERE id = (SELECT LAST_INSERT_ID());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateMeeting`(IN `_title` VARCHAR(255), IN `_organizer` INT(11), IN `_category` VARCHAR(255), IN `_description` VARCHAR(255), IN `_set_date` DATE, IN `_time_start` VARCHAR(255), IN `_time_end` VARCHAR(255), IN `_status` VARCHAR(255))
BEGIN
	INSERT INTO meeting(title, organizer, category, description, set_date, time_start, time_end, status, created_at)
	VALUES (_title, _organizer, _category, _description, _set_date, _time_start, _time_end, _status, date(now()));
	
	SELECT LAST_INSERT_ID() as id;
	
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateOfficeRequest`(
	IN `_transac` VARCHAR(255), 
    IN `_employeeId` INT(11), 
    IN `_item` VARCHAR(255), 
    IN `_quantity` INT(11),
    IN `_price` DOUBLE,
    IN `_total_price` DOUBLE,
    IN `_date_needed` DATE)
BEGIN
    DECLARE _approver INT(11);
    
	INSERT INTO office_request(transaction_no, employeeId, item, quantity, approverId, price, total_price, date_needed)
	VALUES (_transac, _employeeId, _item, _quantity, GetAdmin(), _price, _total_price, _date_needed );

	SELECT LAST_INSERT_ID() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreatePerformanceReview`(
		IN `_c1` INT(11), 
        IN `_c2` INT(11), 
        IN `_c3` INT(11), 
        IN `_c4` INT(11), 
        IN `_c5` INT(11), 
        IN `_employee_reviewed` INT(11),
		IN `_reviewer` INT(11)
	)
BEGIN
	INSERT INTO softype.performance_review(c1, c2, c3, c4, c5, employee_reviewed, reviewer)
    VALUES(_c1, _c2, _c3, _c4, _c5, _employee_reviewed, _reviewer);
    
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateRoleType`(IN `_position` VARCHAR(255))
BEGIN
	INSERT INTO softype.role(position) VALUES(_position);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteAccountRecoveryCode`(
IN _email varchar(255)
)
BEGIN
	delete from softype.account_recovery where email = _email;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteAnnouncement`(IN `_announcementId` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM announcement
    WHERE id = _announcementId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartment`(IN `_departmentId` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM department
    WHERE id = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentEmployee`(IN `_department_employeeId` INT(11))
BEGIN
	DELETE FROM department_employees
    WHERE id = _department_employeeId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentHead`(IN `_id` INT(11))
BEGIN
	DELETE FROM softype.department_head
    WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteDepartmentManager`(IN `_id` INT(11))
BEGIN
	DELETE FROM softype.department_manager
    WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteEmployee`(IN `_id` INT(11))
BEGIN
	DELETE FROM `employee` WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteFile`(IN `_id` INT(11))
BEGIN
	DELETE FROM company_repository
    WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteMeeting`(IN `_id` INT(11))
BEGIN
	SET sql_safe_updates = 0;
	DELETE meeting, calendar_invites 
    FROM meeting
    INNER JOIN calendar_invites ON meeting.id = calendar_invites.meetingId
    WHERE meeting.id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteMember`(IN `_id` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM calendar_invites
	WHERE memberId = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeletePerformanceReview`(IN `_id` INT(11))
BEGIN
	DELETE FROM `performance_review` WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeletOfficeRequest`(IN `_officeRequestId` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM office_request
    WHERE id = _officeRequestId;
    
    call RetrieveOfficeRequests();
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveByFileType`(IN `_type` VARCHAR(255))
BEGIN
	SELECT 
		cr.id as file_id,
		cr.created_at as uploadedAt,
        cr.filename as filename,
        cr.path as path,
        cr.type as type,
        cr.description as description,
        concat(emp.firstname, ' ', emp.lastname) as uploadedBy
    FROM softype.company_repository as cr
    JOIN softype.employee as emp ON emp.id = cr.uploadedBy
    WHERE cr.type = _type
    ORDER BY cr.created_at DESC;
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
		deh.id as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.id as department_managerId,
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
        emp.phil_health_no as phil_health_no,
        emp.sss_no as sss,
        emp.pag_ibig_no as pag_ibig_no,
		emp.profile_img as profile_img,
        emp.isActive as isActive,
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
    WHERE dept_emp.employeeId IS NOT NULL
        ORDER BY emp.lastname ASC;
    
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentEmployeesWithPerformanceReviews`(IN _department_headId INT(11))
BEGIN
	SELECT *
		#emp.id as department_employeeId
		# dept employees
		FROM softype.employee as emp
		LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
        LEFT JOIN softype.performance_review as emp_pr ON emp_pr.employee_reviewed = emp.id
		ORDER BY emp_pr.date_reviewed DESC;
		# dept managers
        #LEFT JOIN softype.department_head ;
         
		
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartmentHeads`()
BEGIN
	SELECT
		emp_h.id as employeeId,
        dept_h.id as department_headId,
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
        emp_h.phil_health_no as phil_health_no,
        emp_h.sss_no as sss,
        emp_h.pag_ibig_no as pag_ibig_no,
        emp.profile_img as profile_img,
        emp_h.isActive as isActive,
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
        dept_h.id as department_headId,
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
        emp_m.phil_health_no as phil_health_no,
        emp_m.sss_no as sss,
        emp_m.pag_ibig_no as pag_ibig_no,
        emp_m.isActive as isActive,
		u.qr_code as manager_qrcode,
        emp_m.profile_img as profile_img
	from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
	JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
	JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
    LEFT JOIN softype.role as r ON emp_m.roleId = r.id
    LEFT JOIN softype.user as u ON emp_m.id = u.employeeId;
    #LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId
	#LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId;
	#from softype.department as dept
	#LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    #LEFT JOIN softype.department_manager as dept_m ON dept_m.departmentId = dept.id
    #JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
	#JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    #JOIN softype.role as r ON emp_m.roleId = r.id
    #JOIN softype.user as u ON emp_m.id = u.employeeId
    #LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    #LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveDepartments`()
BEGIN
	SELECT 
		dept.id as department_id,
        dept.name as department_name,
        emp_h.id as department_head_employeeId,
        concat(emp_h.firstname,' ', emp_h.lastname) as department_head
        #concat(emp_m.firstname,' ', emp_m.lastname) as department_manager,
        #concat(emps.firstname, ' ', emps.lastname) as department_employee
    from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    #LEFT JOIN softype.department_manager as dept_m ON dept_m.departmentId = dept.id
    #LEFT JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    #LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    #LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId
	#LEFT JOIN softype.department_employees as dept_emps ON dept_emps.employeeId = emps.id
    #LEFT JOIN softype.employee as emps ON dept_emp_h.employeeId = emps.id
    ORDER BY department_name ASC;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeeByDepartment`(IN `_departmentId` INT(11))
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
        emp.phil_health_no as phil_health_no,
        emp.sss_no as sss,
        emp.pag_ibig_no as pag_ibig_no,
        emp.isActive as isActive,
		u.qr_code as qrcode,
		emp.profile_img as profile_img

        
	FROM softype.employee as emp
    LEFT JOIN softype.role as r ON r.id = emp.roleId
    LEFT JOIN softype.user as u ON u.employeeId = emp.id
    LEFT JOIN softype.department_employees as dept_emp ON dept_emp.employeeId = emp.id
    LEFT JOIN softype.department_manager as dm ON dm.department_manager = emp.id
	LEFT JOIN softype.department_head as dh ON dh.department_head = emp.id
	LEFT JOIN softype.department_manager as dem ON dem.id = dept_emp.department_managerId
	LEFT JOIN softype.department_head as deh ON deh.id = dept_emp.department_headId
    LEFT JOIN softype.employee as emp_m ON dem.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON deh.department_head = emp_h.id
    LEFT JOIN softype.department as dept ON dem.departmentId = dept.id
    WHERE dept.id = _departmentId
     ORDER BY emp.lastname ASC;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeeByManager`(IN `_department_managerId` INT(11))
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
        emp.phil_health_no as phil_health_no,
        emp.sss_no as sss_no,
        emp.pag_ibig_no as pag_ibig_no,
        emp.isActive as isActive,
        emp.profile_img as profile_img,
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeePerformanceReviewByMonth`(IN _employee_reviewed INT(11), IN _date_reviewed DATE)
BEGIN
	SELECT
		pr_emp.id as performance_reviewId,
		emp.id as employee_reviewedId,
        emp_r.id as reviewerId, 
        pr_emp.c1 as criteria_1,
        pr_emp.c2 as criteria_2,
        pr_emp.c3 as criteria_3,
        pr_emp.c4 as criteria_4,
        pr_emp.c5 as criteria_5,
        concat(emp.firstname,' ', emp.lastname) as employee_reviewed,
        concat(emp_r.firstname,' ', emp_r.lastname) as reviewer,
        pr_emp.date_reviewed as date_reviewed
    FROM softype.employee as emp
    LEFT JOIN softype.performance_review as pr_emp ON emp.id = pr_emp.employee_reviewed
    LEFT JOIN softype.employee as emp_r ON emp_r.id = pr_emp.reviewer
    WHERE pr_emp.employee_reviewed = _employee_reviewed AND 
    YEAR(pr_emp.date_reviewed) = YEAR(_date_reviewed) AND
    MONTH(pr_emp.date_reviewed) = MONTH(_date_reviewed);

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployeePerformanceReviews`()
BEGIN
	SELECT 
		pr_emp.id as performance_reviewId,
        emp.id as employee_reviewedId,
        emp_r.id as reviewerId, 
		pr_emp.c1 as criteria_1,
		pr_emp.c2 as criteria_2,
		pr_emp.c3 as criteria_3,
		pr_emp.c4 as criteria_4,
		pr_emp.c5 as criteria_5,
		concat(emp.firstname, ' ', emp.lastname) as employee_reviewed,
        concat(emp_r.firstname, ' ', emp_r.lastname) as reviewer,
        pr_emp.date_reviewed as date_reviewed
    FROM softype.performance_review as pr_emp
    LEFT JOIN softype.employee as emp ON pr_emp.employee_reviewed = emp.id
    LEFT JOIN softype.employee as emp_r ON pr_emp.reviewer = emp_r.id;
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
		deh.id as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.id as department_managerId,
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
        emp.phil_health_no as phil_health_no,
        emp.sss_no as sss,
        emp.pag_ibig_no as pag_ibig_no,
        emp.isActive as isActive,
        emp.profile_img as profile_img,
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveFiles`()
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLeaveRequests`(IN `_roleID` INT(11), IN `_empId` INT(11), IN `_status` VARCHAR(50))
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
		SELECT emp.id AS employee_id, 
       Concat (emp.firstname, "", emp.lastname) AS `name`, 
       `status`, 
       date_from, 
       date_to, 
       reason, 
       category, 
       Concat (emp1.firstname, " ", emp1.lastname) AS approver, 
       lr.approver AS approver_id 
       FROM   softype.leave_request lr 
       JOIN softype.leave_category lc 
         ON lr.leave_categoryid = lc.id 
       JOIN softype.employee emp 
         ON lr.employeeid = emp.id 
       JOIN softype.employee emp1 
         ON lr.approver = emp1.id 
		WHERE YEAR(lr.created_at) = YEAR(NOW())
        ORDER BY  lr.created_at desc
        ;
    end if;
    
     if _roleID = 2 then 
		SELECT emp.id AS employee_id, 
       Concat (emp.firstname, "", emp.lastname) AS `name`, 
       `status`, 
       date_from, 
       date_to, 
       reason, 
       category, 
       Concat (emp1.firstname, " ", emp1.lastname) AS approver, 
       lr.approver AS approver_id 
       FROM   softype.leave_request lr 
       JOIN softype.leave_category lc 
         ON lr.leave_categoryid = lc.id 
       JOIN softype.employee emp 
         ON lr.employeeid = emp.id 
       JOIN softype.employee emp1 
         ON lr.approver = emp1.id 
	  WHERE lr.approver= _empId 
      AND YEAR(lr.created_at) = YEAR(NOW())
     #   AND lr.`status` =  _status
        ORDER BY  lr.created_at desc;
    end if;
    
     if _roleID = 3 then 
		SELECT emp.id AS employee_id, 
       Concat (emp.firstname, "", emp.lastname) AS `name`, 
       `status`, 
       date_from, 
       date_to, 
       reason, 
       category, 
       Concat (emp1.firstname, " ", emp1.lastname) AS approver, 
       lr.approver AS approver_id 
       FROM   softype.leave_request lr 
       JOIN softype.leave_category lc 
         ON lr.leave_categoryid = lc.id 
       JOIN softype.employee emp 
         ON lr.employeeid = emp.id 
       JOIN softype.employee emp1 
         ON lr.approver = emp1.id 
	  WHERE emp.id = _empId AND
      YEAR(lr.created_at) = YEAR(NOW())
        # AND lr.`status` =  _status
	  ORDER BY  lr.created_at desc;
     end if;
    
    
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartment`(IN `_departmentId` INT(11))
BEGIN
	SELECT 
		dept.id as department_id,
        dept.name as department_name,
        emp_h.id as department_head_employeeId,
        concat(emp_h.firstname,' ', emp_h.lastname) as department_head
    from softype.department as dept
    LEFT JOIN softype.department_head as dept_h ON dept_h.departmentId = dept.id
    LEFT JOIN softype.department_manager as dept_m ON dept_m.departmentId = dept.id
    LEFT JOIN softype.employee as emp_m ON dept_m.department_manager = emp_m.id
    LEFT JOIN softype.employee as emp_h ON dept_h.department_head = emp_h.id
    LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId
    LEFT JOIN softype.employee as emps ON dept_emp_h.employeeId = emps.id
    WHERE dept.id = _departmentId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentEmployee`(IN `_department_employeeId` INT(11))
BEGIN
	SELECT
		emp.id as employeeId,
        dept_emp.id as department_employeeId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
		dept.name as department_name,
		deh.id as department_headId,
		concat(emp_h.firstname,' ', emp_h.lastname) as department_head,
        dem.id as department_managerId,
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
    WHERE dept_emp.id = _department_employeeId
    ORDER BY emp.lastname ;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentHead`(IN `_id` INT(11))
BEGIN
	SELECT
		emp_h.id as employeeId,
        dept_h.id as department_headId,
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedDepartmentManager`(IN `_managerId` INT(11))
BEGIN
	SELECT
		emp_m.id as employeeId,
        dept_m.id as managerId,
		u.id as userId,
		u.account_type as accountType,
		r.position as role,
		dept.id as department_id,
        dept.name as department_name,
        dept_h.id as department_headId,
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
    #LEFT JOIN softype.department_employees as dept_emp_h ON dept_h.id = dept_emp_h.department_headId
    #LEFT JOIN softype.department_employees as dept_emp_m ON dept_m.id = dept_emp_m.department_managerId
    WHERE dept_m.id = _managerId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedEmployee`(IN `_id` INT(11))
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
		u.qr_code as qrcode,
	    emp.sss_no as sss,
        emp.pag_ibig_no as pag_ibig_no ,
        emp.phil_health_no as phil_health_no ,
        emp.profile_img as profile_img
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedMeeting`(IN `_meetingId` INT(11))
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedOfficeRequest`(IN `_officeRequestId` INT(11))
BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.item as item,
		t.quantity as quantity,
        t.price as price,
        t.total_price as total_price,
        t.date_needed as date_needed,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM office_request as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.id = _officeRequestId;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedPerformanceReview`(IN `_id` INT(11))
BEGIN
	SELECT 
    	pr.id as performance_review_id,
        emp_reviewed.id as employee_reviewedId, 
        emp_reviewer.id as reviewerId, 
        pr.c1 as criteria_1,
        pr.c2 as criteria_2,
        pr.c3 as criteria_3,
        pr.c4 as criteria_4,
        pr.c5 as criteria_5,
		concat(emp_reviewed.firstname,' ', emp_reviewed.lastname) as employee_reviewed,
        concat(emp_reviewer.firstname,' ', emp_reviewer.lastname) as employee_reviewer,
        pr.date_reviewed as date_reviewed
	FROM softype.performance_review as pr
	JOIN softype.employee as emp_reviewed on emp_reviewed.id = pr.employee_reviewed
	JOIN softype.employee as emp_reviewer ON emp_reviewer.id = pr.reviewer
	WHERE pr.id = _id;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveOfficeRequests`()
BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.item as item,
		t.quantity as quantity,
        t.price as price,
        t.total_price as total_price,
        t.date_needed as date_needed,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM office_request as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    ORDER BY  t.created_at desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveOfficeRequestsByDate`()
BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.item as item,
		t.quantity as quantity,
        t.price as price,
        t.total_price as total_price,
        t.date_needed as date_needed,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM office_request as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.created_at = date(now())
    ;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveOfficeRequestsByEmployee`(IN `_employeeId` INT(11))
BEGIN
	SELECT 
		emp.id as employeeId,
		t.id as office_requestId, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.item as item,
		t.quantity as quantity,
        t.price as price,
        t.total_price as total_price,
        t.date_needed as date_needed,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM office_request as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.employeeId = _employeeId
    ORDER BY  t.created_at desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveOfficeRequestsByMonth`(IN `_month` VARCHAR(255))
BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.item as item,
		t.quantity as quantity,
        t.price as price,
        t.total_price as total_price,
        t.date_needed as date_needed,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM office_request as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE MONTH(t.created_at) = _month
    ORDER BY  t.created_at desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveOfficeRequestsByStatus`(IN `_status` INT(11))
BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.item as item,
		t.quantity as quantity,
        t.price as price,
        t.total_price as total_price,
        t.date_needed as date_needed,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM office_request as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE t.status = _status
    ORDER BY  t.created_at desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveOfficeRequestsByYear`(IN `_year` VARCHAR(255))
BEGIN
	SELECT 
		t.id, 
        t.transaction_no as transaction_no,
        concat(emp.firstname , ' ', emp.lastname) as name, 
        emp.email,
        t.item as item,
		t.quantity as quantity,
        t.price as price,
        t.total_price as total_price,
        t.date_needed as date_needed,
        concat(emp_app.firstname, ' ', emp_app.lastname) as person_in_charge,
        t.resolve_date as resolved_date,
        t.status as status,
        t.remarks as remarks,
        t.created_at as date_requested
    FROM office_request as t
    INNER JOIN employee as emp ON t.employeeId = emp.id
    INNER JOIN employee as emp_app ON t.approverId = emp_app.id
    WHERE YEAR(t.created_at) = _year
    ORDER BY  t.created_at desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrievePerformanceReviewByEmployee`(IN `_id` INT(11))
BEGIN
	SELECT
		pr_emp.id as performance_reviewId,
		emp.id as employee_reviewedId,
        emp_r.id as reviewerId,
        pr_emp.c1 as criteria_1,
        pr_emp.c2 as criteria_2,
        pr_emp.c3 as criteria_3,
        pr_emp.c4 as criteria_4,
        pr_emp.c5 as criteria_5,
        concat(emp.firstname,' ', emp.lastname) as employee_reviewed,
        concat(emp_r.firstname,' ', emp_r.lastname) as reviewer,
        pr_emp.date_reviewed as date_reviewed
    FROM softype.employee as emp
    LEFT JOIN softype.performance_review as pr_emp ON emp.id = pr_emp.employee_reviewed
    LEFT JOIN softype.employee as emp_r ON emp_r.id = pr_emp.reviewer
    WHERE pr_emp.employee_reviewed = _id
    ORDER BY pr_emp.date_reviewed desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrievePerformanceReviewByMonth`()
BEGIN
	SELECT
		MONTH(pr_emp.date_reviewed) as month_reviewed,
		pr_emp.id as performance_reviewId,
		emp.id as employee_reviewedId,
        emp_r.id as reviewerId,
        pr_emp.c1 as criteria_1,
        pr_emp.c2 as criteria_2,
        pr_emp.c3 as criteria_3,
        pr_emp.c4 as criteria_4,
        pr_emp.c5 as criteria_5,
        concat(emp.firstname,' ', emp.lastname) as employee_reviewed,
        concat(emp_r.firstname,' ', emp_r.lastname) as reviewer,
        pr_emp.date_reviewed as date_reviewed
    FROM softype.employee as emp
    LEFT JOIN softype.performance_review as pr_emp ON emp.id = pr_emp.employee_reviewed
    LEFT JOIN softype.employee as emp_r ON emp_r.id = pr_emp.reviewer
    GROUP BY MONTH(pr_emp.date_reviewed)
    ORDER BY pr_emp.date_reviewed desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrievePerformanceReviewByReviewer`(IN _reviewer INT(11))
BEGIN
	SELECT
		pr_emp.id as performance_reviewId,
		emp.id as employee_reviewedId,
        emp_r.id as reviewerId,
        pr_emp.c1 as criteria_1,
        pr_emp.c2 as criteria_2,
        pr_emp.c3 as criteria_3,
        pr_emp.c4 as criteria_4,
        pr_emp.c5 as criteria_5,
        concat(emp.firstname,' ', emp.lastname) as employee_reviewed,
        concat(emp_r.firstname,' ', emp_r.lastname) as reviewer,
        pr_emp.date_reviewed as date_reviewed
    FROM softype.employee as emp
    LEFT JOIN softype.performance_review as pr_emp ON emp.id = pr_emp.employee_reviewed
    LEFT JOIN softype.employee as emp_r ON emp_r.id = pr_emp.reviewer
    WHERE pr_emp.reviewer = _reviewer
   ORDER BY pr_emp.date_reviewed desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrievePerformanceReviewByYear`()
BEGIN
	SELECT
		Year(pr_emp.date_reviewed) as year_reviewed,
		pr_emp.id as performance_reviewId,
		emp.id as employee_reviewedId,
        emp_r.id as reviewerId,
        pr_emp.c1 as criteria_1,
        pr_emp.c2 as criteria_2,
        pr_emp.c3 as criteria_3,
        pr_emp.c4 as criteria_4,
        pr_emp.c5 as criteria_5,
        concat(emp.firstname,' ', emp.lastname) as employee_reviewed,
        concat(emp_r.firstname,' ', emp_r.lastname) as reviewer,
        pr_emp.date_reviewed as date_reviewed
    FROM softype.employee as emp
    LEFT JOIN softype.performance_review as pr_emp ON emp.id = pr_emp.employee_reviewed
    LEFT JOIN softype.employee as emp_r ON emp_r.id = pr_emp.reviewer
    GROUP BY YEAR(pr_emp.date_reviewed)
    ORDER BY pr_emp.date_reviewed desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveUsers`()
BEGIN
	SELECT * from `user` ORDER BY account_type;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `TimeIn`(IN `_employeeId` INT(11), IN `_timeIn` TIME)
BEGIN
	INSERT INTO employee_attendance(employeeId, time_in, time_out, date, no_of_hours)
    VALUES(_employeeId, _timeIn, '00:00:00', date(now()), 0);
    
    SELECT last_insert_id() as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAnnouncement`(IN `_announcementId` INT(11), IN `_title` VARCHAR(255), IN `_description` VARCHAR(255))
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartment`(IN `_departmentId` INT(11), IN `_name` VARCHAR(255))
BEGIN
	SET sql_safe_updates = 0;
    SET @update_id := 0;
    IF _name IS NOT NULL THEN
    UPDATE department
    SET name = _name  , id = (SELECT @update_id := id)
    WHERE id = _departmentId;
    END IF;
    SELECT @update_id as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentEmployee`(IN `_id` INT(11), IN `_employeeId` INT(11), IN `_department_managerId` INT(11), IN `_department_headId` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    SET @update_id := 0;
    IF _employeeId IS NOT NULL THEN
    UPDATE department_employees
    SET employeeId = _employeeId , id = (SELECT @update_id := id)
    WHERE id = _id;
    END IF;
	IF _department_managerId IS NOT NULL THEN
    UPDATE department_employees
    SET department_managerId = _department_managerId , id = (SELECT @update_id := id)
    WHERE id = _id;
    END IF;
    IF _department_headId IS NOT NULL THEN
    UPDATE department_employees
    SET department_headId = _department_headId , id = (SELECT @update_id := id)
    WHERE id = _id;
    END IF;
    UPDATE department_employees
    SET updated_at = date(now()) , id = (SELECT @update_id := id)
    WHERE id = _id;
    SELECT @update_id as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentHead`(IN `_id` INT(11), IN `_departmentId` INT(11), IN `_department_head` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    SET @update_id := 0;
    IF _departmentId IS NOT NULL THEN
    UPDATE department_head
    SET departmentId = _departmentId, id = (SELECT @update_id := id)
    WHERE id = _id;
    END IF;
    IF _department_head IS NOT NULL THEN
    UPDATE department_head
    SET department_head = _department_head, id = (SELECT @update_id := id)
    WHERE id = _id;
    END IF;
	UPDATE department_head
    SET updated_at = date(now()), id = (SELECT @update_id := id)
    WHERE id = _id;
	SELECT @update_id as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateDepartmentManager`(IN `_id` INT(11), IN `_departmentId` INT(11), IN `_employeeId` INT(11))
BEGIN
	SET sql_safe_updates = 0;
    SET @update_id := 0;
    IF _departmentId IS NOT NULL THEN
    UPDATE department_manager 
    SET departmentId = _departmentId , id = (SELECT @update_id := id)
    WHERE id = _id;
    END IF;
    IF _employeeId IS NOT NULL THEN
    UPDATE department_manager
    SET department_manager = _employeeId, id = (SELECT @update_id := id)
    WHERE id = _id;
    END IF;
    UPDATE department_manager
    SET updated_at = date(now()), id = (SELECT @update_id := id)
    WHERE id = _id;
    SELECT @update_id as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateEmployee`(
	IN `_employeeId` INT(11), 
    IN `_firstname` VARCHAR(255), 
    IN `_middlename` VARCHAR(255), 
    IN `_lastname` VARCHAR(255), 
    IN `_mobileno` VARCHAR(255), 
    IN `_gender` VARCHAR(255), 
    IN `_email` VARCHAR(255), 
    IN `_birthdate` DATE, 
    IN `_street` VARCHAR(255), 
    IN `_city` VARCHAR(255), 
    IN `_country` VARCHAR(255), 
    IN `_phil_health_no` VARCHAR(255),
    IN `_sss_no` VARCHAR(255),
    IN `_pag_ibig_no` VARCHAR(255),
    IN `_isActive` INT(11),
    IN `_roleId` INT(11))
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
    IF _phil_health_no IS NOT NULL THEN
    UPDATE employee
    SET phil_health_no = _phil_health_no, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _sss_no IS NOT NULL THEN
    UPDATE employee
    SET sss_no = _sss_no, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _pag_ibig_no IS NOT NULL THEN
    UPDATE employee
    SET pag_ibig_no = _pag_ibig_no, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    IF _isActive IS NOT NULL THEN
    UPDATE employee
    SET isActive = _isActive, id = (SELECT @update_id := id)
    WHERE id = _employeeId;
    END IF;
    SELECT @update_id as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateMeeting`(IN `_meetingId` INT(11), IN `_title` VARCHAR(255), IN `_organizer` INT(11), IN `_category` VARCHAR(255), IN `_description` VARCHAR(255), IN `_set_date` DATE, IN `_time_start` VARCHAR(255), IN `_time_end` VARCHAR(255), IN `_status` VARCHAR(255))
BEGIN
	SET sql_safe_updates = 0;
    SET @update_id := 0;
    IF _title IS NOT NULL then
    UPDATE meeting
    SET title = _title , id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    IF _organizer IS NOT NULL then
    UPDATE meeting
    SET organizer = _organizer , id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    IF _category IS NOT NULL then
    UPDATE meeting
    SET category = _category , id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    IF _description IS NOT NULL then
    UPDATE meeting
    SET description = _description , id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    IF _set_date IS NOT NULL then
    UPDATE meeting
    SET set_date = _set_date , id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    IF _time_start IS NOT NULL then
    UPDATE meeting
    SET time_start = _time_start, id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    IF _time_end IS NOT NULL then
    UPDATE meeting
    SET time_end = _time_end , id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    IF _status IS NOT NULL then
    UPDATE meeting
    SET status = _status , id = (SELECT @update_id := id)
    WHERE id = _meetingId;
    END IF;
    UPDATE meeting
    SET updated_at = date(now()) WHERE id = _meetingId;
    SELECT @update_id as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateOfficeRequest`(
	IN `_office_requestId` INT(11), 
    IN `_employeeId` INT(11), 
    IN `_item` VARCHAR(255), 
    IN `_quantity` INT(11), 
    IN `_price` DOUBLE,
    IN `_total_price` DOUBLE,
    IN `_date_needed`DATE,
    IN `_status` INT(11))
BEGIN
	SET sql_safe_updates = 0;
	SET @update_id := 0;
    IF _item IS NOT NULL then
    UPDATE office_request
    SET item = _item , id = (SELECT @update_id := id)
    WHERE id = _office_requestId AND employeeId = _employeeId;
    END IF;
    IF _quantity IS NOT NULL then
    UPDATE office_request
    SET quantity = _quantity , id = (SELECT @update_id := id)
    WHERE id = _office_requestId AND employeeId = _employeeId;
    END IF;
	IF _price IS NOT NULL then
    UPDATE office_request
    SET price = _price , id = (SELECT @update_id := id)
    WHERE id = _office_requestId AND employeeId = _employeeId;
    END IF;
    IF _total_price IS NOT NULL then
    UPDATE office_request
    SET total_price = _total_price , id = (SELECT @update_id := id)
    WHERE id = _office_requestId AND employeeId = _employeeId;
    END IF;
	IF _date_needed IS NOT NULL then
    UPDATE office_request
    SET date_needed = _date_needed , id = (SELECT @update_id := id)
    WHERE id = _office_requestId AND employeeId = _employeeId;
    END IF;
    SELECT @update_id as id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdatePassword`(
	IN _password varchar(255),
    IN _userID int
)
BEGIN
    SET SQL_SAFE_UPDATES = 0;
	update softype.`user` set `password` = _password where id = _userID;
    select row_count() AS result;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserCreateLeaveRequest`(IN `_employeeId` INT(11), IN `_leave_categoryId` INT(11), IN `_date_from` DATE, IN `_date_to` DATE, IN `_reason` VARCHAR(255), IN `_approver` INT(11))
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
		users.username = _username  and users.`password` = _password;
        
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserGetCurrentPassword`(
	IN userID int 
)
BEGIN
	select `password` from softype.user where id = userID;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserGetInfoByEmail`(
    In _email  varchar(255)
)
BEGIN
	select usr.id as userId 
    from softype.user usr 
    join softype.employee emp 
    on usr.employeeId = emp.id
    where email = _email;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserGetProfile`(IN `_userID` INT(11))
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
       email,
       qr_code ,
       position ,
       account_type as roleId,
       emp.sss_no as sss,
       emp.pag_ibig_no as pag_ibig_no ,
       emp.phil_health_no as phil_health_no ,
       emp.profile_img as profile_img
   from
softype.employee emp
join softype.user usr
on emp.id = usr.employeeId
   left join softype.role rl
on emp.roleId = rl.id
   where usr.id = _userID;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verifyRecoveryCode`(
	IN _email varchar(255),
    IN _code varchar(50),
    IN _remove tinyint(1)
)
BEGIN
   if _remove = 0 then
	select id  , email , `code` , created_at from softype.account_recovery where email = _email and `code` =  _code ; 
    else 
    delete from softype.account_recovery where email = _email and `code` =  _code ; 
    end if;
END$$
DELIMITER ;
