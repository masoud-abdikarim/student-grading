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
  `otp` VARCHAR(10) DEFAULT '0',
  `class_id` INT(10) UNSIGNED NULL
);

CREATE TABLE `exam` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `examname` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `year` VARCHAR(20) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `category` ENUM('Main', 'Other') NOT NULL DEFAULT 'Other',
  `pass_mark` INT DEFAULT 50,
  `is_locked` TINYINT DEFAULT 0,
  `class_id` INT(10) UNSIGNED NULL

);

CREATE TABLE `assignment` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `assignment` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `duedate` DATE NOT NULL,
  `file` VARCHAR(255) NOT NULL,
  `class_id` INT(10) UNSIGNED NULL
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

CREATE TABLE `classes` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `class_name` VARCHAR(100) NOT NULL
);

-- Note: studentlist, exam, and assignment should have a class_id INT(10) UNSIGNED NULL column.

CREATE TABLE `assignment_responses` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `assignment_id` INT(10) UNSIGNED NOT NULL,
  `enroll` VARCHAR(50) NOT NULL,
  `submited` DATE,
  `marks` INT(10) DEFAULT NULL,
  `file` VARCHAR(100)
);

-- Example dynamic table for exam:
-- CREATE TABLE `table_name` (id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, enroll INT(10), sub1 INT(10), sub2 INT(10), sub3 INT(10), sub4 INT(10), sub5 INT(10), sub6 INT(10), sub7 INT(10));

CREATE TABLE `results` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `exam_id` INT(10) UNSIGNED NOT NULL,
  `enroll` VARCHAR(50) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `marks` INT(10) DEFAULT 0,
  UNIQUE KEY `student_exam_subject` (`exam_id`, `enroll`, `subject`)
);

CREATE TABLE `academic_history` (
  `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `enroll` VARCHAR(50) NOT NULL,
  `year` VARCHAR(20) NOT NULL,
  `class_id` INT(10) UNSIGNED NOT NULL,
  `average_marks` DECIMAL(5,2) DEFAULT 0,
  `status` VARCHAR(50) DEFAULT 'Active'
);

