USE cmsc126_db;

USE cmsc126_db;

-- Clean slate before seeding
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE students;
TRUNCATE TABLE courses;
SET FOREIGN_KEY_CHECKS = 1;

INSERT IGNORE INTO courses (course_name) VALUES
('BS Computer Science'),
('BS Applied Mathematics'),
('BS Statistics');

-- Resolve course IDs first
SET @cs  = (SELECT id FROM courses WHERE course_name = 'BS Computer Science');
SET @am  = (SELECT id FROM courses WHERE course_name = 'BS Applied Mathematics');
SET @sta = (SELECT id FROM courses WHERE course_name = 'BS Statistics');

INSERT INTO students (name, age, email, course_id, year_level, graduation_status, image_path) VALUES

-- BS Computer Science
('Joseph Victor Sumbong',  24, 'jvsumbong@up.edu.ph',      @cs,  4, 0, 'seed.jpg'),
('Maria Clara Reyes',      20, 'mcreyes@up.edu.ph',        @cs,  1, 0, NULL),
('Juan Paolo Santos',      21, 'jpsantos@up.edu.ph',       @cs,  2, 0, NULL),
('Angela Dizon',           22, 'adizon@up.edu.ph',         @cs,  3, 0, NULL),
('Rafael Mendoza',         23, 'rmendoza@up.edu.ph',       @cs,  4, 0, NULL),
('Sophia Lim',             20, 'slim@up.edu.ph',           @cs,  1, 0, NULL),
('Carlo Bautista',         21, 'cbautista@up.edu.ph',      @cs,  2, 0, NULL),
('Isabelle Cruz',          22, 'icruz@up.edu.ph',          @cs,  3, 0, NULL),
('Marco Villanueva',       23, 'mvillanueva@up.edu.ph',    @cs,  4, 1, NULL),
('Rina Dela Torre',        20, 'rdelatorre@up.edu.ph',     @cs,  1, 0, NULL),
('Luis Aquino',            21, 'laquino@up.edu.ph',        @cs,  2, 0, NULL),
('Patricia Gomez',         24, 'pgomez@up.edu.ph',         @cs,  4, 1, NULL),

-- BS Applied Mathematics
('Andrea Fontaine',        20, 'afontaine@up.edu.ph',      @am,  1, 0, NULL),
('Miguel Torres',          21, 'mtorres@up.edu.ph',        @am,  2, 0, NULL),
('Camille Navarro',        22, 'cnavarro@up.edu.ph',       @am,  3, 0, NULL),
('Dante Ramos',            23, 'dramos@up.edu.ph',         @am,  4, 0, NULL),
('Francesca Ocampo',       20, 'focampo@up.edu.ph',        @am,  1, 0, NULL),
('Andrei Pascual',         21, 'apascual@up.edu.ph',       @am,  2, 0, NULL),
('Lorena Castillo',        22, 'lcastillo@up.edu.ph',      @am,  3, 0, NULL),
('Renz Magno',             24, 'rmagno@up.edu.ph',         @am,  4, 1, NULL),
('Therese Aguilar',        20, 'taguilar@up.edu.ph',       @am,  1, 0, NULL),
('Paolo Ferrer',           21, 'pferrer@up.edu.ph',        @am,  2, 0, NULL),

-- BS Statistics
('Nina Salazar',           20, 'nsalazar@up.edu.ph',       @sta, 1, 0, NULL),
('Jerome Dela Cruz',       21, 'jdelacruz@up.edu.ph',      @sta, 2, 0, NULL),
('Bianca Flores',          22, 'bflores@up.edu.ph',        @sta, 3, 0, NULL),
('Kevin Macaraeg',         23, 'kmacaraeg@up.edu.ph',      @sta, 4, 0, NULL),
('Alyssa Tan',             20, 'atan@up.edu.ph',           @sta, 1, 0, NULL),
('Christian Abad',         21, 'cabad@up.edu.ph',          @sta, 2, 0, NULL),
('Maricel Soriano',        22, 'msoriano@up.edu.ph',       @sta, 3, 0, NULL),
('Felix Ignacio',          24, 'fignacio@up.edu.ph',       @sta, 4, 1, NULL),
('Danielle Reyes',         20, 'dreyes@up.edu.ph',         @sta, 1, 0, NULL),
('Samuel Evangelista',     21, 'sevangelista@up.edu.ph',   @sta, 2, 0, NULL);
