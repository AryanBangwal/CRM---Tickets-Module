-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Tickets Table
CREATE TABLE IF NOT EXISTS tickets (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'pending' NOT NULL,
    created_by INT NOT NULL, 
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    completed_at TIMESTAMP,
    deleted_at TIMESTAMP
);

-- Assignments Table
CREATE TABLE IF NOT EXISTS assignments (
    id SERIAL PRIMARY KEY,
    ticket_id INT,
    assigned_to INT,
    assigned_at TIMESTAMP DEFAULT NOW(),
    UNIQUE (ticket_id, assigned_to)
);

-- Track Missing Fields in Tickets
CREATE TABLE IF NOT EXISTS ticket_missing_fields (
    id SERIAL PRIMARY KEY,
    ticket_id INT,
    missing_field VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);


-- Alter
-- Alter Table users drop column role;

-- Alter table tickets add new column
ALTER TABLE tickets 
ADD COLUMN assignee_id INT;







