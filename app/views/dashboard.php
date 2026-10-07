<?php

require "app/includes/auth_check.php";
require "app/includes/header.php";
?>
<div class="mb-4">
    <h1 class="display-6 fw-semibold">Dashboard</h1>
    <p class="text-secondary mb-0">
        Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>. Manage your campus events here.
    </p>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5">Event management</h2>
                <p class="text-secondary">Create, edit, and review campus activities.</p>
                <a class="btn btn-primary" href="index.php?action=events">View events</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5">Create an event</h2>
                <p class="text-secondary">Add a new activity to the campus calendar.</p>
                <a class="btn btn-outline-primary" href="index.php?action=create">Add new event</a>
            </div>
        </div>
    </div>
</div>

<?php require "app/includes/footer.php"; ?>