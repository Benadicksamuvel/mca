-- Create the database
CREATE DATABASE IF NOT EXISTS bookinv;
USE bookinv;

-- Create the register table
CREATE TABLE IF NOT EXISTS register (
  user_id  INT AUTO_INCREMENT PRIMARY KEY,
  name     VARCHAR(100)  NOT NULL,
  password VARCHAR(255)  NOT NULL,
  phone    VARCHAR(15)   NOT NULL,
  address  TEXT          NOT NULL,
  email    VARCHAR(100)  NOT NULL
);
