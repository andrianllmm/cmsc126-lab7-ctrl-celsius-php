USE cmsc126_db;

DELIMITER $$

DROP TRIGGER IF EXISTS generate_student_id $$

CREATE TRIGGER generate_student_id
BEFORE INSERT ON students
FOR EACH ROW
BEGIN
    DECLARE next_id INT;
    DECLARE start_year INT;

    -- Increment sequence
    UPDATE student_id_seq SET next_val = next_val + 1;

    -- Get the value we just reserved
    SELECT next_val - 1 INTO next_id FROM student_id_seq;

    -- Compute student ID
    SET start_year = YEAR(CURDATE()) - NEW.year_level;
    SET NEW.student_id = CONCAT(start_year, '-', LPAD(next_id, 5, '0'));
END$$

DELIMITER ;