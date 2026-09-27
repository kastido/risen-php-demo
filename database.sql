CREATE DATABASE IF NOT EXISTS risen_php_demo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE risen_php_demo;


-- -----------------------------------------------------
-- Rises
-- -----------------------------------------------------

CREATE TABLE rises (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    start_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- -----------------------------------------------------
-- Moments
-- -----------------------------------------------------

CREATE TABLE moments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rise_id INT UNSIGNED NOT NULL,
    moment_text TEXT NOT NULL,
    moment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_moments_rise
        FOREIGN KEY (rise_id)
        REFERENCES rises(id)
        ON DELETE CASCADE
);


-- -----------------------------------------------------
-- Memories
-- -----------------------------------------------------

CREATE TABLE memories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rise_id INT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    memory_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_memories_rise
        FOREIGN KEY (rise_id)
        REFERENCES rises(id)
        ON DELETE CASCADE
);


-- -----------------------------------------------------
-- Milestones
-- -----------------------------------------------------

CREATE TABLE milestones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rise_id INT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    notes TEXT,
    milestone_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_milestones_rise
        FOREIGN KEY (rise_id)
        REFERENCES rises(id)
        ON DELETE CASCADE
);