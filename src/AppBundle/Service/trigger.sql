CREATE TRIGGER `add_bcc` BEFORE INSERT ON `animals`
 FOR EACH ROW 
BEGIN
DECLARE i INT DEFAULT 0;
set i =(select id from animals order by `id` desc limit 1);
  IF (i % 2 = 0) THEN 
    SET NEW.id = i+2 ;
  END IF;

END
