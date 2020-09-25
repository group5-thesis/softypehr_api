DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateEmployee`(IN `_firstname` VARCHAR(255), IN `_middlename` VARCHAR(255), IN `_lastname` VARCHAR(255), IN `_mobileno` VARCHAR(255), IN `_gender` VARCHAR(255), IN `_email` VARCHAR(255), IN `_birthdate` VARCHAR(255),  IN `_street` VARCHAR(255), IN `_city` VARCHAR(255), IN `_country` VARCHAR(255), IN `_roleId` INT(11))
    NO SQL
BEGIN
	INSERT INTO employee(firstname, middlename, lastname, mobileno, gender, email, birthdate, street, city, country, roleId) VALUES (_firstname, _middlename, _lastname, _mobileno, _gender, _email, _birthdate, _street, _city, _country, _roleId);
    SELECT * FROM employee WHERE id = (SELECT LAST_INSERT_ID());
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateEmployeeAccount`(IN `_username` VARCHAR(255), IN `_email` VARCHAR(255), IN `_password` VARCHAR(255), IN `_qrcode` VARCHAR(255), IN `_employeeId` INT(11))
    NO SQL
BEGIN
	INSERT INTO `user` (`username`, `email`, `password`, `qr_code`, `employeeId`) VALUES(_username, _email, _password, _qrcode, _employeeId);
    SELECT * FROM `user` WHERE id = (SELECT LAST_INSERT_ID());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveEmployees`()
    NO SQL
BEGIN
	SELECT *  FROM employee;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveLimitedEmployee`(IN `_id` INT(11))
BEGIN
	SELECT * from employee WHERE id = _id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RetrieveUsers`()
    NO SQL
BEGIN
	SELECT * from `user`;
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
