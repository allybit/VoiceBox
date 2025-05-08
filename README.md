# Anonymous Feedback Platform

A comprehensive web application that allows students to provide anonymous feedback about courses, instructors, and campus facilities. The platform includes user verification, content moderation, and a voting system to highlight the most valuable feedback.

## Overview

The Anonymous Feedback Platform is designed to create a safe and constructive environment for students to share their thoughts and experiences. All feedback is moderated to ensure respectful communication, while preserving the anonymity of the contributors.

## Features

### User Management
- User registration with school email verification
- Admin verification of student accounts
- User profiles with feedback history
- Role-based access control (admin/student)

### Content Management
- Anonymous post creation with tags
- Post moderation by administrators
- Upvote/downvote system
- Commenting system
- Tag-based filtering and organization

### Administration
- Admin dashboard with statistics
- User verification management
- Content moderation tools
- Announcement creation and management
- Tag management

### Other Features
- Responsive design for mobile and desktop
- Real-time feedback with AJAX
- Secure authentication and authorization
- Cross-site request forgery (CSRF) protection

## Technologies Used

- **Backend**: PHP 8.0+, CodeIgniter 4 Framework
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Database**: MySQL/MariaDB
- **Dependencies**: jQuery, Font Awesome

## Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher / MariaDB 10.3 or higher
- Composer
- Web server (Apache/Nginx)

### Setup Instructions

1. **Clone the repository**
   \`\`\`bash
   git clone https://github.com/yourusername/anonymous-feedback.git
   cd anonymous-feedback
   \`\`\`

2. **Install dependencies**
   \`\`\`bash
   composer install
   \`\`\`

3. **Configure environment**
   - Copy the `env` file to `.env`
   \`\`\`bash
   cp env .env
   \`\`\`
   - Update the database configuration in `.env`:
   \`\`\`
   database.default.hostname = localhost
   database.default.database = anonymous_feedback
   database.default.username = your_username
   database.default.password = your_password
   database.default.DBDriver = MySQLi
   \`\`\`

4. **Set up the database**
   - Create a database named `anonymous_feedback`
   - Run migrations to create the tables:
   \`\`\`bash
   php spark migrate
   \`\`\`
   - (Optional) Seed the database with initial data:
   \`\`\`bash
   php spark db:seed InitialSeeder
   \`\`\`

5. **Configure email settings**
   - Update the email configuration in `.env` for user verification emails:
   \`\`\`
   email.fromEmail = noreply@yourschool.edu
   email.fromName = 'Anonymous Feedback Platform'
   email.SMTPHost = your_smtp_host
   email.SMTPUser = your_smtp_username
   email.SMTPPass = your_smtp_password
   email.SMTPPort = 587
   email.SMTPCrypto = tls
   \`\`\`

6. **Set up the web server**
   - Configure your web server to point to the `public` directory
   - Ensure the `writable` directory is writable by the web server

7. **Start the application**
   - For development, you can use CodeIgniter's built-in server:
   \`\`\`bash
   php spark serve
   \`\`\`
   - Access the application at `http://localhost:8080`

## Database Structure

The application uses the following main tables:

- `users` - User accounts and authentication
- `posts` - Feedback posts
- `tags` - Categories for organizing feedback
- `post_tags` - Many-to-many relationship between posts and tags
- `votes` - Upvotes and downvotes on posts
- `comments` - User comments on posts
- `announcements` - System-wide announcements
- `verification_history` - History of user verification actions

## User Roles

### Administrator
- Verify student accounts
- Approve, reject, or delete posts
- Manage tags
- Create announcements
- Delete comments
- Access to admin dashboard

### Student
- Create anonymous feedback posts
- Vote on posts
- Comment on posts
- View their own post history
- Update their profile

## Configuration Options

### School Email Domains
You can configure allowed school email domains in the `Auth.php` controller:

\`\`\`php
$allowedDomains = ['school.edu', 'university.edu']; // Add your school domains here
\`\`\`

### Post Moderation
By default, all posts require admin approval. This can be changed in the `Post.php` controller.

### User Verification
By default, all student accounts require admin verification. This can be configured in the `Auth.php` controller.

## Usage Guide

### For Students

1. **Registration**
   - Register with your school email address
   - Wait for admin verification of your account

2. **Creating Feedback**
   - Click "Create Post" in the navigation menu
   - Fill in the title, content, and select relevant tags
   - Submit for admin approval

3. **Interacting with Feedback**
   - Upvote or downvote posts to indicate usefulness
   - Comment on posts to add additional information
   - Filter posts by tags to find relevant feedback

### For Administrators

1. **User Verification**
   - Access the "Verify Users" page
   - Review student information and verify legitimate accounts

2. **Content Moderation**
   - Review pending posts in the "Pending Posts" section
   - Approve, reject, or delete posts as appropriate
   - Delete inappropriate comments

3. **Announcements**
   - Create announcements to communicate important information
   - Manage existing announcements

## Security Considerations

- All user passwords are hashed using PHP's password_hash() function
- CSRF protection is enabled for all forms
- Input validation and escaping is implemented throughout the application
- Role-based access control prevents unauthorized actions

## Troubleshooting

### Common Issues

1. **Database Connection Errors**
   - Verify your database credentials in the `.env` file
   - Ensure the database server is running

2. **Email Configuration Issues**
   - Check your SMTP settings in the `.env` file
   - Verify that your SMTP server allows the connection

3. **Permission Issues**
   - Ensure the `writable` directory has the correct permissions
   - Check that the web server has write access to necessary directories

## Contributing

Contributions to the Anonymous Feedback Platform are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgements

- [CodeIgniter](https://codeigniter.com/) - The web framework used
- [Bootstrap](https://getbootstrap.com/) - Frontend framework
- [Font Awesome](https://fontawesome.com/) - Icons
- [jQuery](https://jquery.com/) - JavaScript library
