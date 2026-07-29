CREATE DATABASE IF NOT EXISTS guitarghar;
USE guitarghar;

-- Users
CREATE TABLE users (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    full_name    VARCHAR(100)  NOT NULL,
    email        VARCHAR(100)  NOT NULL UNIQUE,
    password     VARCHAR(255)  NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Lessons content
CREATE TABLE lessons (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id    VARCHAR(10)   NOT NULL UNIQUE,
    level        ENUM('beginner','intermediate','advanced') NOT NULL,
    sort_order   INT           NOT NULL DEFAULT 0,
    title        VARCHAR(150)  NOT NULL,
    description  TEXT          NOT NULL,
    video_url    VARCHAR(255)  DEFAULT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Lesson progress per user
CREATE TABLE lesson_progress (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT           NOT NULL,
    lesson_id    VARCHAR(10)   NOT NULL,
    completed    TINYINT(1)    DEFAULT 1,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Guitar builds
CREATE TABLE guitar_builds (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT           NOT NULL,
    shape        VARCHAR(50)   NOT NULL,
    color        VARCHAR(10)   NOT NULL,
    body_wood    VARCHAR(50)   NOT NULL,
    neck_wood    VARCHAR(50)   NOT NULL,
    fingerboard  VARCHAR(50)   NOT NULL,
    pickups      VARCHAR(50)   NOT NULL,
    bridge       VARCHAR(50)   NOT NULL,
    hardware     VARCHAR(50)   NOT NULL,
    saved_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- AI Recommendation history
CREATE TABLE recommendations (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT           NOT NULL,
    skill_level  VARCHAR(50)   NOT NULL,
    genre        VARCHAR(50)   NOT NULL,
    guitar_type  VARCHAR(50)   NOT NULL,
    budget       VARCHAR(50)   NOT NULL,
    extra_note   TEXT,
    result       TEXT          NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);