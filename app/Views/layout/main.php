<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoiceBox</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 56px;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            color: #f0ad4e !important;
        }
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #f8f9fa;
            border-top: 1px solid #e7e7e7;
        }
        .vote-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .vote-count {
            margin: 5px 0;
            font-weight: bold;
        }
        .post-card {
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .post-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #e7e7e7;
            border-radius: 8px 8px 0 0;
        }
        .post-content {
            padding: 15px;
        }
        .post-footer {
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #e7e7e7;
            border-radius: 0 0 8px 8px;
        }
        .tag {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        /* Improved vote button styling */
        .vote-btn {
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s ease;
            padding: 5px;
            border-radius: 4px;
        }
        .vote-btn:hover {
            background-color: #f8f9fa;
        }
        .vote-btn.text-primary {
            color: #0d6efd !important;
        }
        .vote-btn.text-danger {
            color: #dc3545 !important;
        }
        .vote-btn.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        /* Comment styles */
        .comment-card {
            border-left: 3px solid #dee2e6;
            margin-bottom: 15px;
        }
        .comment-header {
            background-color: #f8f9fa;
            padding: 8px 15px;
            border-bottom: 1px solid #e7e7e7;
        }
        .comment-content {
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>"> VoiceBox</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Home</a>
                    </li>
                    
                    <?php if (session()->get('isLoggedIn')): ?>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <!-- Admin-only navigation items -->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/pending-posts') ?>">Pending Posts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/approved-posts') ?>">Approved Posts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/manage-tags') ?>">Manage Tags</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/announcements') ?>">Announcements</a>
                            </li>
                            <!-- Remove the Pending Comments link -->
                            <!-- Verify Users link - admin only -->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('verify-users') ?>">Verify Users</a>
                            </li>
                        <?php else: ?>
                            <!-- Student-only navigation items -->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('post/create') ?>">Create Post</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('post/my-posts') ?>">My Posts</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= esc(session()->get('username')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?php 
                $error = session()->getFlashdata('error');
                if (is_array($error)) {
                    echo '<ul class="mb-0">';
                    foreach ($error as $errorItem) {
                        echo '<li>' . esc($errorItem) . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo esc($error);
                }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?= $this->renderSection('content') ?>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?= date('Y') ?> VoiceBox</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Providing a voice for students</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Common JavaScript for the application
        $(document).ready(function() {
            // Vote functionality with simplified error handling
            $('.vote-btn').click(function(e) {
                e.preventDefault();
                
                // If not logged in, redirect to login
                <?php if (!session()->get('isLoggedIn')): ?>
                    window.location.href = '<?= base_url('login') ?>';
                    return;
                <?php endif; ?>
                
                var postId = $(this).data('post-id');
                var voteType = $(this).data('vote-type');
                var voteBtn = $(this);
                
                // Disable both vote buttons for this post
                $('.vote-btn[data-post-id="' + postId + '"]').addClass('disabled');
                
                $.ajax({
                    url: '<?= base_url('vote') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        post_id: postId,
                        vote_type: voteType,
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        // Always update the UI, even if there was an error
                        
                        // Update vote count
                        if (response.voteCount !== undefined) {
                            $('#vote-count-' + postId).text(response.voteCount);
                        }
                        
                        // Reset both buttons
                        $('#upvote-' + postId).removeClass('text-primary');
                        $('#downvote-' + postId).removeClass('text-danger');
                        
                        // Set the correct button state based on user's current vote
                        if (response.userVoteStatus == 1) {
                            $('#upvote-' + postId).addClass('text-primary');
                        } else if (response.userVoteStatus == -1) {
                            $('#downvote-' + postId).addClass('text-danger');
                        }
                        
                        // Re-enable the buttons
                        setTimeout(function() {
                            $('.vote-btn[data-post-id="' + postId + '"]').removeClass('disabled');
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        // Even on error, don't show an error message
                        console.log("Vote processing error (hidden from user):", error);
                        
                        // Re-enable the buttons
                        setTimeout(function() {
                            $('.vote-btn[data-post-id="' + postId + '"]').removeClass('disabled');
                        }, 500);
                    }
                });
            });
        });
    </script>
</body>
</html>
