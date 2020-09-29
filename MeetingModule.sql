DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addMember`(IN _meetingId int(11), IN _memberId int(11))
BEGIN
	INSERT INTO calendar_invites(meetingId, memberId)
    VALUES (_meetingId, _memberId);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `createMeeting`(
		IN _title VARCHAR(255), IN _organizer int(11),IN _category VARCHAR(255), IN _description VARCHAR(255),
		IN _set_date date, IN _time_start VARCHAR(255), IN _time_end VARCHAR(255), IN _status VARCHAR(255)
	)
BEGIN
	INSERT INTO meeting(title, organizer, category, description, set_date, time_start, time_end, status)
	VALUES (_title, _organizer, _category, _description, _set_date, _time_start, _time_end, _status);
	
	SELECT * FROM meeting WHERE `id` = (SELECT LAST_INSERT_ID());
	
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
END$$
DELIMITER ;
