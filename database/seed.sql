INSERT IGNORE INTO courses (course_name) VALUES
('BS Computer Science'),
('BS Applied Mathematics'),
('BS Statistics');

INSERT IGNORE INTO students (name, age, email, course_id, year_level, graduation_status, image_path)
VALUES
(
  'Joseph Victor Sumbong',
  24,
  'jvsumbong@up.edu.ph',
  (SELECT id FROM courses WHERE course_name = 'BS Computer Science'),
  4,
  0,
  'seed.jpg'
);
