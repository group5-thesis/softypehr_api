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
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteTicket`(IN _ticketId INT(11))
BEGIN
	SET sql_safe_updates = 0;
    DELETE FROM ticket
    WHERE id = _ticketId;
    
    call retrieveTickets();
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
