CREATE TABLE `feedback` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `firstname` VARCHAR(50) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `message` TEXT NOT NULL
);

CREATE TABLE `teacherlist` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `firstname` VARCHAR(50) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `usertype` VARCHAR(20) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `otp` VARCHAR(10) DEFAULT '0'
);

CREATE TABLE `studentlist` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `enroll` VARCHAR(50) NOT NULL,
  `firstname` VARCHAR(50) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `usertype` VARCHAR(20) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `otp` VARCHAR(10) DEFAULT '0'
);

CREATE TABLE `exam` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `examname` VARCHAR(100) NOT NULL,
  `year` VARCHAR(20) NOT NULL,
  `type` VARCHAR(50) NOT NULL
);

CREATE TABLE `assignment` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `assignment` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `duedate` DATE NOT NULL,
  `file` VARCHAR(255) NOT NULL
);

CREATE TABLE `login` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `usertype` VARCHAR(20) NOT NULL
);

-- Insert a default admin user
INSERT INTO `login` (`username`, `password`, `usertype`) VALUES ('admin', 'admin', 'admin');

-- Note: The `add_assignment.php` and `add_exam.php` scripts dynamically create tables for individual assignments and exams.
-- Example dynamic table for assignment:
-- CREATE TABLE `assign_name` (id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, enroll INT(10), submited DATE, marks INT(10), file VARCHAR(100));

-- Example dynamic table for exam:
-- CREATE TABLE `table_name` (id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, enroll INT(10), sub1 INT(10), sub2 INT(10), sub3 INT(10), sub4 INT(10), sub5 INT(10), sub6 INT(10), sub7 INT(10));

CREATE TABLE `results` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `exam_id` INT(10) UNSIGNED NOT NULL,
  `enroll` VARCHAR(50) NOT NULL,
  `sub1` INT(10) DEFAULT 0,
  `sub2` INT(10) DEFAULT 0,
  `sub3` INT(10) DEFAULT 0,
  `sub4` INT(10) DEFAULT 0,
  `sub5` INT(10) DEFAULT 0,
  `sub6` INT(10) DEFAULT 0,
  `sub7` INT(10) DEFAULT 0,
  UNIQUE KEY `student_exam` (`exam_id`, `enroll`)
);
