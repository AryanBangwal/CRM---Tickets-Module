CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role VARCHAR(50) CHECK (role IN ('author', 'assignee')) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tickets (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    status VARCHAR(20) CHECK (status IN ('pending', 'inprogress', 'completed', 'onhold')) DEFAULT 'pending' NOT NULL,
    created_by INT NOT NULL REFERENCES users(id) ON DELETE CASCADE, 
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    completed_at TIMESTAMP,
    deleted_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS assignments (
    id SERIAL PRIMARY KEY,
    ticket_id INT REFERENCES tickets(id) ON DELETE CASCADE,
    assigned_to INT REFERENCES users(id) ON DELETE CASCADE,
    assigned_at TIMESTAMP DEFAULT NOW(),
    UNIQUE (ticket_id, assigned_to)
);

CREATE TABLE IF NOT EXISTS ticket_missing_fields (
    id SERIAL PRIMARY KEY,
    ticket_id INT REFERENCES tickets(id) ON DELETE CASCADE,
    missing_field VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

ALTER TABLE tickets 
ADD COLUMN assignee_id INT REFERENCES users(id) ON DELETE SET NULL AFTER description;