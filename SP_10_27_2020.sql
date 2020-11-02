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
   WHERE cr.type = _type;
END