CREATE TABLE users (
    id NUMBER PRIMARY KEY, 
    name varchar(255) NOT NULL, 
    email varchar(255) UNIQUE NOT NULL, 
    password varchar(255) NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP
);

CREATE TABLE assignments (
    id NUMBER PRIMARY KEY, 
    assigned_to NUMBER NOT NULL, 
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE user_details (
    id NUMBER PRIMARY KEY, 
    name varchar(255) NOT NULL, 
    description varchar(255), 
    status varchar(255), 
    file varchar(255), 
    created_by NUMBER NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP, 
    deleted_at TIMESTAMP, 
    completed_at TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
