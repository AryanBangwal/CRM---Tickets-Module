# CRM---Tickets-Module

File Structure ->
```
/auth           # For Authentication
│── login.html 
│── login.css   
│── login.js           
│── register.html    
│── register.css       
│── register.js         
/dashboard      # Overview of Tickets,User Profile & Account Info
│── dashboard.html    
│── dashboard.css       
│── dashboard.js       
/tickets        # For tickets
│── index.html    
│── create.html
│── edit.html
│── assignment.html
│── status.html
│── tickets.css     
│── tickets.js          
/users          # See a list of registered users 
│── users.html          
│── users.css           
│── users.js            
/components     # Could be used anywhere
│── navbar.html        
│── navbar.css         
│── navbar.js         
│── footer.html         
│── footer.css          
/utils          # Reusable helper functions
│── validation.js       # Form validation 
│── auth.js             # e.g. checking login status
│── permissions.js      # To see if only admin is there or not
/assets
│── /css
│   ├── main.css      
│── /js
│   ├── main.js         
│── /images
│   ├── logo.png        # Images
/config
│── env.js              # Environment variables
│── constants.js        
│── README.md                     
```
[Database Layout](/database-layout.mermaid)