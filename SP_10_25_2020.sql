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
        IN `_role` VARCHAR(255))
BEGIN
	DECLARE _roleId int(11);
    IF NOT EXISTS(SELECT id FROM softype.`role` WHERE position = _role)
	THEN
		INSERT INTO softype.`role` (position) VALUES (_role);
		SELECT last_insert_id() into _roleID;
	ELSE
		SELECT id FROM softype.`role` WHERE position = _role into _roleID;
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
			roleId
		)
	VALUES (_firstname, _middlename, _lastname, _mobileno, _gender, _email, _birthdate, _street, _city, _country, _roleID);
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
